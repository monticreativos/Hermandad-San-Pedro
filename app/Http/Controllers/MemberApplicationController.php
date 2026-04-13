<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberApplicationRequest;
use App\Models\MemberApplication;
use Inertia\Inertia;
use Inertia\Response;

class MemberApplicationController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('MemberApplication/Index');
    }

    public function store(StoreMemberApplicationRequest $request): \Illuminate\Http\RedirectResponse
    {
        MemberApplication::query()->create([
            ...$request->validated(),
            'locale' => app()->getLocale(),
        ]);

        return redirect()
            ->route('member_application.create', ['locale' => app()->getLocale()])
            ->with('member_application_sent', true);
    }
}
