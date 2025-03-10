<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{

    use HasFactory, Notifiable;
    use HasRoles;
    use InteractsWithMedia;

   
    const ADMIN = 'admin'; 
    const CUSTOMER = 'customer'; 
    const STORE_MANAGER = 'store_manager'; 
    const CUSTOMER_SUPPORT = 'customer_support'; 
    const WAREHOUSE_STAFF = 'warehouse_staff'; 
    const ACCOUNTANT = 'accountant'; 
    const MARKETING_MANAGER = 'marketing_manager';


    public function orders(){
        return $this->hasMany(Order::class,'user_id');
    }

    public function carts(){
        return $this->hasMany(Cart::class,'user_id');
    }

    public function wishlists(){
        return $this->hasMany(Wishlist::class,'user_id');
    }
    


    protected function getDefaultGuardName(): string
    {
        return 'api';
    }

    protected $guarded = ['id'];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}