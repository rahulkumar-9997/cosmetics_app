<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'customers';
    protected $fillable = [
        'id',
        'firm_name',
        'contact_person',
        'phone_number',
        'email',
        'customer_id',
        'gst_no',
        'country',
        'state',
        'city', 
        'pin_code',
        'latitude',
        'longitude',
        'locality',
        'permanent_address',
        'added_by',
        'password',
        'google_id',
        'profile_img',
        'status',
        'approval_status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => 'boolean',
        'approval_status' => 'boolean',
    ];
}
