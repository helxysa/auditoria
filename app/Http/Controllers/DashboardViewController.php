<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class DashboardViewController extends Controller
{
    /**
     * Display the dashboard view with metrics data.
     *
     * Reutiliza o DashboardController existente para buscar os dados
     * e passa para o Inertia render.
     */
    public function index(): Response
    {
        // Reutiliza o DashboardController existente
        $dashboardController = new DashboardController();
        $dashboardResponse = $dashboardController->index();

        // Decodifica o JSON response para array
        $dashboardData = json_decode($dashboardResponse->getContent(), true);

        // Renderiza a página Inertia com os dados
        return Inertia::render('dashboard/main-dashboard', [
            'dashboard' => $dashboardData
        ]);
    }
}
