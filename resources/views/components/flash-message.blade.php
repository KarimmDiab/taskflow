
@foreach (['success', 'error', 'warning'] as $type)
    @php
        $bgColor = match ($type) {
            'success' => 'bg-green-600 text-white',
            'error' => 'bg-red-600 text-white',
            'warning' => 'bg-amber-500 text-black',
        };
    @endphp

    @if (session()->has($type))


        <div
            x-data ="{ show : true }"
            x-show = "show"
            x-init = "setTimeout(() => show =false, 4000)"
            x-transition
            class="fixed rop-5 right-5 p-4 rounded-lg shadow-lg z-50 {{ $bgColor }}"
            >

            {{ session($type) }}

        </div>

    @endif

@endforeach
