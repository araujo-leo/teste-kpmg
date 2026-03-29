<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Project;
use App\Models\Ticket;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingTickets = Ticket::where('status', 'pending')->count();
        $totalProjects = Project::count();
        $totalCompanies = Company::count();

        $recentTickets = Ticket::with('project')
            ->latest()
            ->take(5)
            ->get();

        $statusData = Ticket::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return Inertia::render('Dashboard', [
            'pendingTickets' => $pendingTickets,
            'activeProjects' => $totalProjects,
            'totalCompanies' => $totalCompanies,
            'recentTickets' => $recentTickets,
            'statusData' => $statusData,
        ]);
    }
}
