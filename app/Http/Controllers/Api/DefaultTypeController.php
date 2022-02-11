<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Validation\Validation;
use App\Http\Responses\JsonResponse;
use App\Models\Area;
use App\Models\DefaultType;
use App\Models\DefaultTypeName;
use App\Models\MinimumSkillLevel;
use App\Models\SportsPosition;
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
     */
    public function getDefaultTypes(string $name, Request $request = null, string $modelMethodName = 'getBasicInformation', $onlyReturn = false) {

        $name = strtoupper($name);
        $defaultTypeName = Validation::getDefaultTypeName($name);

        if ($request) {

            $paginationAttributes = $this->getPaginationAttributes($request);

            /** @var \App\Models\DefaultType $defaultTypes */
            $defaultTypes = $defaultTypeName->defaultTypes()->filter()->paginate($paginationAttributes['perPage']);

            $result = $this->preparePagination($defaultTypes, 'getDetailedInformation');

            if (!$onlyReturn) {
                JsonResponse::sendSuccess($result['data'], $result['metadata']);
            } else {
                return $result;
            }
        }

        $result = null;

        /** @var \App\Models\DefaultType $defaultTypes */
        $defaultTypes = $defaultTypeName->defaultTypes()->get();

        /** @var \App\Models\DefaultType $dT */
        foreach ($defaultTypes as $dT) {
            $result[] = $dT->$modelMethodName();
        }

        $name = strtolower($name);

        if (!$onlyReturn) {
            JsonResponse::sendSuccess([$name => $result]);
        } else {
            return [$name => $result];
        }
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

    /**
     * #### `GET` `/api/v1/sports`
     * Pobranie listy sportów
     * 
     * @return void
     */
    public function getSports(): void {

        $return = $this->getDefaultTypes('SPORT', null, 'getBasicInformation', true);

        foreach ($return['sport'] as &$r) {
            
            /** @var SportsPosition[] $sportsPositions */
            $sportsPositions = SportsPosition::where('sport_id', $r['id'])->get();

            /** @var SportsPosition $sP */
            foreach ($sportsPositions as $sP) {
                if ($sP->visible_at) {
                    $r['sports_positions'][] = [
                        'id' => $sP->id,
                        'name' => $sP->name,
                    ];
                }
            }

            if (sizeof($sportsPositions) == 0) {
                $r['sports_positions'] = null;
            }

            /** @var MinimumSkillLevel[] $minSkillLevels */
            $minSkillLevels = MinimumSkillLevel::where('sport_id', $r['id'])->get();

            /** @var MinimumSkillLevel $mSL */
            foreach ($minSkillLevels as $mSL) {
                if ($mSL->visible_at) {
                    $r['minimum_skill_levels'][] = [
                        'id' => $mSL->id,
                        'name' => $mSL->name,
                        'description' => $mSL->description
                    ];
                }
            }

            if (sizeof($minSkillLevels) == 0) {
                $r['minimum_skill_levels'] = null;
            }
        }

        JsonResponse::sendSuccess($return);
    }

    /**
     * #### `GET` `/api/v1/areas`
     * Pobranie listy pasujących miast, gmin, powiatów, województw
     * 
     * @param Request $request
     * 
     * @return void
     */
    public function getAreas(Request $request): void {
        $paginationAttributes = $this->getPaginationAttributes($request);

        /** @var Area $areas */
        $areas = Area::where('visible_at', '<>', NULL)->filter()->paginate($paginationAttributes['perPage']);

        $result = $this->preparePagination($areas, 'getBasicInformation');

        JsonResponse::sendSuccess($result['data'], $result['metadata']);
    }
}
