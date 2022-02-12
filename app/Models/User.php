<?php

namespace App\Models;

use App\Exceptions\ApiException;
use App\Http\ErrorCodes\AuthErrorCode;
use App\Http\ErrorCodes\BaseErrorCode;
use App\Http\Libraries\Encrypter\Encrypter;
use App\Http\Libraries\FileProcessing\FileProcessing;
use App\Http\Libraries\Validation\Validation;
use App\Http\Responses\JsonResponse;
use App\Http\Traits\Encryptable;
use App\Mail\AccountRestoration as MailAccountRestoration;
use App\Mail\PasswordReset as MailPasswordReset;
use App\Mail\EmailVerification as MailEmailVerification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Mehradsadeghi\FilterQueryString\FilterQueryString;

class User extends Authenticatable implements MustVerifyEmail
{
    use Encryptable, FilterQueryString, HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'telephone',
        'password',
        'birth_date',
        'gender_id',
        'city_id',
        'address_coordinates',
        'facebook_profile',
        'instagram_profile',
        'website'
    ];

    protected $guarded = [
        'id',
        'role_id',
        'email_verified_at',
        'telephone_verified_at',
        'last_time_name_changed',
        'last_time_password_changed',
        'verified_at',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'first_name',
        'last_name',
        'email',
        'telephone',
        'password',
        'birth_date',
        'gender_id',
        'role_id',
        'city_id',
        'address_coordinates',
        'facebook_profile',
        'instagram_profile',
        'website',
        'email_verified_at',
        'telephone_verified_at',
        'last_time_name_changed',
        'last_time_password_changed',
        'verified_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'email_verified_at' => 'string',
        'telephone_verified_at' => 'string',
        'last_time_name_changed' => 'string',
        'last_time_password_changed' => 'string',
        'verified_at' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    protected $encryptable = [
        'first_name' => 30,
        'last_name' => 30,
        'email' => 254,
        'telephone' => 24,
        'birth_date' => 10,
        'address_coordinates' => 21,
        'facebook_profile' => 255,
        'instagram_profile' => 255,
        'website' => 255
    ];

    protected $with = [
        'gender'
    ];

    public function gender() {
        return $this->belongsTo(DefaultType::class, 'gender_id', 'id');
    }

    public function role() {
        return $this->belongsTo(DefaultType::class, 'role_id', 'id');
    }

    public function city() {
        return $this->belongsTo(Area::class, 'city_id', 'id');
    }

    public function images() {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function imageAssignments() {
        return $this->morphMany(ImageAssignment::class, 'imageable');
    }

    public function actionables() {
        return $this->morphMany(AccountAction::class, 'actionable');
    }

    public function operationable() {
        return $this->morphMany(AccountOperation::class, 'operationable');
    }

    public function evaluatorRating() {
        return $this->morphMany(Rating::class, 'evaluator');
    }

    public function evaluatorRatingUsefulness() {
        return $this->morphMany(RatingUsefulness::class, 'evaluator');
    }

    public function tokenable() {
        return $this->morphMany(PersonalAccessToken::class, 'tokenable');
    }

    public function reportable() {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function defaultTypeNamesByCreator() {
        return $this->hasMany(DefaultTypeName::class, 'creator_id');
    }

    public function defaultTypeNamesByEditor() {
        return $this->hasMany(DefaultTypeName::class, 'editor_id');
    }

    public function friendsByRequestingUser() {
        return $this->hasMany(Friend::class, 'requesting_user_id');
    }

    public function friendsByRespondingUser() {
        return $this->hasMany(Friend::class, 'responding_user_id');
    }

    public function iconsByCreator() {
        return $this->hasMany(Icon::class, 'creator_id');
    }

    public function iconsByEditor() {
        return $this->hasMany(Icon::class, 'editor_id');
    }

    public function imagesByCreator() {
        return $this->hasMany(Image::class, 'creator_id');
    }

    public function imagesBySupervisor() {
        return $this->hasMany(Image::class, 'supervisor_id');
    }

    public function userSetting() {
        return $this->hasOne(UserSetting::class);
    }

    public function defaultTypesByCreator() {
        return $this->hasMany(DefaultType::class, 'creator_id');
    }

    public function defaultTypesByEditor() {
        return $this->hasMany(DefaultType::class, 'editor_id');
    }

    public function imagesAssignmentByCreator() {
        return $this->hasMany(ImageAssignment::class, 'creator_id');
    }

    public function imagesAssignmentByEditor() {
        return $this->hasMany(ImageAssignment::class, 'editor_id');
    }

    public function accountsActionsByCreator() {
        return $this->hasMany(AccountAction::class, 'creator_id');
    }

    public function accountsActionsByEditor() {
        return $this->hasMany(AccountAction::class, 'editor_id');
    }

    public function accountsOperationsByCreator() {
        return $this->hasMany(AccountOperation::class, 'creator_id');
    }

    public function accountsOperationsByEditor() {
        return $this->hasMany(AccountOperation::class, 'editor_id');
    }

    public function areasByCreator() {
        return $this->hasMany(Area::class, 'creator_id');
    }

    public function areasByEditor() {
        return $this->hasMany(Area::class, 'editor_id');
    }

    public function areasBySupervisor() {
        return $this->hasMany(Area::class, 'supervisor_id');
    }

    public function authentications() {
        return $this->hasMany(Authentication::class);
    }

    public function externalAuthentications() {
        return $this->hasMany(ExternalAuthentication::class);
    }

    public function rolePermissionsByCreator() {
        return $this->hasMany(RolePermission::class, 'creator_id');
    }

    public function commissionsByCreator() {
        return $this->hasMany(Commission::class, 'creator_id');
    }

    public function commissionsByEditor() {
        return $this->hasMany(Commission::class, 'editor_id');
    }

    public function partners() {
        return $this->hasMany(Partner::class);
    }

    public function partnersByCreator() {
        return $this->hasMany(Partner::class, 'creator_id');
    }

    public function partnersByEditor() {
        return $this->hasMany(Partner::class, 'editor_id');
    }

    public function partnersSettingsByCreator() {
        return $this->hasMany(PartnerSetting::class, 'creator_id');
    }

    public function partnersSettingsByEditor() {
        return $this->hasMany(PartnerSetting::class, 'editor_id');
    }

    public function discountCodesByCreator() {
        return $this->hasMany(DiscountCode::class, 'creator_id');
    }

    public function discountCodesByEditor() {
        return $this->hasMany(DiscountCode::class, 'editor_id');
    }

    public function discountsByCreator() {
        return $this->hasMany(Discount::class, 'creator_id');
    }

    public function facilitiesByCreator() {
        return $this->hasMany(Facility::class, 'creator_id');
    }

    public function facilitiesByEditor() {
        return $this->hasMany(Facility::class, 'editor_id');
    }

    public function facilitiesBySupervisor() {
        return $this->hasMany(Facility::class, 'supervisor_id');
    }

    public function facilitiesAvailableSportsByCreator() {
        return $this->hasMany(FacilityAvailableSport::class, 'creator_id');
    }

    public function facilitiesAvailableSportsByEditor() {
        return $this->hasMany(FacilityAvailableSport::class, 'editor_id');
    }

    public function facilitiesAvailableSportsBySupervisor() {
        return $this->hasMany(FacilityAvailableSport::class, 'supervisor_id');
    }

    public function facilitiesEquipmentByCreator() {
        return $this->hasMany(FacilityEquipment::class, 'creator_id');
    }

    public function facilitiesEquipmentByEditor() {
        return $this->hasMany(FacilityEquipment::class, 'editor_id');
    }

    public function facilitiesEquipmentBySupervisor() {
        return $this->hasMany(FacilityEquipment::class, 'supervisor_id');
    }

    public function facilitiesOpeningHoursByCreator() {
        return $this->hasMany(FacilityOpeningHour::class, 'creator_id');
    }

    public function facilitiesOpeningHoursByEditor() {
        return $this->hasMany(FacilityOpeningHour::class, 'editor_id');
    }

    public function facilitiesOpeningHoursBySupervisor() {
        return $this->hasMany(FacilityOpeningHour::class, 'supervisor_id');
    }

    public function facilitiesPlacesByCreator() {
        return $this->hasMany(FacilityPlace::class, 'creator_id');
    }

    public function facilitiesPlacesByEditor() {
        return $this->hasMany(FacilityPlace::class, 'editor_id');
    }

    public function facilitiesSpecialOpeningHoursByCreator() {
        return $this->hasMany(FacilitySpecialOpeningHour::class, 'creator_id');
    }

    public function facilitiesSpecialOpeningHoursByEditor() {
        return $this->hasMany(FacilitySpecialOpeningHour::class, 'editor_id');
    }

    public function facilitiesSpecialOpeningHoursBySupervisor() {
        return $this->hasMany(FacilitySpecialOpeningHour::class, 'supervisor_id');
    }

    public function facilitiesPlacesBookings() {
        return $this->hasMany(FacilityPlaceBooking::class);
    }

    public function minimumSkillLevelsByCreator() {
        return $this->hasMany(MinimumSkillLevel::class, 'creator_id');
    }

    public function minimumSkillLevelsByEditor() {
        return $this->hasMany(MinimumSkillLevel::class, 'editor_id');
    }

    public function minimumSkillLevelsBySupervisor() {
        return $this->hasMany(MinimumSkillLevel::class, 'supervisor_id');
    }

    public function sportsPositionsByCreator() {
        return $this->hasMany(SportsPosition::class, 'creator_id');
    }

    public function sportsPositionsByEditor() {
        return $this->hasMany(SportsPosition::class, 'editor_id');
    }

    public function sportsPositionsBySupervisor() {
        return $this->hasMany(SportsPosition::class, 'supervisor_id');
    }

    public function announcementsByCreator() {
        return $this->hasMany(Announcement::class, 'creator_id');
    }

    public function announcementsByEditor() {
        return $this->hasMany(Announcement::class, 'editor_id');
    }

    public function announcementsPaymentsByCreator() {
        return $this->hasMany(AnnouncementPayment::class, 'creator_id');
    }

    public function announcementsPaymentsByEditor() {
        return $this->hasMany(AnnouncementPayment::class, 'editor_id');
    }

    public function announcementsSeatsByCreator() {
        return $this->hasMany(AnnouncementSeat::class, 'creator_id');
    }

    public function announcementsSeatsByEditor() {
        return $this->hasMany(AnnouncementSeat::class, 'editor_id');
    }

    public function announcementsParticipants() {
        return $this->hasMany(AnnouncementParticipant::class);
    }

    public function agreementsByCreator() {
        return $this->hasMany(Agreement::class, 'creator_id');
    }

    public function agreementsByEditor() {
        return $this->hasMany(Agreement::class, 'editor_id');
    }

    public function userAgreements() {
        return $this->hasMany(UserAgreement::class);
    }

    public function reports() {
        return $this->hasMany(Report::class);
    }

    public function reportsBySupervisor() {
        return $this->hasMany(Report::class, 'supervisor_id');
    }

    /**
     * Zwrócenie informacji o płci użytkownika
     * 
     * @return array|null
     */
    public function getGender(): ?array {

        /** @var DefaultType $gender */
        $gender = $this->gender()->first();

        if ($gender) {
            /** @var Icon $icon */
            $icon = $gender->icon()->first();
        }

        $result = [
            'id' => $gender ? (int) $gender->id : null,
            'name' => $gender ? $gender->name : null,
            'description_simple' => $gender ? $gender->description_simple : null,
            'icon' => isset($icon) && $icon ? $icon->filename : null
        ];

        return $gender ? $result : null;
    }

    /**
     * Zwrócenie informacji o roli użytkownika
     * 
     * @return string
     */
    public function getRole(): string {

        /** @var DefaultType $role */
        $role = $this->role()->first();

        return $role->name;
    }

    /**
     * Zwrócenie informacji o mieście użytkownika
     * 
     * @return array|null
     */
    public function getCity(): ?array {

        /** @var DefaultType $city */
        $city = $this->city()->first();

        $result = [
            'name' => $city ? $city->name : null,
            'boundary' => $city ? $city->boundary : null
        ];

        return $city ? $result : null;
    }

    /**
     * Zwrócenie listy uprawnień
     * 
     * @return array|null
     */
    public function getPermissions(): ?array {

        /** @var DefaultType $role */
        $role = $this->role()->first();

        /** @var RolePermission $rolePermissions */
        $rolePermissions = $role->rolePermissionsByRole()->get();

        $result = null;

        /** @var RolePermission $rP */
        foreach ($rolePermissions as $rP) {

            /** @var DefaultType $permission */
            $permission = $rP->permission()->first();

            /** @var DefaultTypeName $defaultTypeName */
            $defaultTypeName = $permission->defaultTypeName()->first();

            if ($permission->is_active && $defaultTypeName->name == 'CLIENT_PERMISSION') {
                $result[] = $permission->name;
            }
        }

        return $result;
    }

    /**
     * Zwrócenie listy lub pojedynczego zdjęcia profilowego
     * 
     * @param bool $all flaga z informacją czy mają zostać zwrócone wszystkie zdjęcia profilowe użytkownika
     * 
     * @return array|null
     */
    public function getAvatars(bool $all = false): ?array {

        $defaultType = Validation::getDefaultType('AVATAR', 'IMAGE_TYPE');

        $result = null;

        if ($all) {

            /** @var ImageAssignment $avatars */
            $avatars = $this->imageAssignments()->where('image_type_id', $defaultType->id)->orderBy('number', 'desc')->get();

            /** @var ImageAssignment $a */
            foreach ($avatars as $a) {

                /** @var Image $image */
                $image = $a->image()->first();

                $result[] = [
                    'id' => $a->id,
                    'filename' => '/storage/user-pictures/' . $image->filename
                ];
            }

        } else {

            /** @var ImageAssignment $avatar */
            $avatar = $this->imageAssignments()->where('image_type_id', $defaultType->id)->orderBy('number', 'desc')->first();

            if ($avatar) {
                /** @var Image $image */
                $image = $avatar->image()->first();

                $result[] = [
                    'id' => $avatar->id,
                    'filename' => '/storage/user-pictures/' . $image->filename
                ];
            }
        }

        return $result;
    }

    /**
     * Zwrócenie informacji czy użytkownik może zmienić imię i nazwisko
     * 
     * @return bool
     */
    public function canChangeName(): bool {
        if ($this->last_time_name_changed_at) {
            $canChangeName = Validation::timeComparison($this->last_time_name_changed_at, env('PAUSE_BEFORE_CHANGING_NAME'), '>');
        } else {
            $canChangeName = true;
        }
        return $canChangeName;
    }

    /**
     * Zwrócenie podstawowych informacji o użytkowniku
     * 
     * @return array
     */
    public function getBasicInformation(): array {
        return [
            'user' => [
                'id' => $this->id,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'avatars' => $this->getAvatars(),
                'gender' => $this->getGender()
            ]
        ];
    }

    /**
     * Zwrócenie prywatnych informacji o użytkowniku
     * 
     * @return array
     */
    public function getPrivateInformation(): array {

        $addressCoordinates = $this->address_coordinates;

        if ($addressCoordinates) {
            $addressCoordinates = explode(';', $addressCoordinates);
        }

        return [
            'user' => [
                'id' => $this->id,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'avatars' => $this->getAvatars(true),
                'email' => $this->email,
                'telephone' => $this->telephone,
                'birth_date' => $this->birth_date,
                'gender' => $this->getGender(),
                'role' => $this->getRole(),
                'city' => $this->getCity(),
                'address_coordinates' => [
                    'lat' => $addressCoordinates ? (float) $addressCoordinates[0] : null,
                    'lng' => $addressCoordinates ? (float) $addressCoordinates[1] : null
                ],
                'facebook_profile' => $this->facebook_profile,
                'instagram_profile' => $this->instagram_profile,
                'website' => $this->website,
                'is_verified' => (bool) $this->verified_at,
                'can_change_name' => $this->canChangeName(),
                'permissions' => $this->getPermissions()
            ],
            'user_setting' => [
                'is_visible_in_comments' => (bool) $this->userSetting()->first()->is_visible_in_comments
            ]
        ];
    }

    /**
     * Utworzenie rekordu do resetu hasła i wysłanie maila z tokenem
     * 
     * @return void
     */
    public function forgotPassword(): void {
        $this->prepareEmail('PASSWORD_RESET', 'reset-hasla', MailPasswordReset::class);
        JsonResponse::sendSuccess();
    }

    /**
     * Zresetowanie hasła użytkownika
     * 
     * @param Request $request
     * @param AccountOperation $accountOperation
     * 
     * @return void
     */
    public function resetPassword(Request $request, AccountOperation $accountOperation): void {

        if (Validation::timeComparison($accountOperation->updated_at, env('EMAIL_TOKEN_LIFETIME'), '>')) {
            throw new ApiException(AuthErrorCode::PASSWORD_RESET_TOKEN_HAS_EXPIRED());
        }

        $accountOperation->delete();

        $this->update([
            'password' => $request->password,
            'last_time_password_changed' => now()
        ]);

        if (!$request->do_not_logout) {
            $this->tokenable()->delete();
        }

        JsonResponse::sendSuccess();
    }

    /**
     * Utworzenie rekordu do weryfikacji maila oraz wysłanie maila z tokenem
     * 
     * @param bool $afterRegistartion flaga z informacją czy wywołanie metody jest pochodną procesu rejestracji nowego użytkownika
     * @param bool $ignorePause flaga określająca czy ma być sprawdzany czas ostatniego wysłania maila
     * 
     * @return void
     */
    public function sendVerificationEmail(bool $afterRegistartion = false, bool $ignorePause = false): void {

        if (!$this->email) {
            throw new ApiException(AuthErrorCode::EMPTY_EMAIL());
        }

        $accountOperationType = Validation::getAccountOperationType('EMAIL_VERIFICATION');

        $emailSendingCounter = 1;

        if (!$afterRegistartion) {

            if ($this->hasVerifiedEmail()) {
                throw new ApiException(AuthErrorCode::EMAIL_ALREADY_VERIFIED());
            }

            /** @var AccountOperation $emailVerification */
            $emailVerification = $accountOperationType->accountsOperations()->first();

            if ($emailVerification) {
                $emailSendingCounter += $emailVerification->countMailing($ignorePause);
            }
        }

        $encrypter = new Encrypter;
        $token = $encrypter->generateToken(64, AccountOperation::class);

        $this->operationable()->updateOrCreate([],
        [
            'account_operation_type_id' => $accountOperationType->id,
            'token' => $token,
            'email_sending_counter' => $emailSendingCounter
        ]);

        $url = env('APP_URL') . '/potwierdzenie-maila?token=' . $token; // TODO Poprawić na prawidłowy URL
        Mail::to($this)->send(new MailEmailVerification($url, $afterRegistartion));

        if (!$afterRegistartion) {
            JsonResponse::sendSuccess();
        }
    }

    /**
     * Zweryfikowanie adresu email użytkownika
     * 
     * @param Request $request
     * 
     * @return void
     */
    public function verifyEmail(Request $request): void {

        if ($this->hasVerifiedEmail()) {
            throw new ApiException(AuthErrorCode::EMAIL_ALREADY_VERIFIED());
        }

        $accountOperationType = Validation::getAccountOperationType('EMAIL_VERIFICATION');

        if (!$accountOperationType) {
            throw new ApiException(
                BaseErrorCode::INTERNAL_SERVER_ERROR(),
                'Invalid account operation type.'
            );
        }

        /** @var AccountOperation $emailVerification */
        $emailVerification = $accountOperationType->accountsOperations()->where('token', $request->token)->first();

        if (!$emailVerification) {
            throw new ApiException(AuthErrorCode::INVALID_EMAIL_VERIFIFICATION_TOKEN());
        }

        if (Validation::timeComparison($emailVerification->updated_at, env('EMAIL_TOKEN_LIFETIME'), '>')) {
            throw new ApiException(AuthErrorCode::EMAIL_VERIFIFICATION_TOKEN_HAS_EXPIRED());
        }

        $emailVerification->delete();

        $this->timestamps = false;
        $this->markEmailAsVerified();
    }

    /**
     * Przywrócenie usuniętego konta
     * 
     * @param AccountOperation $accountOperation
     * 
     * @return void
     */
    public function restoreAccount(AccountOperation $accountOperation): void {

        if (Validation::timeComparison($accountOperation->updated_at, env('EMAIL_TOKEN_LIFETIME'), '>')) {
            throw new ApiException(AuthErrorCode::RESTORE_ACCOUNT_TOKEN_HAS_EXPIRED());
        }

        $accountActionType = Validation::getAccountActionType('ACCOUNT_DELETION');

        if (!$accountActionType) {
            throw new ApiException(
                BaseErrorCode::INTERNAL_SERVER_ERROR(),
                'Invalid account operation type.'
            );
        }

        /** @var AccountAction $accountDeleted */
        $accountDeleted = $accountActionType->accountsActions()->where('actionable_type', 'App\Models\User')->where('actionable_id', $this->id)->first();

        $accountOperation->delete();
        $accountDeleted->delete();

        JsonResponse::sendSuccess();
    }

    /**
     * Zaktualizowanie informacji o użytkowniku
     * 
     * @param Request $request
     * 
     * @return void
     */
    public function updateInformation(Request $request): void {

        $encrypter = new Encrypter;

        if ($request->email) {
            $email = $encrypter->decrypt($request->email);
            $request->merge(['email' => $email]);
        }

        if ($request->telephone) {
            $telephone = $encrypter->decrypt($request->telephone);
            $request->merge(['telephone' => $telephone]);
        }

        $updatedInformation = null;

        $isFirstName = $request->first_name && $request->first_name != $this->first_name;
        $isLastName = $request->last_name && $request->last_name != $this->last_name;
        $isEmail = $request->email && $request->email != $this->email;
        $isBirthDate = $request->birth_date && $request->birth_date != $this->birth_date;

        if ($isFirstName || $isLastName) {

            if ($this->last_time_name_changed &&
                Validation::timeComparison($this->last_time_name_changed, env('PAUSE_BEFORE_CHANGING_NAME'), '<='))
            {
                throw new ApiException(
                    AuthErrorCode::WAIT_BEFORE_CHANGING_NAME()
                );
            }

            if ($isFirstName) {
                $updatedInformation['first_name'] = $request->first_name;
            }

            if ($isLastName) {
                $updatedInformation['last_name'] = $request->last_name;
            }

            $updatedInformation['last_time_name_changed'] = now();
        }

        if ($isEmail) {
            $updatedInformation['email'] = $request->email;
            $updatedInformation['email_verified_at'] = null;
        }

        if ($request->password) {
            $updatedInformation['password'] = $request->password;
            $updatedInformation['last_time_password_changed'] = now();
        }

        if ($isBirthDate) {
            $updatedInformation['birth_date'] = $request->birth_date;
        }

        if ($request->address_coordinates != $this->address_coordinates) {

            if ($request->address_coordinates) {

                $addressCoordinates = explode(';', $request->address_coordinates);

                if (count($addressCoordinates) != 2) {
                    throw new ApiException(
                        BaseErrorCode::FAILED_VALIDATION(),
                        ['address_coordinates' => [__('validation.regex')]]
                    );
                }

                $latitudeLength = strlen($addressCoordinates[0]);
                $longitudeLength = strlen($addressCoordinates[1]);

                if ($latitudeLength != 10 ||
                    $longitudeLength != 10 ||
                    $addressCoordinates[0][2] != '.' ||
                    $addressCoordinates[1][2] != '.')
                {
                    throw new ApiException(
                        BaseErrorCode::FAILED_VALIDATION(),
                        ['address_coordinates' => [__('validation.regex')]]
                    );
                }

                for ($i=0; $i<$latitudeLength; $i++) {
                    if ((!is_numeric($addressCoordinates[0][$i]) ||
                        !is_numeric($addressCoordinates[1][$i])) &&
                        $i != 2)
                    {
                        throw new ApiException(
                            BaseErrorCode::FAILED_VALIDATION(),
                            ['address_coordinates' => [__('validation.regex')]]
                        );
                    }
                }
            }

            $updatedInformation['address_coordinates'] = $request->address_coordinates;
        }

        if ($request->telephone != $this->telephone) {

            if ($request->telephone) {

                $telephoneLength = strlen($request->telephone);

                for ($i=0; $i<$telephoneLength; $i++) {
                    if (!is_numeric($request->telephone[$i])) {
                        throw new ApiException(
                            BaseErrorCode::FAILED_VALIDATION(),
                            ['telephone' => [__('validation.regex')]]
                        );
                    }
                }
            }

            $updatedInformation['telephone'] = $request->telephone;
        }

        if ($request->facebook_profile != $this->facebook_profile) {
            $updatedInformation['facebook_profile'] = $request->facebook_profile;
        }

        if ($request->instagram_profile != $this->instagram_profile) {
            $updatedInformation['instagram_profile'] = $request->instagram_profile;
        }

        if ($request->website != $this->website) {
            $updatedInformation['website'] = $request->website;
        }

        if ($request->gender_id != $this->gender_id) {
            $updatedInformation['gender_id'] = $request->gender_id;
        }

        if ($request->city_id || $request->city_name) {
            $city = Validation::createArea($request);
            $updatedInformation['city_id'] = $city->id;
        }

        if ($updatedInformation) {
            $this->update($updatedInformation);
        }

        $this->refresh();

        if (isset($updatedInformation['email'])) {
            $this->sendVerificationEmail(false, true);
        }
    }

    /**
     * Zapisanie zdjęcia profilowego użytkownika
     * 
     * @param string $avatarPath aktualna ścieżka do zdjęcia profilowego
     * 
     * @return void
     */
    public function saveAvatar(string $avatarPath): void {

        $imageType = Validation::getDefaultType('AVATAR', 'IMAGE_TYPE');

        $oldAvatars = $this->imageAssignments()->where('image_type_id', $imageType->id)->orderBy('number', 'desc')->get();

        $counter = 0;

        foreach ($oldAvatars as $oA) {
            $counter++;
        }

        $newNumber = $counter + 1;

        foreach ($oldAvatars as $oA) {
            $oA->number = $counter;
            $oA->save();
            $counter--;
        }

        $image = FileProcessing::saveAvatar($avatarPath, true);

        $imageAssignment = new ImageAssignment;
        $imageAssignment->imageable_type = 'App\Models\User';
        $imageAssignment->imageable_id = $this->id;
        $imageAssignment->image_type_id = $imageType->id;
        $imageAssignment->image_id = $image->id;
        $imageAssignment->number = $newNumber;
        $imageAssignment->creator_id = $this->id;
        $imageAssignment->editor_id = $this->id;
        $imageAssignment->save();
    }

    /**
     * Zmiana zdjęcia profilowego użytkownika
     * 
     * @param int $avatarId id zdjęcia profilowego, które ma być teraz aktualnym
     * 
     * @return void
     */
    public function changeAvatar(int $avatarId): void {

        $imageType = Validation::getDefaultType('AVATAR', 'IMAGE_TYPE');

        /** @var ImageAssignment $oldAvatars */
        $oldAvatars = $this->imageAssignments()->where('image_type_id', $imageType->id)->orderBy('number', 'desc')->get();

        $counter = 0;

        foreach ($oldAvatars as $oA) {
            $counter++;
        }

        $newNumber = $counter;

        /** @var ImageAssignment $oldAvatars */
        $currentAvatar = $oldAvatars->where('id', $avatarId)->first();

        if ($currentAvatar) {
            $currentAvatar->number = $newNumber;
            $currentAvatar->save();
    
            foreach ($oldAvatars as $oA) {
                if ($oA->id != $currentAvatar->id) {
                    $counter--;
                    $oA->number = $counter;
                    $oA->save();
                }
            }
        } else {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Podano nieprawidłowy identyfikator avatara'
            );
        }
    }

    /**
     * Usunięcie zdjęcia profilowego użytkownika
     * 
     * @return void
     */
    public function deleteAvatar(int $avatarId): void {

        $imageType = Validation::getDefaultType('AVATAR', 'IMAGE_TYPE');

        /** @var ImageAssignment $avatar */
        $avatar = $this->imageAssignments()->where('image_type_id', $imageType->id)->where('id', $avatarId)->first();

        if ($avatar) {
            Storage::delete('user-pictures/' . $avatar->image()->first()->filename);
            $avatar->image()->first()->delete();
        } else {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Podano nieprawidłowy identyfikator avatara'
            );
        }
    }

    /**
     * Zweryfikowanie urządzenia i stworzenie odpowiednich logów
     * 
     * @param Request $request
     * @param string $activity nazwa aktywności, która wywołała daną metodę np. LOGIN_FORM
     * 
     * @return void
     */
    public function checkDevice(Request $request = null, string $activity): void {

        /** @var DefaultType $authenticationType */
        $authenticationType = Validation::getDefaultType($activity, 'AUTHENTICATION_TYPE');

        if ($request === null) {

            $encrypter = new Encrypter;
            $tempUuid = $encrypter->generateToken(64, Device::class, 'uuid');

            $device = new Device;
            $device->uuid = $tempUuid;
            $device->save();
            $deviceId = $device->id;

            JsonResponse::setCookie($tempUuid, 'TEMP_UUID');

        } else {
            $deviceId = $request->device_id;
        }

        $authentication = new Authentication;
        $authentication->user_id = $this->id;
        $authentication->authentication_type_id = $authenticationType->id;
        $authentication->device_id = $deviceId;
        $authentication->save();
    }

    /**
     * Sprawdzenie czy użytkownik może korzystać z serwisu
     * 
     * @param Request $request
     * 
     * @return void
     */
    public function checkAccess(Request $request = null): void {

        $accountBlocked = null;
        $accountDeleted = null;

        /** @var AccountAction $accountActions */
        $accountActions = $this->actionables()->get();

        /** @var AccountAction $aA */
        foreach ($accountActions as $aA) {

            /** @var AccountActionType $accountActionType */
            $accountActionType = $aA->accountActionType()->first();

            /** @var DefaultType $defaultType */
            $defaultType = $accountActionType->accountActionType()->first();

            if (strpos($defaultType->name, 'ACCOUNT_BLOCKED') !== false) {
                $accountBlocked = $aA;
            } else if (strpos($defaultType->name, 'ACCOUNT_DELETION') !== false) {
                $accountDeleted = $aA;
            }
        }

        if ($accountBlocked || $accountDeleted) {

            if ($request === null) {
                JsonResponse::deleteCookie('JWT');
                JsonResponse::deleteCookie('REFRESH-TOKEN');
            } else {

                if ($request->cookie(env('JWT_COOKIE_NAME'))) {
                    JsonResponse::deleteCookie('JWT');
                }

                if ($request->cookie(env('REFRESH_TOKEN_COOKIE_NAME'))) {
                    JsonResponse::deleteCookie('REFRESH_TOKEN');
                }
            }

            $this->tokenable()->delete();

            if ($accountBlocked) {

                /** @var AccountActionType $accountActionType */
                $accountActionType = $accountBlocked->accountActionType()->first();

                /** @var DefaultType $defaultType */
                $defaultType = $accountActionType->accountActionType()->first();

                throw new ApiException(
                    AuthErrorCode::ACOUNT_BLOCKED(),
                    [
                        $defaultType->description_perfect,
                        'Data zniesienia blokady: ' . $accountBlocked->expires_at
                    ]
                );
            }

            if ($accountDeleted) {

                /** @var AccountActionType $accountActionType */
                $accountActionType = $accountDeleted->accountActionType()->first();

                /** @var DefaultType $defaultType */
                $defaultType = $accountActionType->accountActionType()->first();

                $this->prepareEmail('ACCOUNT_RESTORATION', 'v1/account/restore', MailAccountRestoration::class);

                throw new ApiException(
                    AuthErrorCode::ACOUNT_DELETED(),
                    [
                        $defaultType->description_perfect,
                        'Wysłaliśmy na Twojego maila link do przywrócenia konta'
                    ]
                );
            }
        }
    }

    /**
     * Utworzenie tokenów uwierzytelniających
     * 
     * @return void
     */
    public function createTokens(): void {

        $encrypter = new Encrypter;
        $refreshToken = $encrypter->generateToken(64, PersonalAccessToken::class, 'refresh_token');
        $encryptedRefreshToken = $encrypter->encrypt($refreshToken);

        $jwt = $this->createToken('JWT');
        $jwtToken = $jwt->plainTextToken;
        $jwtId = $jwt->accessToken->getKey();

        $personalAccessToken = $this->tokenable()->where('id', $jwtId)->first();
        $personalAccessToken->refresh_token = $encryptedRefreshToken;
        $personalAccessToken->save();

        JsonResponse::setCookie($jwtToken, 'JWT');
        JsonResponse::setCookie($refreshToken, 'REFRESH-TOKEN');
    }

    /**
     * Sprawdzenie brakujących informacji o użytkowniku i zwrócenie jego encji
     * 
     * @param string $modelMethodName nazwa metody, która ma zostać dołączona jako wykaz zwróconych pól użytkownika, np. getPrivateInformation
     * 
     * @return void
     */
    public function getUser($modelMethodName): void {

        $missingInformation = null;

        if (!$this->email) {
            $missingInformation['required']['email'] = [__('validation.custom.is-missing', ['attribute' => 'adres email'])];
        }

        if (!$this->birth_date) {
            $missingInformation['required']['birth_date'] = [__('validation.custom.is-missing', ['attribute' => 'datę urodzenia'])];
        }

        if (!$this->getAvatars()) {
            $missingInformation['optional']['avatar'] = [__('validation.custom.is-missing', ['attribute' => 'zdjęcie profilowe'])];
        }

        if (!$this->telephone) {
            $missingInformation['optional']['telephone'] = [__('validation.custom.is-missing', ['attribute' => 'numer telefonu'])];
        }

        if (!$this->gender_id) {
            $missingInformation['optional']['gender_id'] = [__('validation.custom.is-missing', ['attribute' => 'płeć'])];
        }

        if (!$this->city_id) {
            $missingInformation['optional']['city_id'] = [__('validation.custom.is-missing', ['attribute' => 'miasto'])];
        }

        if (!$this->address_coordinates) {
            $missingInformation['optional']['address_coordinates'] = [__('validation.custom.is-missing', ['attribute' => 'lokalizację'])];
        }

        if (!$this->facebook_profile) {
            $missingInformation['optional']['facebook_profile'] = [__('validation.custom.is-missing', ['attribute' => 'adres profilu na Facebooku'])];
        }

        if (!$this->instagram_profile) {
            $missingInformation['optional']['instagram_profile'] = [__('validation.custom.is-missing', ['attribute' => 'adres profilu na Instagramie'])];
        }

        if (!$this->website) {
            $missingInformation['optional']['website'] = [__('validation.custom.is-missing', ['attribute' => 'adres strony internetowej'])];
        }

        if (isset($missingInformation['required']) || !$this->email_verified_at) {
            throw new ApiException(
                $this->email_verified_at ? AuthErrorCode::MISSING_USER_INFORMATION() : AuthErrorCode::UNVERIFIED_EMAIL(),
                $this->$modelMethodName(),
                ['missing_user_information' => $missingInformation]
            );
        }

        JsonResponse::sendSuccess(
            $this->$modelMethodName(),
            ['missing_user_information' => $missingInformation]
        );
    }

    /**
     * Zapisanie zaakceptowanych regulaminów przez użytkownika
     * 
     * @param Request $request
     * 
     * @return void
     */
    public function saveAcceptedAgreements(Request $request): void {

        $acceptedAgreements = $request->accepted_agreements;

        foreach ($acceptedAgreements as $aA) {
            $this->userAgreements()->create(['agreement_id' => $aA]);
        }
    }

    /**
     * Utworzenie niezbędnych danych do wysłania maila i wysłanie go
     * 
     * @param string $accountOperation typ przeprowadzanej operacji, np. PASSWORD_RESET
     * @param string $urlEndpoint końcowa nazwa endpointu, dla którego zostanie wygenerowany token np. account/password
     * @param string $mail klasa maila, która ma zostać wywołany
     * 
     * @return void
     */
    public function prepareEmail(string $accountOperation, string $urlEndpoint, $mail) {

        $accountOperationType = Validation::getAccountOperationType($accountOperation);

        if (!$accountOperationType) {
            throw new ApiException(
                BaseErrorCode::INTERNAL_SERVER_ERROR(),
                'Invalid account operation type.'
            );
        }

        /** @var AccountOperation $accountOperation */
        $accountOperation = $accountOperationType->accountsOperations()->first();

        $emailSendingCounter = 1;

        if ($accountOperation) {
            $emailSendingCounter += $accountOperation->countMailing();
        }

        $encrypter = new Encrypter;
        $token = $encrypter->generateToken(64, AccountOperation::class);

        $this->operationable()->updateOrCreate([],
        [
            'account_operation_type_id' => $accountOperationType->id,
            'token' => $token,
            'email_sending_counter' => $emailSendingCounter
        ]);

        $url = env('APP_URL') . '/' . $urlEndpoint . '?token=' . $token; // TODO Poprawić na prawidłowy URL
        Mail::to($this)->send(new $mail($url));
    }
}
