@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-dark-grey-900']) }}>
    {{ $value ?? $slot }}
</label>
