<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {                
                $model->{$model->getKeyName()} = (string) Str::uuid();            
            }
        });
    }
    
    protected $fillable = [
        'nome',
        'email',
        'senha',
        'telefone',
        'endereco',
        'data_nascimento',
        'tipo_usuario',
        'ativo',
    ];

    protected $hidden = [
        'senha',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'senha' => 'hashed',
        'telefone' => 'string',
        'endereco' => 'string',
        'ativo' => 'boolean',
    ];

    public function getAuthPassword()
    {
        return $this->senha;
    }


}
