<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NaoConformidade;
use App\Models\TipoAuditoria;


class NaoConformidadeController extends Controller
{
    public function index()
    {


        $nao_conformidades = NaoConformidade::with(['auditorias', 'tipoAuditoria'])
            ->orderBy('id', 'asc')
            ->paginate(10);
        
        $tipos_auditoria = TipoAuditoria::orderBy('nome', 'asc')->get();
        
        return \Inertia\Inertia::render('auditor/naoconformidades/list-nao-conformidades', [
            'nao_conformidades' => $nao_conformidades,
            'tipos_auditoria' => $tipos_auditoria
        ]);
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'sigla' => ['required', 'string', 'max:255', 'unique:nao_conformidades,sigla'],
            'descricao' => ['nullable', 'string'],
            'tipo_auditoria_id' => ['nullable', 'uuid', 'exists:tipo_auditorias,id'],
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
            'tipo_auditoria_id' => ['nullable', 'uuid', 'exists:tipo_auditorias,id'],

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
