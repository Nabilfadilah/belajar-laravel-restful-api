<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model implements Authenticatable
{
    protected $table = "users";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    // data yang bisa diubah
    protected $fillable = [
        'username',
        'password',
        'name'
    ];

    // many
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, "user_id", "id");
    }

    // implement semua method dari Authenticatable
    public function getAuthIdentifierName()
    {
        return 'username';
    }

    public function getAuthIdentifier()
    {
        return $this->username; // dapatkan username
    }

    public function getAuthPassword()
    {
        return $this->password; // dapatkan password
    }

    public function getRememberToken()
    {
        return $this->token; // dapatkan token
    }

    public function setRememberToken($value)
    {
        $this->token = $value; // atur token
    }

    public function getRememberTokenName()
    {
        return 'token';
    }
}
