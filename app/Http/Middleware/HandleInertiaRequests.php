<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;
use Illuminate\Support\Facades\Session;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            // Data auth, ziggy, dll.
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    // tambahkan role jika perlu, misal:
                    // 'role' => $request->user()->getRoleNames()->first()
                ] : null,
            ],
            // Ini bagian KRUSIAL untuk flash messages
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error'   => fn () => $request->session()->get('error'),
                // Anda bisa menambahkan key flash lain jika ada (e.g., warning, info)
                // 'warning' => fn () => $request->session()->get('warning'),
            ],
            // Pastikan Anda juga menyertakan 'errors' untuk validasi
            'errors' => fn () => Session::get('errors')
                ? Session::get('errors')->getBag('default')->getMessages()
                : (object) [],
        ]);
    }
}
