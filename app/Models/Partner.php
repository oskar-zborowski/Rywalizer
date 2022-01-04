<?php

namespace App\Models;

use App\Http\Traits\Encryptable;

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

    public function partnerSettings() {
        return $this->hasMany(PartnerSetting::class);
    }

    public function discountCode() {
        return $this->hasMany(DiscountCode::class, 'payer_id');
    }
}
