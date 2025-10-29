<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Auditoria extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'tipo_auditorias_id',
        'quem_criou',
        'analista_responsavel',
        'processo',
        'produto',
        'tarefa_redmine',
        'nome_do_projeto'
    ];

    protected $casts = [
        'quem_criou' => 'string',
        'analista_responsavel' => 'string',
        'processo' => 'decimal:2',
        'produto' => 'decimal:2',
        'tarefa_redmine' => 'string',
        'nome_do_projeto' => 'string'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid();
            }
        });
    }

    /**
     * Tipo de auditoria.
     */
    public function tipoAuditoria()
    {
        return $this->belongsTo(TipoAuditoria::class, 'tipo_auditorias_id');
    }

    /**
     * Não conformidades associadas a esta auditoria.
     */
    public function naoConformidades()
    {
        return $this->belongsToMany(NaoConformidade::class, 'auditoria_nao_conformidade')
                    ->using(AuditoriaNaoConformidade::class);
    }
}
