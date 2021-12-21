<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\ErrorCodes\BaseErrorCode;
use App\Http\Libraries\ImageProcessing\ImageProcessing;
use App\Http\Requests\Auth\UpdateUserRequest;
use App\Http\Responses\JsonResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * #### `POST` `/api/user/email/verification-notification`
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
     * #### `PATCH` `/api/user/email/verify`
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
        $user->checkMissingInformation();
    }

    /**
     * #### `PATCH` `/api/user`
     * Proces uzupełnienia danych użytkownika, bądź też zaktualizowania już istniejących
     * 
     * @param UpdateUserRequest $request
     * 
     * @return void
     */
    public function updateUser(UpdateUserRequest $request): void {

        /** @var User $user */
        $user = Auth::user();
        $isUpdatedEmail = $user->updateInformation($request);

        if ($isUpdatedEmail) {
            $user->sendVerificationEmail(false, true);
        }

        $user->checkMissingInformation();
    }

    /**
     * #### `GET` `/api/user`
     * Pobranie prywatnych informacji o użytkowniku
     * 
     * @return void
     */
    public function getUser(): void {
        /** @var User $user */
        $user = Auth::user();
        $user->checkMissingInformation();
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
                    'Your email address is not verified.'
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

    /**
     * #### `POST` `/api/user/avatar/upload`
     * Wgranie zdjęcia profilowego
     * 
     * @param Request $request
     * 
     * @return void
     */
    public function uploadAvatar(Request $request): void {

        /** @var User $user */
        $user = Auth::user();

        if ($request->avatar) {

            $updateInformation['avatar'] = ImageProcessing::saveAvatar($request->avatar);

            if ($user->avatar) {
                $oldAvatarPath = 'avatars/' . $user->avatar;
                Storage::delete($oldAvatarPath);
            }

            $user->update($updateInformation);
        }

        $user->checkMissingInformation();
    }

    /**
     * #### `DELETE` `/api/user/avatar/delete`
     * Usunięcie zdjęcia profilowego
     * 
     * @return void
     */
    public function deleteAvatar(): void {

        /** @var User $user */
        $user = Auth::user();

        if ($user->avatar) {
            $avatarPath = 'avatars/' . $user->avatar;
            Storage::delete($avatarPath);

            $user->update(['avatar' => null]);
        }

        $user->checkMissingInformation();
    }
}
