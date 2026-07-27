@props([
    'type' => 'text',
])

<input type="{{ $type }}"
    {{ $attributes->merge(['class' => 'flex h-12 w-full min-w-0 border-0 bg-transparent px-3 text-[15px] font-medium text-[#43292e] shadow-none outline-none placeholder:text-[#9f93a2] focus-visible:outline-none focus-visible:ring-0 disabled:cursor-not-allowed disabled:opacity-60']) }}>
