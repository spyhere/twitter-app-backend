<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relationship between User model and Post model
     *
     * @return HasMany
     */

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Relationship between User and Profile model
     *
     * @return HasOne
     */

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Mutator to hash password
     *
     * @param $value
     */

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Create a token for current user
     *
     * @param string $type
     * @param integer $lifetime
     * @param array $data
     * @return mixed
     */

    public function getToken($type ,$lifetime = 5, $data = [])
    {
        $customClaims = [
            'sub' => $this->id,
            'type' => $type,
            'exp' => Carbon::now()->addMinutes($lifetime)->timestamp,
        ];

        return encrypt(array_merge($data, $customClaims));
    }
}
