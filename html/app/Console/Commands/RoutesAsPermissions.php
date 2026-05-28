<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
class RoutesAsPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'routes-as-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add Routes as Permissions in Permission Table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $routes = Route::getRoutes();
        $routeNames = [];

        foreach ($routes as $route) {
            if ($route->getName()) {
                $routeNames[] = $route->getName();
            }
        }
        $valuesToRemove = ['signup', 'signin','logout'];
        $result = array_diff($routeNames, $valuesToRemove);
        $routeNames = array_values($result);
        
        // print_r($routeNames);
        try{
            foreach($routeNames as $route)
            $inserted = DB::table('permissions')->updateOrInsert(
                            ['name' => $route],
                            ['name' => $route,'guard_name'=>'web']
                        );
            $inserted?$this->info("Inserted"):$this->info("Already Inserted");
        }catch(\Exception $e){
            $this->error($e->getMessage());
        }
    }
}
