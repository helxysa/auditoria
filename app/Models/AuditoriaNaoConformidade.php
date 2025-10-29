<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AuditoriaNaoConformidade extends Pivot
{
    protected $table = 'auditoria_nao_conformidade';

    public $incrementing = false;

    protected $fillable = [
        'auditoria_id',
        'nao_conformidade_id'
    ];

    /**
     * Auditoria relacionada.
     * Carrega eagerly o tipo de auditoria para otimizar buscas.
     */
    public function auditoria()
    {
        return $this->belongsTo(Auditoria::class, 'auditoria_id')
                    ->with('tipoAuditoria');
    }

    /**
     * Não conformidade relacionada.
     */
    public function naoConformidade()
    {
        return $this->belongsTo(NaoConformidade::class, 'nao_conformidade_id');
    }

    /**
     * Acesso direto ao tipo de auditoria através da auditoria.
     * Útil para filtragem rápida por tipo de auditoria.
     */
    public function tipoAuditoria()
    {
        return $this->hasOneThrough(
            TipoAuditoria::class,
            Auditoria::class,
            'id', // Foreign key na tabela Auditoria
            'id', // Foreign key na tabela TipoAuditoria
            'auditoria_id', // Local key na tabela pivot
            'tipo_auditorias_id' // Local key na tabela Auditoria
        );
    }
}
