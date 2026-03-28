<?php

namespace App\Http\Controllers;

use App\DTOs\TicketDTO;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Company;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TicketController extends Controller
{
    public function __construct(
        protected TicketService $ticketService
    ) {}

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

    public function store(StoreTicketRequest $request): RedirectResponse
    {
        $dto = TicketDTO::fromRequest($request);

        $this->ticketService->createTicket($dto, auth()->id());

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

    public function update(UpdateTicketRequest $request, int $id): RedirectResponse
    {
        $ticket = Ticket::findOrFail($id);
        $dto = TicketDTO::fromRequest($request);

        $this->ticketService->updateTicket($ticket, $dto);

        return redirect()->route('tickets.show', $ticket->id)->with('status', 'Ticket updated successfully!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $ticket = Ticket::findOrFail($id);

        $this->ticketService->deleteTicket($ticket);

        return redirect()->route('tickets.index')->with('status', 'Ticket deleted successfully!');
    }
}
