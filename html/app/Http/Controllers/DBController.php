<?php

namespace App\Http\Controllers;

use App\Models\DBBackup;
use App\Services\DatabaseBackupService;
use Illuminate\Http\Request;

class DBController extends Controller
{
    public function __construct(
        private readonly DatabaseBackupService $backupService,
    ) {
    }

    public function dbList()
    {
        $title = 'Database Backups';
        $backups = DBBackup::orderBy('id', 'DESC')->paginate(10);
        $latestentry = DBBackup::orderBy('id', 'DESC')->limit(1)->first();

        return view('dbfiles', compact('title', 'backups', 'latestentry'));
    }

    public function backUp()
    {
        $result = $this->backupService->createBackup();

        session()->flash($result['success'] ? 'success' : 'error', $result['message']);

        if ($result['success']) {
            DBBackup::create([
                'name' => 'backup_' . date('Y-m-d_H:i:s'),
                'file' => $result['relative_path'],
            ]);
        }

        return redirect()->back();
    }

    public function download($id)
    {
        $file = DBBackup::where('id', $id)->first();
        if (! $file) {
            return response()->json(['status' => false], 404);
        }

        $fileToDownload = storage_path('app/public' . $file->file);
        $newName = date('l_d_M_Y_h:i:s_A', strtotime($file->created_at)) . '.sql';
        if (! file_exists($fileToDownload)) {
            return response()->json(['status' => false], 404);
        }
        DBBackup::where('id', $id)->update(['downloaded' => 1]);

        return response()->download($fileToDownload, $newName);
    }

    public function deleteBackup($id)
    {
        $file = DBBackup::where('id', $id)->first();
        if (! $file) {
            session()->flash('error', 'Backup file does not exist.');

            return redirect()->back();
        }

        $fileToDelete = storage_path('app/public' . $file->file);
        if (file_exists($fileToDelete)) {
            DBBackup::where('id', $id)->delete();
            unlink($fileToDelete);
            session()->flash('success', 'Backup file deleted successfully.');

            return redirect()->back();
        }

        session()->flash('error', 'Backup file does not exist on disk.');

        return redirect()->back();
    }
}
