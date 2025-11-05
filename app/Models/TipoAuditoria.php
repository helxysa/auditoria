<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TipoAuditoria extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nome'
    ];

    protected $casts = [
        'nome' => 'string'
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
     * Auditorias deste tipo.
     */
    public function auditorias()
    {
        return $this->hasMany(Auditoria::class, 'tipo_auditorias_id');
    }

    /**
     * Não conformidades associadas a este tipo de auditoria.
     */
    public function naoConformidades()
    {
        return $this->hasMany(NaoConformidade::class, 'tipo_auditoria_id');
    }
}
