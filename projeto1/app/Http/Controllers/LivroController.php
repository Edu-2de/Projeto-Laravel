<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Livro;

class LivroController extends Controller
{
    //  (GET)  /livros
    public function index(Request $request)
    {
        // 1. Inicia a consulta (Query Builder), mas NÃO executa ainda
        // O "with" já prepara para trazer os relacionamentos
        $query = Livro::with(['autor', 'genero']);

        // 2. Verifica se a URL tem ?genero_id=...
        if ($request->has('genero_id')) {
            // Se tiver, adiciona a cláusula WHERE na consulta
            $query->where('genero_id', $request->genero_id);
        }

        // 3. Verifica se a URL tem ?autor_id=... (Bônus: já filtrei por autor também!)
        if ($request->has('autor_id')) {
            $query->where('autor_id', $request->autor_id);
        }
        
        // 4. Bônus: Pesquisa por título (busca parcial)
        // Exemplo: ?titulo=Harry
        if ($request->has('titulo')) {
            $query->where('titulo', 'like', '%' . $request->titulo . '%');
        }

        // 5. AGORA sim, executa a consulta no banco e pega os resultados
        $livros = $query->get();

        return response()->json($livros);
    }

    //  (GET) /livros/{id}
    public function show($id)
    {
        $livro = Livro::with(['autor', 'genero'])->find($id);

        if (!$livro) {
            return response()->json(['message' => 'Livro not found'], 404);
        }

        return response()->json($livro);
    }

    //  (POST) /livros
    public function store(Request $request)
    {
        $dadosValidados = $request->validate([
           'titulo' => 'required|string|max:255',
           'ano_publicacao' => 'required|integer',
           'editora' => 'required|string|max:255',
           'isbn' => 'required|string|max:13|unique:livros,isbn',
           'sinopse' => 'nullable|string',
           'numero_paginas' => 'required|integer',
           'faixa_etaria' => 'nullable|string|max:50',
           'genero_id' => 'required|exists:generos,id',
           'autor_id' => 'required|exists:autores,id',
       ]);

        $livro = Livro::create($dadosValidados);

        return response()->json([
            'message' => 'Livro created successfully',
            'data' => $livro

        ], 201);
    }


    //  (PUT) /livros/{id}
    public function update(Request $request, $id)
    {
        $livro = Livro::find($id);

        if (!$livro) {
            return response()->json(['message' => 'Livro not found'], 404);
        }

        $dadosValidados = $request->validate([
           'titulo' => 'sometimes|required|string|max:255',
           'ano_publicacao' => 'sometimes|required|integer',
           'editora' => 'sometimes|required|string|max:255',
           'isbn' => 'sometimes|required|string|max:13|unique:livros,isbn,' . $id,
           'sinopse' => 'nullable|string',
           'numero_paginas' => 'sometimes|required|integer',
           'faixa_etaria' => 'nullable|string|max:50',
           'genero_id' => 'sometimes|required|exists:generos,id',
           'autor_id' => 'sometimes|required|exists:autores,id',
       ]);

        $livro->update($dadosValidados);

        return response()->json([
            'message' => 'Livro updated successfully',
            'data' => $livro
        ]);
    }

    //  (DELETE) /livros/{id}
    public function destroy($id)
    {
        $livro = Livro::find($id);

        if (!$livro) {
            return response()->json(['message' => 'Livro not found'], 404);
        }

        $livro->delete();

        return response()->json(['message' => 'Livro deleted successfully'],200);
    }
}
