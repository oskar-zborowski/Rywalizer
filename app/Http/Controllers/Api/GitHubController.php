<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * Klasa odpowiedzialna za komunikację z serwisem GitHub
 */
class GitHubController extends Controller
{
    /**
     * #### `POST` `/api/github/pull`
     * Zaciągnięcie nowych zmian ze zdalnego repozytorium
     * 
     * @return void
     */
    public function pull(): void {
        // xd
        echo shell_exec('sudo git pull https://BolleyVall7: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@github.com/BolleyVall7/Rywalizer.git master 2>&1');
    }
}
