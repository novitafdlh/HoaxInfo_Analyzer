<x-guest-layout>
    <div class="flex flex-col items-center justify-center">
        
        <div class="w-full sm:max-w-md px-6">
            
            <div class="mb-8 text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Buat Akun Baru</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Silakan lengkapi data di bawah ini untuk mendaftar.
                </p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <x-input-label for="name" :value="__('Nama Lengkap')" class="font-medium text-gray-700" />
                    <x-text-input id="name" 
                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm py-2.5" 
                        type="text" 
                        name="name" 
                        :value="old('name')" 
                        required 
                        autofocus 
                        placeholder="Masukkan nama Anda"
                        autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="email" :value="__('Alamat Email')" class="font-medium text-gray-700" />
                    <x-text-input id="email" 
                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm py-2.5" 
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        placeholder="contoh@email.com"
                        autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password" :value="__('Kata Sandi')" class="font-medium text-gray-700" />
                    <x-text-input id="password" 
                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm py-2.5"
                        type="password"
                        name="password"
                        required 
                        placeholder="Minimal 8 karakter"
                        autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" class="font-medium text-gray-700" />
                    <x-text-input id="password_confirmation" 
                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm py-2.5"
                        type="password"
                        name="password_confirmation" 
                        required 
                        placeholder="Ulangi kata sandi"
                        autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="pt-3">
                    <x-primary-button class="w-full justify-center py-3 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-900 rounded-xl shadow-lg transition-all duration-200">
                        {{ __('Daftar Sekarang') }}
                    </x-primary-button>
                </div>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-500 font-semibold transition duration-150">
                            Masuk di sini
                        </a>
                    </p>
                </div>
            </form>
        </div>

    </div>
</x-guest-layout>