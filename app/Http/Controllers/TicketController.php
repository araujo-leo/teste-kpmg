<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function index() : \Inertia\Response
    {
        $tickets = Ticket::with(['project.company', 'user', 'detail'])
            ->latest()
            ->paginate(10);

        return Inertia::render('tickets/Index', [
            'tickets' => $tickets
        ]);
    }

    public function create() : \Inertia\Response
    {
        $companies = Company::with('projects')->get();

        return Inertia::render('tickets/Create', [
            'companies' => $companies
        ]);
    }

    public function store(Request $request) : \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'environment' => 'nullable|string|max:255',
            'module' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:json,txt,pdf,jpg,jpeg,png|max:5120',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'local');
        }

        $ticket = Ticket::create([
            'project_id' => $validated['project_id'],
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'attachment_path' => $attachmentPath,
        ]);

        $ticket->detail()->create([
            'environment' => $validated['environment'] ?? null,
            'module' => $validated['module'] ?? null,
        ]);

        return redirect()->route('dashboard')->with('status', 'Ticket created successfully!');
    }
}
