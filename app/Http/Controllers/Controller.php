<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

/**
 * Domyślna klasa kontrolera zawierająca wszystkie metody, które są wykorzystywanie globalnie we wszystkich kontrolerach
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Zwrócenie sformatowanej tablicy z podziałem na dane właściwe oraz metadane z podstawowymi informacjami paginacji
     * 
     * @param $entity encja do zwrócenia
     * @param string|null $method metoda należąca do encji
     * @param int|null $currentPage encja do zwrócenia
     * 
     * @return array
     */
    public function preparePagination($entity, string $method = null, int $currentPage = null): array {

        $data = null;

        if ($entity) {
            if ($method) {
                foreach ($entity->items() as $e) {
                    $data[] = $e->$method();
                }
            } else {
                $data = $entity->items();
            }
        }

        return [
            'data' => $data,
            'metadata' => [
                'count' => $entity ? $entity->count() : 0,
                'total' => $entity ? $entity->total() : 0,
                'currentPage' => $entity ? $entity->currentPage() : $currentPage,
                'lastPage' => $entity ? $entity->lastPage() : 1,
                'previousPageUrl' => $entity ? $entity->previousPageUrl() : null,
                'nextPageUrl' => $entity ? $entity->nextPageUrl() : null
            ]
        ];
    }

    /**
     * Zwrócenie liczby elementów do wyświetlenia na stronie oraz numeru bieżącej strony (wykorzystywane w paginacji)
     * 
     * @param Request $request
     * 
     * @return array
     */
    public function getPaginationAttributes(Request $request): array {

        $perPage = (int) $request->get('per_page', env('MAX_SELECTING_RECORDS_LIMIT'));
        $currentPage = (int) $request->get('page', 1);

        if (!is_int($perPage)) {
            $perPage = (int) env('MAX_SELECTING_RECORDS_LIMIT');
        }

        if (!is_int($currentPage)) {
            $currentPage = 1;
        }

        return [
            'perPage' => $perPage,
            'currentPage' => $currentPage
        ];
    }
}
