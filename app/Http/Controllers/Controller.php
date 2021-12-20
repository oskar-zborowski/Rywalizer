<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Zwrócenie sformatowanej tablicy z podziałem na dane właściwe oraz metadane z podstawowymi informacjami paginacji
     * 
     * @param $entity encja do zwrócenia
     * @param $method metoda należąca do encji
     * 
     * @return array
     */
    public function preparePagination($entity, $method = null): array {

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
                'currentPage' => $entity ? $entity->currentPage() : 1,
                'lastPage' => $entity ? $entity->lastPage() : 1,
                'previousPageUrl' => $entity ? $entity->previousPageUrl() : null,
                'nextPageUrl' => $entity ? $entity->nextPageUrl() : null
            ]
        ];
    }

    /**
     * Zwrócenie liczby elementów do wyświetlenia na stronie (wykorzystywane w paginacji)
     * 
     * @param Illuminate\Http\Request $request
     * 
     * @return int|null
     */
    public function getNumberOfItemsPerPage($request): ?int {

        $perPage = $request->get('per_page', env('MAX_SELECTING_RECORDS_LIMIT'));

        if ($perPage !== null) {
            $perPage = (int) $perPage;

            if (!is_int($perPage)) {
                $perPage = (int) env('MAX_SELECTING_RECORDS_LIMIT');
            }
        }

        return $perPage;
    }
}
