@extends('layouts.app')

@section('styles')
<style>
    .sidebar {
        width: 250px;
        height: 100vh;
        background-color: #f8f9fa;
        position: fixed;
        top: 0;
        left: 0;
        padding: 2rem 1rem;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    }

    .main-content {
        margin-left: 250px;
        padding: 2rem;
    }

    .sidebar a {
        display: block;
        padding: 0.75rem 1rem;
        color: #333;
        text-decoration: none;
        margin-bottom: 0.5rem;
        border-radius: 5px;
    }

    .sidebar a:hover {
        background-color: #e2e6ea;
    }
</style>
@endsection

@section('content')
<div class="sidebar">
    <h5>Menu Navigasi</h5>
    <a href="{{ route('map') }}">🗺️ Peta</a>
    <a href="#">🏡 Tentang Dusun</a>
    <a href="#">📊 Data Penduduk</a>
    <a href="#">📁 Dokumentasi</a>
</div>

<div class="main-content">
    <h2>Dashboard Utama</h2>
    <p>Selamat datang di sistem WebGIS Dusun Boto. Pilih menu di sidebar untuk mulai menjelajahi informasi.</p>
</div>
@endsection
