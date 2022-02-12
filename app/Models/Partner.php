<?php

namespace App\Models;

use App\Exceptions\ApiException;
use App\Http\ErrorCodes\BaseErrorCode;
use App\Http\Libraries\FileProcessing\FileProcessing;
use App\Http\Libraries\Validation\Validation;
use App\Http\Traits\Encryptable;
use Illuminate\Support\Facades\Storage;

class Partner extends BaseModel
{
    use Encryptable;

    protected $fillable = [
        'first_name',
        'last_name',
        'business_name',
        'contact_email',
        'invoice_email',
        'telephone',
        'facebook_profile',
        'instagram_profile',
        'website',
        'nip',
        'street',
        'post_code',
        'city_id'
    ];

    protected $guarded = [
        'id',
        'user_id',
        'alias',
        'submerchant_id',
        'avarage_rating',
        'rating_counter',
        'creator_id',
        'editor_id',
        'przelewy24_verified_at',
        'contact_email_verified_at',
        'invoice_email_verified_at',
        'telephone_verified_at',
        'verified_at',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'user_id',
        'submerchant_id',
        'first_name',
        'last_name',
        'alias',
        'business_name',
        'contact_email',
        'invoice_email',
        'telephone',
        'facebook_profile',
        'instagram_profile',
        'website',
        'nip',
        'street',
        'post_code',
        'city_id',
        'avarage_rating',
        'rating_counter',
        'creator_id',
        'editor_id',
        'przelewy24_verified_at',
        'contact_email_verified_at',
        'invoice_email_verified_at',
        'telephone_verified_at',
        'verified_at',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'avarage_rating' => 'float',
        'rating_counter' => 'int',
        'przelewy24_verified_at' => 'string',
        'contact_email_verified_at' => 'string',
        'invoice_email_verified_at' => 'string',
        'telephone_verified_at' => 'string',
        'verified_at' => 'string',
        'deleted_at' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    protected $encryptable = [
        'submerchant_id' => 6,
        'first_name' => 30,
        'last_name' => 30,
        'business_name' => 200,
        'contact_email' => 254,
        'invoice_email' => 254,
        'telephone' => 24,
        'facebook_profile' => 255,
        'instagram_profile' => 255,
        'website' => 255,
        'nip' => 10,
        'street' => 80,
        'post_code' => 5,
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function city() {
        return $this->belongsTo(Area::class, 'city_id');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function editor() {
        return $this->belongsTo(User::class, 'editor_id');
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

    public function evaluable() {
        return $this->morphMany(Rating::class, 'evaluable');
    }

    public function evaluatorRating() {
        return $this->morphMany(Rating::class, 'evaluator');
    }

    public function evaluatorRatingUsefulness() {
        return $this->morphMany(RatingUsefulness::class, 'evaluator');
    }

    public function contractable() {
        return $this->morphMany(Agreement::class, 'contractable');
    }

    public function reportable() {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function partnerSettings() {
        return $this->hasMany(PartnerSetting::class);
    }

    public function discountCode() {
        return $this->hasMany(DiscountCode::class, 'payer_id');
    }

    /**
     * Zwrócenie listy lub pojedynczego loga partnera
     * 
     * @param bool $all flaga z informacją czy mają zostać zwrócone wszystkie loga partnera
     * 
     * @return array|null
     */
    public function getLogos(bool $all = false): ?array {

        $defaultType = Validation::getDefaultType('LOGO', 'IMAGE_TYPE');

        $result = null;

        if ($all) {

            /** @var ImageAssignment $logos */
            $logos = $this->imageAssignments()->where('image_type_id', $defaultType->id)->orderBy('number', 'desc')->get();

            /** @var ImageAssignment $a */
            foreach ($logos as $l) {

                /** @var Image $image */
                $image = $l->image()->first();

                $result[] = [
                    'id' => (int) $l->id,
                    'filename' => '/storage/partner-pictures/' . $image->filename
                ];
            }

        } else {

            /** @var ImageAssignment $logo */
            $logo = $this->imageAssignments()->where('image_type_id', $defaultType->id)->orderBy('number', 'desc')->first();

            if ($logo) {
                /** @var Image $image */
                $image = $logo->image()->first();

                $result[] = [
                    'id' => (int) $logo->id,
                    'filename' => '/storage/partner-pictures/' . $image->filename
                ];
            }
        }

        return $result;
    }

    /**
     * Zapisanie loga partnera
     * 
     * @param string $logoPath aktualna ścieżka do loga
     * 
     * @return void
     */
    public function saveLogo(string $logoPath): void {

        $imageType = Validation::getDefaultType('LOGO', 'IMAGE_TYPE');

        $oldLogos = $this->imageAssignments()->where('image_type_id', $imageType->id)->orderBy('number', 'desc')->get();

        $counter = 0;

        foreach ($oldLogos as $oL) {
            $counter++;
        }

        $newNumber = $counter + 1;

        foreach ($oldLogos as $oL) {
            $oL->number = $counter;
            $oL->save();
            $counter--;
        }

        $image = FileProcessing::saveLogo($logoPath, $this);

        $imageAssignment = new ImageAssignment;
        $imageAssignment->imageable_type = 'App\Models\Partner';
        $imageAssignment->imageable_id = $this->id;
        $imageAssignment->image_type_id = $imageType->id;
        $imageAssignment->image_id = $image->id;
        $imageAssignment->number = $newNumber;
        $imageAssignment->creator_id = $this->id;
        $imageAssignment->editor_id = $this->id;
        $imageAssignment->save();
    }

    /**
     * Zmiana loga partnera
     * 
     * @param int $logoId id loga, które ma być teraz aktualnym
     * 
     * @return void
     */
    public function changeLogo(int $logoId): void {

        $imageType = Validation::getDefaultType('LOGO', 'IMAGE_TYPE');

        /** @var ImageAssignment $oldLogos */
        $oldLogos = $this->imageAssignments()->where('image_type_id', $imageType->id)->orderBy('number', 'desc')->get();

        $counter = 0;

        foreach ($oldLogos as $oL) {
            $counter++;
        }

        $newNumber = $counter;

        /** @var ImageAssignment $oldLogos */
        $currentLogo = $oldLogos->where('id', $logoId)->first();

        if ($currentLogo) {
            $currentLogo->number = $newNumber;
            $currentLogo->save();
    
            foreach ($oldLogos as $oL) {
                if ($oL->id != $currentLogo->id) {
                    $counter--;
                    $oL->number = $counter;
                    $oL->save();
                }
            }
        } else {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Podano nieprawidłowy identyfikator loga'
            );
        }
    }

    /**
     * Usunięcie loga partnera
     * 
     * @return void
     */
    public function deleteLogo(int $logoId): void {

        $imageType = Validation::getDefaultType('LOGO', 'IMAGE_TYPE');

        /** @var ImageAssignment $logo */
        $logo = $this->imageAssignments()->where('image_type_id', $imageType->id)->where('id', $logoId)->first();

        if ($logo) {
            Storage::delete('partner-pictures/' . $logo->image()->first()->filename);
            $logo->image()->first()->delete();
        } else {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Podano nieprawidłowy identyfikator loga'
            );
        }
    }
}
