<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoAuditoria;


class TipoAuditoriaController extends Controller
{
    public function index()
    {
        $auditorias = TipoAuditoria::with(['auditorias'])
            ->orderBy('id', 'asc')
            ->paginate(10);
        return \Inertia\Inertia::render('auditor/tiposauditorias/list-tipos-de-auditorias', ['tipo_de_auditorias' => $auditorias]);
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255', 'unique:tipo_auditorias,nome'],
        ]);

        TipoAuditoria::create($validated);

        return redirect()->route('tipos-auditorias-index');
    }

    public function update(Request $request, string $id)
    {
        $tipo_auditoria = TipoAuditoria::findOrFail($id);



        $validated = $request->validate([
            'nome' => 'string',
        ]);


        $tipo_auditoria->update($validated);

        return redirect()->route('tipos-auditorias-index')->with('Sucess', 'Tipo de Auditoria atualizada com sucesso');
    }

    public function destroy(string $id)
    {
        $tipoAuditoria = TipoAuditoria::findOrFail($id);
        $tipoAuditoria->delete();

        return redirect()->route('tipos-auditorias-index');
    }
}
