<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NaoConformidade;


class NaoConformidadeController extends Controller
{
    public function index()
    {
        $nao_conformidades = NaoConformidade::orderBy('id', 'asc')->paginate(10);
        return \Inertia\Inertia::render('auditor/naoconformidades/list-nao-conformidades', ['nao_conformidades' => $nao_conformidades]);
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'sigla' => ['required', 'string', 'max:255', 'unique:nao_conformidades,sigla'],
            'descricao' => ['nullable', 'string'],
        ]);

        NaoConformidade::create($validated);

        return redirect()->route('nao-conformidades-index');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'sigla' => 'string',
            'descricao' => 'nullable|string',
        ]);

        $dados = NaoConformidade::findOrFail($id);
        NaoConformidade::updating($dados);
    }

    public function destroy(string $id)
    {
        //
    }
}
