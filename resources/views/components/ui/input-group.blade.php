<div
    {{ $attributes->merge(['class' => 'flex w-full items-center overflow-hidden rounded-2xl border border-[#ded6cf] bg-white/96 text-slate-900 shadow-[0_10px_24px_rgba(74,35,41,0.06)] transition focus-within:border-[#cdbfb4] focus-within:shadow-[0_14px_30px_rgba(74,35,41,0.1)]']) }}>
    {{ $slot }}
</div>
