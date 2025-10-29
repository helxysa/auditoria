<?php

namespace App\Http\Controllers;

use App\Services\RedmineService;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class RedmineController extends Controller
{
    private RedmineService $redmineService;

    public function __construct(RedmineService $redmineService)
    {
        $this->redmineService = $redmineService;
    }

    /**
     * Renderiza a página e carrega dados sob demanda usando lazy loading do Inertia
     */
    public function index(): Response
    {
        return Inertia::render('dashboard/redmine/redmine', [
            'projectsCount' => Inertia::lazy(fn () => count($this->redmineService->getProjects())),
            'issuesStats' => Inertia::lazy(fn () => $this->redmineService->getNaoConformidadesStats()),
        ]);
    }
}
