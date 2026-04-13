<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50">
        <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white shadow-2xl overflow-hidden sm:rounded-2xl border border-gray-100">
            
            <div class="mb-8 text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">
                    Atur Ulang Kata Sandi
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Silakan masukkan email dan kata sandi baru Anda.
                </p>
            </div>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="group">
                    <x-input-label for="email" :value="__('Alamat Email')" class="font-semibold text-gray-700" />
                    <x-text-input id="email" 
                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm transition duration-150" 
                        type="email" 
                        name="email" 
                        :value="old('email', $request->email)" 
                        required 
                        autofocus 
                        placeholder="nama@email.com"
                        autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-6">
                    <x-input-label for="password" :value="__('Kata Sandi Baru')" class="font-semibold text-gray-700" />
                    <x-text-input id="password" 
                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm transition duration-150" 
                        type="password" 
                        name="password" 
                        required 
                        placeholder="••••••••"
                        autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="mt-6">
                    <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" class="font-semibold text-gray-700" />
                    <x-text-input id="password_confirmation" 
                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm transition duration-150" 
                        type="password" 
                        name="password_confirmation" 
                        required 
                        placeholder="••••••••"
                        autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="mt-8">
                    <x-primary-button class="w-full justify-center py-3 text-sm font-bold tracking-widest uppercase bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-900 focus:ring-offset-2 transition-all duration-200 rounded-xl shadow-lg hover:shadow-indigo-200">
                        {{ __('Simpan Kata Sandi') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>