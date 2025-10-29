<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditoriaNaoConformidade as AuditoriaNaoConformidadeModel;

class AuditoriaNaoConformidade extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tipo = $request->query('tipo', 'todos');

        $query = AuditoriaNaoConformidadeModel::with(['auditoria.tipoAuditoria', 'naoConformidade']);

        if ($tipo !== 'todos') {
            $query->whereHas('auditoria', function ($q) use ($tipo) {
                switch ($tipo) {
                    case 'qualidade':
                        $q->whereNotNull('processo');
                        break;
                    case 'servicos':
                        $q->whereNotNull('produto');
                        break;
                    case 'produtos':
                        $q->whereNotNull('produto');
                        break;
                }
            });
        }

        $relacoes = $query->orderBy('created_at', 'desc')->get();

        return response()->json($relacoes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
