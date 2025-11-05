<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Auditoria;
use App\Models\TipoAuditoria;
use App\Models\NaoConformidade;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa se o endpoint do dashboard retorna 200 OK
     */
    public function test_dashboard_endpoint_returns_success(): void
    {
        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
    }

    /**
     * Testa se o dashboard retorna todas as metricas esperadas
     */
    public function test_dashboard_returns_all_expected_metrics(): void
    {
        $response = $this->get('/dashboard');

        $response->assertJsonStructure([
            'total_auditorias',
            'auditorias_por_tipo',
            'distribuicao_nao_conformidades',
            'nao_conformidades_por_tipo',
            'media_processo_produto_por_tipo',
            'timeline_auditorias',
            'top_nao_conformidades',
            'distribuicao_por_analista',
        ]);
    }

    /**
     * Testa se o total de auditorias esta correto
     */
    public function test_dashboard_counts_auditorias_correctly(): void
    {
        // Arrange: Criar tipo de auditoria
        $tipoAuditoria = TipoAuditoria::factory()->create();

        // Criar 5 auditorias
        Auditoria::factory()->count(5)->create([
            'tipo_auditorias_id' => $tipoAuditoria->id
        ]);

        // Act
        Cache::flush(); // Limpar cache para garantir dados atualizados
        $response = $this->get('/dashboard');

        // Assert
        $response->assertJson([
            'total_auditorias' => 5
        ]);
    }

    /**
     * Testa se a distribuicao de nao conformidades tem todas as categorias
     */
    public function test_dashboard_distribuicao_has_all_categories(): void
    {
        $response = $this->get('/dashboard');

        $data = $response->json();

        $labels = array_column($data['distribuicao_nao_conformidades'], 'label');

        $this->assertContains('0 NC', $labels);
        $this->assertContains('1-3 NC', $labels);
        $this->assertContains('4-6 NC', $labels);
        $this->assertContains('7+ NC', $labels);
    }

    /**
     * Testa se a timeline retorna 6 meses de dados
     */
    public function test_dashboard_timeline_returns_six_months(): void
    {
        $response = $this->get('/dashboard');

        $data = $response->json();

        $this->assertCount(6, $data['timeline_auditorias']);
    }

    /**
     * Testa se cada mes da timeline tem a estrutura correta
     */
    public function test_dashboard_timeline_has_correct_structure(): void
    {
        $response = $this->get('/dashboard');

        $data = $response->json();

        foreach ($data['timeline_auditorias'] as $mes) {
            $this->assertArrayHasKey('mes', $mes);
            $this->assertArrayHasKey('quantidade', $mes);
            $this->assertMatchesRegularExpression('/^\d{4}-\d{2}$/', $mes['mes']);
            $this->assertIsInt($mes['quantidade']);
        }
    }

    /**
     * Testa se o cache funciona corretamente
     */
    public function test_dashboard_uses_cache(): void
    {
        // Primeira requisicao - popula o cache
        $response1 = $this->get('/dashboard');
        $data1 = $response1->json();

        // Criar nova auditoria (mas cache ainda deve estar ativo)
        $tipoAuditoria = TipoAuditoria::factory()->create();
        Auditoria::factory()->create([
            'tipo_auditorias_id' => $tipoAuditoria->id
        ]);

        // Segunda requisicao - deve retornar do cache (mesmo valor)
        $response2 = $this->get('/dashboard');
        $data2 = $response2->json();

        // Assert: Valores devem ser iguais porque veio do cache
        $this->assertEquals($data1['total_auditorias'], $data2['total_auditorias']);

        // Limpar cache
        Cache::flush();

        // Terceira requisicao - deve buscar dados atualizados
        $response3 = $this->get('/dashboard');
        $data3 = $response3->json();

        // Assert: Agora deve ter 1 auditoria a mais
        $this->assertEquals($data1['total_auditorias'] + 1, $data3['total_auditorias']);
    }

    /**
     * Testa se as auditorias por tipo estao corretas
     */
    public function test_dashboard_auditorias_por_tipo_structure(): void
    {
        $tipoAuditoria = TipoAuditoria::factory()->create(['nome' => 'Teste']);
        Auditoria::factory()->count(3)->create([
            'tipo_auditorias_id' => $tipoAuditoria->id
        ]);

        Cache::flush();
        $response = $this->get('/dashboard');

        $data = $response->json();

        $this->assertIsArray($data['auditorias_por_tipo']);

        foreach ($data['auditorias_por_tipo'] as $item) {
            $this->assertArrayHasKey('tipo', $item);
            $this->assertArrayHasKey('quantidade', $item);
        }
    }

    /**
     * Testa se a media de processo e produto tem estrutura correta
     */
    public function test_dashboard_media_processo_produto_structure(): void
    {
        $tipoAuditoria = TipoAuditoria::factory()->create();
        Auditoria::factory()->create([
            'tipo_auditorias_id' => $tipoAuditoria->id,
            'processo' => 85.5,
            'produto' => 90.0
        ]);

        Cache::flush();
        $response = $this->get('/dashboard');

        $data = $response->json();

        if (count($data['media_processo_produto_por_tipo']) > 0) {
            foreach ($data['media_processo_produto_por_tipo'] as $item) {
                $this->assertArrayHasKey('tipo', $item);
                $this->assertArrayHasKey('media_processo', $item);
                $this->assertArrayHasKey('media_produto', $item);
                $this->assertIsFloat($item['media_processo']);
                $this->assertIsFloat($item['media_produto']);
            }
        }
    }

    /**
     * Testa se top nao conformidades retorna no maximo 10 itens
     */
    public function test_dashboard_top_nao_conformidades_limit(): void
    {
        // Criar mais de 10 nao conformidades
        $tipoAuditoria = TipoAuditoria::factory()->create();
        NaoConformidade::factory()->count(15)->create([
            'tipo_auditoria_id' => $tipoAuditoria->id
        ]);

        Cache::flush();
        $response = $this->get('/dashboard');

        $data = $response->json();

        $this->assertLessThanOrEqual(10, count($data['top_nao_conformidades']));
    }

    /**
     * Testa se distribuicao por analista filtra valores nulos
     */
    public function test_dashboard_distribuicao_analista_filters_nulls(): void
    {
        $tipoAuditoria = TipoAuditoria::factory()->create();

        // Criar auditorias com e sem analista
        Auditoria::factory()->create([
            'tipo_auditorias_id' => $tipoAuditoria->id,
            'analista_responsavel' => 'Joao Silva'
        ]);
        Auditoria::factory()->create([
            'tipo_auditorias_id' => $tipoAuditoria->id,
            'analista_responsavel' => null
        ]);

        Cache::flush();
        $response = $this->get('/dashboard');

        $data = $response->json();

        // Assert: Nenhum analista deve ser null
        foreach ($data['distribuicao_por_analista'] as $item) {
            $this->assertNotNull($item['analista']);
        }
    }
}
