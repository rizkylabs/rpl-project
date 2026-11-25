@extends('layouts.portal')

@section('title', 'Dashboard Orang Tua')

@section('content')
<div class="rounded-xl border border-slate-200 bg-white p-6">

    <span class="text-sm font-medium text-slate-500">
        Portal Orang Tua
    </span>

    <h1 class="mt-2 text-2xl font-bold">
        Selamat datang, {{ auth()->user()->name }}
    </h1>

    <p class="mt-2 text-sm text-slate-600">
        Anda berhasil masuk ke portal Orang Tua.
    </p>

    <div class="mt-6 rounded-lg border border-dashed border-slate-300 p-6">
        <h2 class="font-semibold">
            Dashboard dalam pengembangan
        </h2>

        <p class="mt-2 text-sm text-slate-500">
            Halaman ini merupakan kerangka awal untuk pengembangan fitur EduSync.
        </p>
    </div>

</div>
@endsection