<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 rounded-full bg-rose-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-rose-200 transition hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:ring-offset-2 active:scale-[0.98]']) }}>
    {{ $slot }}
</button>
