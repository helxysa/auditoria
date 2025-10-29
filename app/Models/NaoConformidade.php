<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NaoConformidade extends Model
{
    /** @use HasFactory<\Database\Factories\NaoConformidadeFactory> */
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'sigla',
        'descricao',
        'tipo_de_nao_conformiade'
    ];

    protected $casts = [
        'sigla' => 'string',
        'descricao' => 'string',
        'tipo_de_nao_conformidade' => 'string'
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
     * Auditorias associadas a esta não conformidade.
     */
    public function auditorias()
    {
        return $this->belongsToMany(Auditoria::class, 'auditoria_nao_conformidade')
            ->using(AuditoriaNaoConformidade::class);
    }
}
