<x-guest-layout>
    <div class="flex flex-col items-center justify-center">
        
        <div class="w-full sm:max-w-md px-6">
            
            <div class="mb-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-red-50 rounded-full mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Konfirmasi Sandi</h2>
                <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                    {{ __('Ini adalah area aman. Harap konfirmasi kata sandi Anda sebelum melanjutkan ke halaman berikutnya.') }}
                </p>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <div>
                    <x-input-label for="password" :value="__('Kata Sandi')" class="font-medium text-gray-700" />
                    <x-text-input id="password" 
                        class="block mt-1.5 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm py-3"
                        type="password"
                        name="password"
                        required 
                        placeholder="Masukkan sandi Anda"
                        autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="mt-8">
                    <x-primary-button class="w-full justify-center py-3.5 bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg transition-all duration-200">
                        {{ __('Konfirmasi Sekarang') }}
                    </x-primary-button>
                </div>
            </form>
        </div>

    </div>
</x-guest-layout>