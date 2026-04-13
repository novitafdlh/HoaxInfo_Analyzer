<x-app-layout>
    {{-- Header Halaman yang Lebih Rapi & Modern --}}
    <x-slot name="header">
        <div class="flex items-center gap-4">
            {{-- Ikon Identitas Profil (Opsional) --}}
            <div class="p-2.5 bg-indigo-100 rounded-xl text-indigo-700 shadow-inner border border-indigo-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div>
                <h2 class="font-extrabold text-2xl text-slate-900 tracking-tight leading-tight">
                    {{ __('Pengaturan Profil') }}
                </h2>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-widest mt-0.5">Kelola informasi akun & keamanan Anda</p>
            </div>
        </div>
    </x-slot>

    {{-- Background Lembut agar Lebih Santai --}}
    <div class="py-12 bg-gradient-to-b from-slate-50 to-white min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">
            
            {{-- Card 1: Informasi Profil --}}
            <div class="p-6 sm:p-10 bg-white shadow-xl shadow-slate-100 rounded-3xl border border-slate-100 transition-all duration-300 hover:shadow-indigo-50">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Card 2: Perbarui Kata Sandi --}}
            <div class="p-6 sm:p-10 bg-white shadow-xl shadow-slate-100 rounded-3xl border border-slate-100 transition-all duration-300 hover:shadow-rose-50">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Card 3: Hapus Akun (Gaya Sedikit Berbeda untuk Peringatan) --}}
            <div class="p-6 sm:p-10 bg-white shadow-xl shadow-slate-100 rounded-3xl border border-rose-100 transition-all duration-300 hover:shadow-rose-100/50">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>