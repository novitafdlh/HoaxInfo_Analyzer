@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full rounded-[1.25rem] border border-slate-200 bg-slate-50/70 px-5 py-3.5 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 disabled:cursor-not-allowed disabled:opacity-60']) }}>
