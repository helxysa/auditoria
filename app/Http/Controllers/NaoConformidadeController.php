<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NaoConformidade;


class NaoConformidadeController extends Controller
{
    public function index()
    {


        $nao_conformidades = NaoConformidade::with(['auditorias'])
            ->orderBy('id', 'asc')
            ->paginate(10);
        return \Inertia\Inertia::render('auditor/naoconformidades/list-nao-conformidades', ['nao_conformidades' => $nao_conformidades]);
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'sigla' => ['required', 'string', 'max:255', 'unique:nao_conformidades,sigla'],
            'descricao' => ['nullable', 'string'],
            'tipo_de_nao_conformiade' => ['nullable', 'string'],
        ]);

        NaoConformidade::create($validated);

        return redirect()->route('nao-conformidades-index');
    }

    public function update(Request $request, string $id)
    {

        $nao_conformidade = NaoConformidade::findOrFail($id);


        $validated = $request->validate([
            'sigla' => 'string',
            'descricao' => 'nullable|string',
            'tipo_de_nao_conformiade' => ['nullable', 'st ring'],

        ]);

        $nao_conformidade->update($validated);

        return redirect()->route('nao-conformidades-index')->with('Sucess', 'Nao conformidade atualizada com sucesso');
    }

    public function destroy(string $id)
    {
        $nao_conformidade = NaoConformidade::findOrfail($id);
        $nao_conformidade->delete();

        return redirect()->route('nao-conformidades-index')->with('sucess', 'Não conformidade foi deletada com sucesso');
    }
}
