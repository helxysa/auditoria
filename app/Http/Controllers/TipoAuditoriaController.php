<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoAuditoria;


class TipoAuditoriaController extends Controller
{
    public function index()
    {
        $auditorias = TipoAuditoria::orderBy('id', 'asc')->paginate(10);
        return \Inertia\Inertia::render('auditor/tiposauditorias/list-tipos-de-auditorias', ['tipo_de_auditorias' => $auditorias]);
    }



    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string'
        ]);


        $auditoria = TipoAuditoria::create([
            'nome' => $request->nome,
        ]);

        return redirect()->route('tipos-auditorias-index');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nome' => 'string',
        ]);

        $dados = TipoAuditoria::findOrFail($id);
        TipoAuditoria::updating($dados);
    }

    public function destroy(string $id)
    {
        //
    }
}
