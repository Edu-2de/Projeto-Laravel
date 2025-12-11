<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLivrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('autores', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nome')->unique();
            $table->text('biografia')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->timestamps();   
        });

        Schema::create('generos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nome')->unique();
            $table->string('descricao')->nullable();
            $table->timestamps();   
        });

        Schema::create('livros', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->string('titulo');
            $table->integer('ano_publicacao');
            $table->string('editora');
            $table->string('isbn')->unique();
            $table->text('sinopse')->nullable();
            $table->integer('numero_paginas');
            $table->string('faixa_etaria')->nullable();


            $table->foreignUuid('genero_id')->references('id')->on('generos')->onDelete('restrict');
            $table->foreignUuid('autor_id')->references('id')->on('autores')->onDelete('restrict');

        });

        Schema::create('usuarios', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nome');
            $table->string('email')->unique();
            $table->string('senha');
            $table->string('telefone')->nullable();
            $table->string('endereco')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->enum('tipo_usuario', ['leitor', 'bibliotecario', 'admin'])->default('leitor');
            $table->boolean('ativo')->default(true);
            $table->rememberToken();
            $table->timestamps();   

        });

        Schema::create('emprestimos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('data_emprestimo');
            $table->date('data_devolucao')->nullable();
            $table->date('data_prevista_devolucao');
            $table->boolean('devolvido')->default(false);
            $table->timestamps();

            $table->foreignUuid('usuario_id')->references('id')->on('usuarios')->onDelete('restrict');
            $table->foreignUuid('livro_id')->references('id')->on('livros')->onDelete('restrict');
        });

     
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('livros');
    }
}
