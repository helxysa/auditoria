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
        'nome' => 'string',
        'quem_criou' => 'string',
        'analista_responsavel' => 'string',
        'processo' => 'decimal',
        'produto' => 'decimal',
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
}
