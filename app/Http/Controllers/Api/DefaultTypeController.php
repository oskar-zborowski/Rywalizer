<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Validation\Validation;
use App\Http\Responses\JsonResponse;
use App\Models\DefaultTypeName;
use Illuminate\Http\Request;

/**
 * Klasa odpowiedzialna za przetwarzanie domyślnych typów
 */
class DefaultTypeController extends Controller
{
    /**
     * #### `GET` `/api/v1/default-type-names`
     * Pobranie listy nazw domyślnych typów
     * 
     * @param Request $request
     * 
     * @return void
     */
    public function getDefaultTypeNames(Request $request): void {

        $paginationAttributes = $this->getPaginationAttributes($request);

        /** @var DefaultTypeNames $defaultTypeNames */
        $defaultTypeNames = DefaultTypeName::filter()->paginate($paginationAttributes['perPage']);

        $result = $this->preparePagination($defaultTypeNames, 'getDetailedInformation');

        JsonResponse::sendSuccess($result['data'], $result['metadata']);
    }

    /**
     * #### `GET` `/api/v1/default-types/{name}`
     * Pobranie listy domyślnych typów
     * 
     * @param string $name nazwa jednego z domyślnych typów trzymanych w bazie danych
     * @param Request $request
     * @param string $modelMethodName nazwa metody wywołanej po stronie modelu
     * 
     * @return void
     */
    public function getDefaultTypes(string $name, Request $request = null, string $modelMethodName = 'getBasicInformation'): void {

        $name = strtoupper($name);
        $defaultTypeName = Validation::getDefaultTypeName($name);

        if ($request) {

            $paginationAttributes = $this->getPaginationAttributes($request);

            /** @var \App\Models\DefaultType $defaultTypes */
            $defaultTypes = $defaultTypeName->defaultTypes()->filter()->paginate($paginationAttributes['perPage']);

            $result = $this->preparePagination($defaultTypes, 'getDetailedInformation');

            JsonResponse::sendSuccess($result['data'], $result['metadata']);
        }

        $result = null;

        /** @var \App\Models\DefaultType $defaultTypes */
        $defaultTypes = $defaultTypeName->defaultTypes()->get();

        /** @var \App\Models\DefaultType $dT */
        foreach ($defaultTypes as $dT) {
            $result[] = $dT->$modelMethodName();
        }

        $name = strtolower($name);

        JsonResponse::sendSuccess([$name => $result]);
    }

    /**
     * #### `GET` `/api/v1/providers`
     * Pobranie listy zewnętrznych serwisów uwierzytelniających
     * 
     * @return void
     */
    public function getProviders(): void {
        $this->getDefaultTypes('PROVIDER');
    }

    /**
     * #### `GET` `/api/v1/genders`
     * Pobranie listy płci
     * 
     * @return void
     */
    public function getGenders(): void {
        $this->getDefaultTypes('GENDER');
    }
}
