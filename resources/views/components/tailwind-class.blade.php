{{-- resources/views/components/tailwind-class.blade.php --}}
@php
    $classes = [
        //'perime' => 0
        'perime' => 'bg-black text-white font-bold',
        //'tres_proche' => 3
        'tres_proche' => 'bg-red-600 text-white font-bold',
        //'proche' => 7
        'proche' => 'bg-orange-400 text-black font-bold',
        //'correcte' => 30
        'correcte' => 'bg-yellow-400 text-black font-bold',
        //'loin' => 31
        'loin' => 'bg-green-600 text-white font-bold',
    ];
@endphp

{{ $classes[$status] ?? '' }}
