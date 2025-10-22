<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auditoria;
use App\Models\TipoAuditoria;


class AuditoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $auditorias = Auditoria::orderBy('id', 'asc')->paginate(10);
        $tipos = TipoAuditoria::all();
        return \Inertia\Inertia::render('auditor/auditorias/list-auditorias', [
            'auditorias' => $auditorias,
            'tipos_auditorias' => $tipos
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo_auditorias_id' => ['required', 'string', 'exists:tipo_auditorias,id'],
            'quem_criou' => ['required', 'string', 'max:255'],
            'analista_responsavel' => ['required', 'string', 'max:255'],
            'processo' => ['required', 'numeric', 'min:0'],
            'produto' => ['required', 'numeric', 'min:0'],
            'tarefa_redmine' => ['required', 'string', 'max:255'],
            'nome_do_projeto' => ['required', 'string', 'max:255'],
        ]);

        Auditoria::create($validated);

        return redirect()->route('auditorias-index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $auditoria = Auditoria::findOrFail($id);

        $validated = $request->validate([
            'tipo_auditorias_id' => ['required', 'string', 'exists:tipo_auditorias,id'],
            'quem_criou' => ['required', 'string', 'max:255'],
            'analista_responsavel' => ['required', 'string', 'max:255'],
            'processo' => ['required', 'numeric', 'min:0'],
            'produto' => ['required', 'numeric', 'min:0'],
            'tarefa_redmine' => ['required', 'string', 'max:255'],
            'nome_do_projeto' => ['required', 'string', 'max:255'],
        ]);

        $auditoria->update($validated);

        return redirect()->route('auditorias-index')->with('success', 'Auditoria atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $auditoria = Auditoria::findOrFail($id);
        $auditoria->delete();

        return redirect()->route('auditorias-index')->with('success', 'Auditoria excluída com sucesso!');
    }
}
