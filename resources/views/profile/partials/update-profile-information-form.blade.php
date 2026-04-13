<section class="max-w-xl">
    {{-- Header dengan Spacing yang Lebih Rapi --}}
    <header class="pb-6 border-b border-slate-100 mb-8 flex items-start gap-4">
        {{-- Ikon Kecil User (Opsional) --}}
        <div class="p-2.5 bg-indigo-50 rounded-xl text-indigo-600 shadow-inner">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-950 tracking-tight">
                {{ __('Informasi Profil') }}
            </h2>

            <p class="mt-2 text-sm text-slate-600 leading-relaxed">
                {{ __("Perbarui informasi profil akun dan alamat email Anda.") }}
            </p>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    {{-- Form dengan Spacing Modern --}}
    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        {{-- Input Nama --}}
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" class="font-medium text-slate-700 ml-1" />
            <x-text-input id="name" name="name" type="text" class="mt-1.5 block w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm py-2.5 transition duration-150" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2 ml-1" :messages="$errors->get('name')" />
        </div>

        {{-- Input Email --}}
        <div>
            <x-input-label for="email" :value="__('Alamat Email')" class="font-medium text-slate-700 ml-1" />
            <x-text-input id="email" name="email" type="email" class="mt-1.5 block w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm py-2.5 transition duration-150" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2 ml-1" :messages="$errors->get('email')" />

            {{-- Bagian Verifikasi Email --}}
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-4 p-4 rounded-xl border border-amber-200 bg-amber-50">
                    <p class="flex items-center gap-2.5 text-sm text-amber-900">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <span>{{ __('Alamat email Anda belum terverifikasi.') }}</span>
                    </p>

                    <button form="send-verification" class="mt-3 text-sm text-indigo-700 hover:text-indigo-500 font-semibold underline rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150">
                        {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-3 font-medium text-sm text-green-700">
                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Bagian Tombol Simpan & Notifikasi Sukses --}}
        <div class="flex items-center gap-5 pt-4 border-t border-slate-100">
            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-900 rounded-xl px-6 py-3 font-bold text-sm tracking-wide">
                {{ __('Simpan Perubahan') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-x-1"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 translate-x-1"
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-sm text-green-700 font-medium flex items-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('Berhasil disimpan.') }}
                </p>
            @endif
        </div>
    </form>
</section>