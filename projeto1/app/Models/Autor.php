<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Autor extends Model
{
    use HasFactory;

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
        'data_nascimento',
        'nacionalidade',
        'biografia',
    ];

    public function livros()
    {
        return $this->hasMany(Livro::class);
    }
    public function getRouteKeyName()
    {
        return 'id';
    }
}
