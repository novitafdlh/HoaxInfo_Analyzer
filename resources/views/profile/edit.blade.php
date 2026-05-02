<x-portal-shell
    title="Pengaturan Profil"
    :mode="$user->role === 'admin' ? 'admin' : 'user'"
    container-class="max-w-7xl"
    content-class="space-y-0"
>
    <x-slot name="pageHeader">
        <section class="space-y-4">
            <div class="flex justify-between items-end">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-on-surface">Pengaturan Profil</h1>
                    <p class="text-x text-on-surface-variant mt-2">
                        Kelola informasi akun, keamanan, dan status verifikasi email Anda dari satu tempat.
                    </p>
                </div>
            </div>
        </section>
    </x-slot>

    @include('profile.partials.form-content', [
        'user' => $user,
        'adminMode' => $user->role === 'admin',
    ])
</x-portal-shell>
