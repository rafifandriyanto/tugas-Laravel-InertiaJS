{{-- ngasih tau browser kalo dokumen ini pake standar html5 --}}
<!DOCTYPE html>
{{-- tag pembuka html sekaligus ngambil bahasa aplikasi dari setting laravel --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    {{-- bagian head buat naro metadata, judul halaman, font, sama asset js dan css --}}
    <head>
        {{-- atur encoding karakter ke utf-8 biar simbol dan huruf kebaca normal --}}
        <meta charset="utf-8">
        {{-- bikin tampilan responsif ngikutin lebar layar hp atau laptop --}}
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- judul tab browser yang otomatis bisa diubah sama inertia tiap ganti halaman --}}
        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        {{-- nyambungin koneksi lebih awal ke server font biar loading lebih cepet --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        {{-- ngambil font figtree dari bunny fonts buat dipake di seluruh halaman --}}
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- nyuntikin daftar route laravel dari ziggy biar bisa dipanggil di vue lewat fungsi route() --}}
        @routes
        {{-- manggil file js utama sama file halaman vue yang lagi dibuka lewat vite --}}
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        {{-- nampilin tag head tambahan yang dikirim sama komponen inertia --}}
        @inertiaHead
    </head>
    {{-- badan halaman yang pake font sans-serif dan teksnya dibikin halus (antialiased) --}}
    <body class="font-sans antialiased">
        {{-- tempat utama aplikasi inertia dan vue di-render ke layar --}}
        @inertia
    </body>
</html>
