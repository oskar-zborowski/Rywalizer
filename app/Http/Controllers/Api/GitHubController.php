<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class GitHubController extends Controller
{
    /**
     * #### `POST` `/api/github/pull`
     * Zaciągnięcie nowych zmian ze zdalnego repozytorium
     * 
     * @return void
     */
    public function pull(): void {
        echo shell_exec('git pull https://BolleyVall7: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@github.com/BolleyVall7/Rywalizer.git master 2>&1');
    }
}
