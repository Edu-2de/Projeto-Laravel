<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Livro extends Model
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
        'titulo',
        'ano_publicacao',
        'editora',
        'isbn',
        'sinopse',
        'numero_paginas',
        'faixa_etaria',
        'genero_id',
        'autor_id',
    ];

    

    public function genero()
    {
        return $this->belongsTo(Genero::class);
    }

    public function autor()
    {
        return $this->belongsTo(Autor::class);
    }
}
