<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\BrotherhoodPageController;
use App\Http\Controllers\CookieConsentController;
use App\Http\Controllers\CultosPageController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\MemberApplicationController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ObraSocialPageController;
use App\Http\Controllers\PatrimonioPageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Models\Event;
use App\Models\News;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $locale = session('locale', config('app.locale', 'es'));
    $locale = in_array($locale, ['es', 'en'], true) ? $locale : 'es';

    return redirect("/{$locale}");
});

Route::prefix('{locale}')
    ->whereIn('locale', ['es', 'en'])
    ->group(function () {
        Route::get('/', function (string $locale) {
            $news = News::query()
                ->where('is_published', true)
                ->latest()
                ->limit(6)
                ->get(['id', 'title', 'slug', 'content', 'image_path', 'created_at']);

            $agendaEvents = Event::query()
                ->with('category')
                ->where('date_time', '>=', now()->subMonths(2)->startOfMonth())
                ->where('date_time', '<=', now()->addMonths(8)->endOfMonth())
                ->orderBy('date_time')
                ->get()
                ->map(fn (Event $event): array => $event->toPublicPayload($locale));

            return Inertia::render('Home', [
                'latestNews' => $news,
                'agendaEvents' => $agendaEvents,
            ]);
        })->name('home');

        Route::get('/noticias', [NewsController::class, 'index'])->name('news.index');
        Route::get('/noticias/{slug}', [NewsController::class, 'show'])->name('news.show');

        Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');

        Route::get('/historia', function () {
            return Inertia::render('History/Index');
        })->name('history.index');

        Route::get('/titulares', function () {
            return Inertia::render('Titulares/Index');
        })->name('titulares.index');

        Route::get('/contacto', function () {
            return Inertia::render('Contact/Index');
        })->name('contact.index');

        Route::get('/hazte-hermano', [MemberApplicationController::class, 'create'])
            ->name('member_application.create');
        Route::post('/hazte-hermano', [MemberApplicationController::class, 'store'])
            ->name('member_application.store');

        Route::get('/documentacion', [DocumentationController::class, 'index'])
            ->name('documentation.index');

        Route::get('/tienda-recuerdos', [ShopController::class, 'index'])
            ->name('shop.index');

        Route::get('/hermandad/{key}', [BrotherhoodPageController::class, 'show'])
            ->name('brotherhood.show');

        Route::get('/cultos/{key}', [CultosPageController::class, 'show'])
            ->whereIn('key', [
                'internos',
                'externos',
                'estacion-penitencia-cofradia',
                'estacion-penitencia-horario',
                'corpus-christi',
                'culto-misa-corporativa',
                'culto-triduo-cuaresma-titulares',
                'culto-via-crucis-cuaresma',
                'culto-cristo-rey-protestacion-fe',
                'culto-virgen-salud-septiembre',
                'culto-san-pedro-apostol',
                'culto-virgen-mayo',
            ])
            ->name('cultos.show');

        Route::get('/patrimonio/{key}', [PatrimonioPageController::class, 'show'])
            ->whereIn('key', ['enseres', 'insignia-cofradia', 'paso-cristo-perdon', 'paso-virgen-salud'])
            ->name('patrimonio.show');

        Route::get('/obra-social/{key}', [ObraSocialPageController::class, 'show'])
            ->whereIn('key', ['labor-asistencial', 'diputacion-caridad', 'obra-asistencial'])
            ->name('obra_social.show');

        Route::post('/cookie-consent', [CookieConsentController::class, 'store'])
            ->name('cookie-consent.store');
    });

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
