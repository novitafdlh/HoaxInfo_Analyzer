<x-portal-shell
    title="Pengaturan Profil"
    :mode="$user->role === 'admin' ? 'admin' : 'user'"
    container-class="max-w-7xl"
    content-class="space-y-0"
>
    <x-slot name="pageHeader">
        <section class="mb-6 space-y-4">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h1 class="text-3xl font-black tracking-tight text-slate-950 sm:text-4xl">Pengaturan Profil</h1>
                    <p class="mt-2 max-w-3xl text-sm leading-relaxed text-slate-600 sm:text-base">
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
