<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\ErrorCodes\BaseErrorCode;
use App\Http\Requests\Auth\UpdateUserRequest;
use App\Http\Responses\JsonResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Klasa odpowiedzialna za wszelkie kwestie związane z użytkownikiem
 */
class UserController extends Controller
{
    /**
     * #### `GET` `/api/v1/user`
     * Pobranie prywatnych informacji o użytkowniku
     * 
     * @return void
     */
    public function getUser(): void {
        /** @var User $user */
        $user = Auth::user();
        $user->getUser('getPrivateInformation');
    }

    /**
     * #### `PATCH` `/api/v1/user`
     * Proces uzupełnienia danych użytkownika, bądź też zaktualizowania już istniejących
     * 
     * @param UpdateUserRequest $request
     * 
     * @return void
     */
    public function updateUser(UpdateUserRequest $request): void {
        /** @var User $user */
        $user = Auth::user();
        $user->updateInformation($request);
        $user->getUser('getPrivateInformation');
    }

    /**
     * #### `POST` `/api/v1/user/email`
     * Proces utworzenia niezbędnych danych do zweryfikowania maila
     * 
     * @return void
     */
    public function sendVerificationEmail(): void {
        /** @var User $user */
        $user = Auth::user();
        $user->sendVerificationEmail();
    }

    /**
     * #### `PUT` `/api/v1/user/email`
     * Proces weryfikacji maila
     * 
     * @param Request $request
     * 
     * @return void
     */
    public function verifyEmail(Request $request): void {
        /** @var User $user */
        $user = Auth::user();
        $user->verifyEmail($request);
        $user->getUser('getPrivateInformation');
    }

    /**
     * #### `POST` `/api/v1/user/avatar`
     * Wgranie zdjęcia profilowego
     * 
     * @param Request $request
     * 
     * @return void
     */
    public function uploadAvatar(Request $request): void {

        if (!$request->avatar) {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Nie dołączono zdjęcia profilowego.'
            );
        }

        /** @var User $user */
        $user = Auth::user();
        $user->saveAvatar($request->avatar);
        $user->getUser('getPrivateInformation');
    }

    /**
     * #### `PUT` `/api/v1/user/avatar/{id}`
     * Zmiana zdjęcia profilowego
     * 
     * @param int $id id avatara
     * 
     * @return void
     */
    public function changeAvatar(int $id): void {
        /** @var User $user */
        $user = Auth::user();
        $user->changeAvatar($id);
        $user->getUser('getPrivateInformation');
    }

    /**
     * #### `DELETE` `/api/v1/user/avatar/{id}`
     * Usunięcie zdjęcia profilowego
     * 
     * @param int $id id avatara
     * 
     * @return void
     */
    public function deleteAvatar(int $id): void {
        /** @var User $user */
        $user = Auth::user();
        $user->deleteAvatar($id);
        $user->getUser('getPrivateInformation');
    }

    /**
     * #### `GET` `/api/users`
     * Pobranie szczegółowych informacji o użytkownikach
     * 
     * @param Request $request
     * 
     * @return void
     */
    public function getUsers(Request $request): void {

        $paginationAttributes = $this->getPaginationAttributes($request);

        /** @var User $users */
        $users = User::filter()->paginate($paginationAttributes['perPage']);

        $result = $this->preparePagination($users, 'detailedInformation');

        JsonResponse::sendSuccess($result['data'], $result['metadata']);
    }

    /**
     * #### `GET` `/api/user/{id}/authentication`
     * Pobranie informacji o uwierzytelnieniach użytkownika
     * 
     * @param int $id identyfikator użytkownika
     * @param Request $request
     * 
     * @return void
     */
    public function getUserAuthentication(int $id, Request $request): void {

        /** @var User $user */
        $user = Auth::user();

        if ($user->id != $id) {

            if ($user->roleType()->first()->name != 'ADMIN') {
                throw new ApiException(BaseErrorCode::PERMISSION_DENIED());
            }

            if (!$user->hasVerifiedEmail()) {
                throw new ApiException(
                    BaseErrorCode::PERMISSION_DENIED(),
                    'Email nie został zweryfikowany'
                );
            }
        }

        $paginationAttributes = $this->getPaginationAttributes($request);

        if ($user->roleType()->first()->name == 'ADMIN' && $user->hasVerifiedEmail()) {

            /** @var User $searchedUser */
            $searchedUser = User::where('id', $id)->first();
            $authentications = null;

            if ($searchedUser) {
                /** @var Authentication $authentications */
                $authentications = $searchedUser->authentication()->filter()->paginate($paginationAttributes['perPage']);
            }

            $result = $this->preparePagination($authentications, 'detailedInformation');

        } else {

            /** @var Authentication $authentications */
            $authentications = $user->authentication()->filter()->paginate($paginationAttributes['perPage']);

            $result = $this->preparePagination($authentications, 'privateInformation');
        }

        JsonResponse::sendSuccess($result['data'], $result['metadata']);
    }
}
