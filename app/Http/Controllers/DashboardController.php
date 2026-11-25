<?php

namespace App\Http\Controllers;

use App\Support\PortalAccess;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        abort_unless($user, 403);

        $portal = PortalAccess::resolve($user);

        if ($portal['portalRole'] === 'admin') {
            return redirect()->route('filament.admin.pages.dashboard');
        }

        return view('dashboard.index', $portal);
    }
}
