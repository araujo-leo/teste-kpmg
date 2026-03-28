<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TicketController extends Controller
{
    public function index(): Response
    {
        $tickets = Ticket::with(['project.company', 'user', 'detail'])
            ->latest()
            ->paginate(10);

        return Inertia::render('tickets/Index', [
            'tickets' => $tickets,
        ]);
    }

    public function show(int $id): Response
    {
        $ticket = Ticket::with(['project.company', 'user', 'detail'])
            ->findOrFail($id);

        return Inertia::render('tickets/Show', [
            'ticket' => $ticket,
        ]);
    }

    public function create(): Response
    {
        $companies = Company::with('projects')->get();

        return Inertia::render('tickets/Create', [
            'companies' => $companies,
        ]);
    }

    public function store(Request $request): RedirectResponse
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

    public function download(int $id): StreamedResponse
    {
        $ticket = Ticket::findOrFail($id);

        if (! $ticket->attachment_path || ! Storage::disk('local')->exists($ticket->attachment_path)) {
            abort(404, 'Attachment not found.');
        }

        return Storage::disk('local')->download($ticket->attachment_path);
    }

    public function edit(int $id): Response
    {
        $ticket = Ticket::with(['project.company', 'detail'])->findOrFail($id);
        $companies = Company::with('projects')->get();

        return Inertia::render('tickets/Edit', [
            'ticket' => $ticket,
            'companies' => $companies,
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $ticket = Ticket::findOrFail($id);

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'status' => 'required|in:open,in_progress,resolved,closed',
            'environment' => 'nullable|string|max:255',
            'module' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:json,txt,pdf,jpg,jpeg,png|max:5120',
        ]);

        $attachmentPath = $ticket->attachment_path;
        if ($request->hasFile('attachment')) {
            if ($attachmentPath && Storage::disk('local')->exists($attachmentPath)) {
                Storage::disk('local')->delete($attachmentPath);
            }
            $attachmentPath = $request->file('attachment')->store('attachments', 'local');
        }

        $ticket->update([
            'project_id' => $validated['project_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'attachment_path' => $attachmentPath,
        ]);

        $ticket->detail()->updateOrCreate(
            ['ticket_id' => $ticket->id],
            [
                'environment' => $validated['environment'] ?? null,
                'module' => $validated['module'] ?? null,
            ]
        );

        return redirect()->route('tickets.show', $ticket->id)->with('status', 'Ticket updated successfully!');
    }
}
