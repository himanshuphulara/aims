<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function startServices()
    {
        // Path to your batch file
       return $batFile = base_path('start_services.bat'); // Adjust the path as necessary

        // Execute the batch file
        exec($batFile, $output, $return_var);
        if ($return_var === 0) {
            return response()->json(['message' => 'Services started successfully', 'output' => $output]);
        } else {
            return response()->json(['message' => 'Failed to start services', 'output' => $output], 500);
        }
    }
}
