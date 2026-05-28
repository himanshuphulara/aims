<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class DatabaseBackupService
{
    /**
     * @return array{success: bool, message: string, file?: string, relative_path?: string}
     */
    public function createBackup(): array
    {
        $mysqldump = $this->resolveMysqldumpPath();
        if (! $mysqldump) {
            return [
                'success' => false,
                'message' => 'mysqldump was not found. Install MySQL/MariaDB client tools (XAMPP, MySQL Server, or Homebrew mysql-client).',
            ];
        }

        $backupDir = storage_path('app/public/' . config('backup.storage_subdirectory', 'backups'));
        if (! File::isDirectory($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $backupFileName = 'backup_' . date('Y-m-d_His') . '.sql';
        $absolutePath = $backupDir . DIRECTORY_SEPARATOR . $backupFileName;
        $relativePath = '/' . config('backup.storage_subdirectory', 'backups') . '/' . $backupFileName;

        $connection = config('database.connections.' . config('database.default', 'mysql'));
        $host = $connection['host'] ?? '127.0.0.1';
        $port = (string) ($connection['port'] ?? '3306');
        $database = $connection['database'] ?? '';
        $username = $connection['username'] ?? 'root';
        $password = (string) ($connection['password'] ?? '');

        if ($database === '') {
            return ['success' => false, 'message' => 'Database name is not configured.'];
        }

        $args = [
            $mysqldump,
            '--single-transaction',
            '--quick',
            '--lock-tables=false',
            '--extended-insert',
            '--set-gtid-purged=OFF',
            '-h', $host,
            '-P', $port,
            '-u', $username,
            $database,
        ];

        if ($password !== '') {
            array_splice($args, -1, 0, ['-p' . $password]);
        }

        $output = [];
        $resultCode = $this->runDumpToFile($args, $absolutePath, $output);

        if ($resultCode === 0 && is_file($absolutePath) && filesize($absolutePath) > 0) {
            return [
                'success' => true,
                'message' => 'Backup file created successfully.',
                'file' => $absolutePath,
                'relative_path' => $relativePath,
            ];
        }

        if (is_file($absolutePath) && filesize($absolutePath) === 0) {
            @unlink($absolutePath);
        }

        $detail = trim(implode("\n", $output));

        return [
            'success' => false,
            'message' => 'Backup failed (exit code ' . $resultCode . ').'
                . ($detail !== '' ? ' ' . $detail : ''),
        ];
    }

    public function resolveMysqldumpPath(): ?string
    {
        $configured = config('backup.mysqldump_path');
        if (is_string($configured) && $configured !== '' && $this->isRunnableBinary($configured)) {
            return $configured;
        }

        $fromPath = $this->findInSystemPath();
        if ($fromPath) {
            return $fromPath;
        }

        foreach ($this->commonInstallPaths() as $candidate) {
            if ($this->isRunnableBinary($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    private function findInSystemPath(): ?string
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $raw = shell_exec('where mysqldump 2>nul');
        } else {
            $raw = shell_exec('command -v mysqldump 2>/dev/null');
        }

        if (! is_string($raw) || trim($raw) === '') {
            return null;
        }

        foreach (preg_split('/\R/', trim($raw)) as $line) {
            $line = trim($line);
            if ($line !== '' && $this->isRunnableBinary($line)) {
                return $line;
            }
        }

        return null;
    }

    /**
     * @return list<string>
     */
    private function commonInstallPaths(): array
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $paths = [
                'C:\\xampp\\mysql\\bin\\mysqldump.exe',
                'C:\\xampp\\mysql\\bin\\mysqldump',
                'C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
                'C:\\Program Files\\MySQL\\MySQL Server 8.4\\bin\\mysqldump.exe',
                'C:\\Program Files\\MySQL\\MySQL Server 5.7\\bin\\mysqldump.exe',
                'C:\\Program Files\\MariaDB 11.4\\bin\\mysqldump.exe',
                'C:\\Program Files\\MariaDB 10.11\\bin\\mysqldump.exe',
            ];

            foreach (glob('C:\\Program Files\\MySQL\\MySQL Server *\\bin\\mysqldump.exe') ?: [] as $match) {
                $paths[] = $match;
            }
            foreach (glob('C:\\laragon\\bin\\mysql\\mysql-*\\bin\\mysqldump.exe') ?: [] as $match) {
                $paths[] = $match;
            }
            foreach (glob('C:\\wamp64\\bin\\mysql\\mysql*\\bin\\mysqldump.exe') ?: [] as $match) {
                $paths[] = $match;
            }

            return array_values(array_unique($paths));
        }

        return [
            '/opt/homebrew/bin/mysqldump',
            '/usr/local/bin/mysqldump',
            '/usr/local/mysql/bin/mysqldump',
            '/usr/bin/mysqldump',
            '/opt/local/bin/mysqldump',
        ];
    }

    private function isRunnableBinary(string $path): bool
    {
        if (! file_exists($path)) {
            return false;
        }

        if (PHP_OS_FAMILY === 'Windows') {
            return is_file($path);
        }

        return is_executable($path);
    }

    /**
     * @param  list<string>  $args
     * @param  list<string>  $output
     */
    private function runDumpToFile(array $args, string $outputFile, array &$output): int
    {
        $descriptorSpec = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $binary = array_shift($args);
        $command = escapeshellarg($binary) . ' ' . implode(' ', array_map('escapeshellarg', $args));

        $process = proc_open($command, $descriptorSpec, $pipes);
        if (! is_resource($process)) {
            $output[] = 'Could not start mysqldump process.';

            return 1;
        }

        fclose($pipes[0]);
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        if ($stdout !== false && $stdout !== '') {
            $output[] = $stdout;
        }
        if ($stderr !== false && $stderr !== '') {
            $output[] = $stderr;
        }

        $exitCode = proc_close($process);

        if ($stdout !== false && $stdout !== '') {
            file_put_contents($outputFile, $stdout);
        } elseif ($exitCode === 0) {
            touch($outputFile);
        }

        return $exitCode;
    }
}
