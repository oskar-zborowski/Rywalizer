<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class GitHubController extends Controller
{
    /**
     * #### `GET` `/api/github/pull`
     * Zaciągnięcie nowych zmian ze zdalnego repozytorium
     * 
     * @return void
     */
    public function pull(): void {
        $path = app_path();
        echo shell_exec($path . '/Console/GitHub/pull.sh 2>&1');
        // echo 'TEST';
        echo 'xdddddddd';
    }
}
