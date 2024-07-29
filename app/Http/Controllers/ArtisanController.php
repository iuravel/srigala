<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;

class ArtisanController extends Controller
{
    public function runCommand(Request $request)
    {
        $command = $request->input('command');

        // Add validation or authorization logic here

        $output = Artisan::output();
        Artisan::call($command);
        $output = Artisan::output();

        return response()->json(['output' => $output]);
    }

    public function runMigrateFreshSeed()
    {
        Artisan::call('migrate:fresh --seed');
        $output = Artisan::output();
        return response()->json(['output' => $output]);
        
        
        // return response()->json([
        //     'output' => Artisan::output(),
        //     'exit_code' => $exitCode,
        // ]);
    }
    public function runKeyGenerate()
    {
        Artisan::call('key:generate');
        $output = Artisan::output();
        return response()->json(['output' => $output]);
    }
    public function runConfigClear()
    {
        Artisan::call('config:clear');
        $output = Artisan::output();
        return response()->json(['output' => $output]);
    }
    public function runConfigCache()
    {
        Artisan::call('config:cache');
        $output = Artisan::output();
        return response()->json(['output' => $output]);
    }
    
    
    
    
    public function runRouteClear()
    {
        Artisan::call('route:clear');
        $output = Artisan::output();
        return response()->json(['output' => $output]);
    }
    public function runViewClear()
    {
        Artisan::call('view:clear');
        $output = Artisan::output();
        return response()->json(['output' => $output]);
    }
    public function runCacheClear()
    {
        Artisan::call('cache:clear');
        $output = Artisan::output();
        return response()->json(['output' => $output]);
    }
    public function runEventClear()
    {
        Artisan::call('event:clear');
        $output = Artisan::output();
        return response()->json(['output' => $output]);
    }
    public function runClearCompiled()
    {
        Artisan::call('clear-compiled');
        $output = Artisan::output();
        return response()->json(['output' => $output]);
    }
    public function runOptimize()
    {
        Artisan::call('optimize');
        $output = Artisan::output();
        return response()->json(['output' => $output]);
    }

    public function runStorageLink()
    {
        Artisan::call('storage:link');
        $output = Artisan::output();
        return response()->json(['output' => $output]);
    }
    public function runIconCache()
    {
        Artisan::call('icons:cache');
        $output = Artisan::output();
        return response()->json(['output' => $output]);
    }
    public function runFilamentCacheComponents()
    {
        Artisan::call('filament:cache-components');
        $output = Artisan::output();
        return response()->json(['output' => $output]);
    }
    public function runFilamentClearCachedComponents()
    {
        Artisan::call('filament:clear-cached-components');
        $output = Artisan::output();
        return response()->json(['output' => $output]);
    }
}
