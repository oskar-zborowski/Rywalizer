<?php

namespace App\Http\Controllers;

class GitHubController extends Controller
{
    /**
     * #### `GET` `/api/github/pull`
     * Zaciągnięcie nowych zmian ze zdalnego repozytorium
     * 
     * @return void
     */
    public function pull(): void {
        shell_exec('git pull origin master');
    }
}
