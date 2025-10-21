<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Auditoria;
use App\Models\TipoAuditoria;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Auditoria>
 */
class AuditoriaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tipo_auditorias_id' => fake()->randomElement(TipoAuditoria::pluck('id')),
            'quem_criou' => $this->fake()->name(),
            'analista_responsavel' =>  $this->fake()->name(),
            'processo' => 1.1,
            'produto' => 1.3,
            'tarefa_redmine' => $this->fake()->name(),
            'nome_do_projeto' => $this->fake()->name()

        ];
    }
}
