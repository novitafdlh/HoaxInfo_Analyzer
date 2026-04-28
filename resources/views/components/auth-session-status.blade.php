@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'rounded-[1.5rem] border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800']) }}>
        {{ $status }}
    </div>
@endif
