<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Inertia\Inertia;
use Inertia\Response;

class AgendaController extends Controller
{
    public function index(string $locale): Response
    {
        $events = Event::query()
            ->with('category')
            ->orderBy('date_time')
            ->get()
            ->map(fn (Event $event): array => $event->toPublicPayload($locale));

        return Inertia::render('Agenda/Index', [
            'events' => $events,
        ]);
    }
}
