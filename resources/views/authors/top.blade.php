@extends('layouts.app')

@section('title', 'Top 10 Penulis Terpopuler - Toko Buku John Doe')

@section('content')
<div class="card">
    <h1>Top 10 Penulis Terpopuler</h1>
    <p>Berdasarkan jumlah voter dengan rating > 5</p>

    @if($authors->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 80px;">Peringkat</th>
                    <th>Nama Penulis</th>
                    <th>Jumlah Voter</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($authors as $index => $author)
                <tr>
                    <td>
                        <div style="text-align: center;">
                            @if($index == 0)
                                <span style="font-size: 1.5rem; color: #FFD700; font-weight: bold;">1</span>
                            @elseif($index == 1)
                                <span style="font-size: 1.5rem; color: #C0C0C0; font-weight: bold;">2</span>
                            @elseif($index == 2)
                                <span style="font-size: 1.5rem; color: #CD7F32; font-weight: bold;">3</span>
                            @else
                                <span style="font-size: 1.2rem; font-weight: bold;">{{ $index + 1 }}</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <strong style="font-size: 1.1rem;">{{ $author->name }}</strong>
                    </td>
                    <td>
                        <span style="font-weight: bold; color: #28a745; font-size: 1.1rem;">
                            {{ number_format($author->voters_count) }}
                        </span> 
                        <small style="color: #666;">voter (rating > 5)</small>
                    </td>
                    <td>
                        <a href="{{ route('books.index', ['search' => $author->name]) }}" 
                           class="btn btn-primary btn-sm">
                            Lihat Buku
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 2rem; padding: 1rem; background-color: #f8f9fa; border-radius: 8px;">
            <h4>Catatan:</h4>
            <ul style="margin: 0.5rem 0 0 1.5rem; color: #666;">
                <li>Hanya menghitung voter dengan rating lebih dari 5</li>
                <li>Satu orang dapat memberikan rating pada buku yang berbeda dari penulis yang sama</li>
                <li>Data diurutkan berdasarkan jumlah voter terbanyak</li>
            </ul>
        </div>
    @else
        <div style="text-align: center; padding: 3rem; color: #666;">
            <h3>Belum ada data penulis</h3>
            <p>Silakan jalankan seeder terlebih dahulu atau tambahkan rating pada buku.</p>
            <a href="{{ route('ratings.create') }}" class="btn btn-primary">
                Tambah Rating Sekarang
            </a>
        </div>
    @endif
</div>

<div class="card">
    <h3>Navigasi Cepat</h3>
    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <a href="{{ route('books.index') }}" class="btn btn-primary">
            Lihat Semua Buku
        </a>
        <a href="{{ route('ratings.create') }}" class="btn btn-success">
            Tambah Rating Baru
        </a>
    </div>
</div>
@endsection
