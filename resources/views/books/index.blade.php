@extends('layouts.app')

@section('title', 'Books List')

@section('content')
<div class="card">
    <h1>Daftar Buku</h1>
    <p>Menampilkan buku dengan rating rata-rata tertinggi</p>

    <!-- Filters -->
    <form method="GET" action="{{ route('books.index') }}" class="filters">
        <div class="form-group">
            <label for="search">Cari Buku/Penulis:</label>
            <input type="text" id="search" name="search" class="form-control" 
                   value="{{ request('search') }}" placeholder="Masukkan judul buku atau nama penulis...">
        </div>
        
        <div class="form-group">
            <label for="per_page">Tampilkan:</label>
            <select name="per_page" id="per_page" class="form-control">
                @foreach([10,20,30,40,50,60,70,80,90,100] as $num)
                    <option value="{{ $num }}" {{ request('per_page') == $num ? 'selected' : '' }}>
                        {{ $num }} buku
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Cari</button>
            <a href="{{ route('books.index') }}" class="btn" style="background-color: #6c757d; color: white;">Reset</a>
        </div>
    </form>

    <div style="margin-bottom: 1rem; color: #666;">
        Menampilkan {{ $books->firstItem() ?? 0 }} - {{ $books->lastItem() ?? 0 }} 
        dari {{ number_format($books->total()) }} buku
        @if(request('search'))
            untuk pencarian "{{ request('search') }}"
        @endif
    </div>

    @if($books->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Buku</th>
                    <th>Penulis</th>
                    <th>Kategori</th>
                    <th>Rata-rata Rating</th>
                    <th>Jumlah Rating</th>
                </tr>
            </thead>
            <tbody>
                @foreach($books as $index => $book)
                <tr>
                    <td>{{ $books->firstItem() + $index }}</td>
                    <td>
                        <strong>{{ $book->title }}</strong>
                    </td>
                    <td>{{ $book->author->name ?? 'N/A' }}</td>
                    <td>{{ $book->category->name ?? 'N/A' }}</td>
                    <td>
                        @if($book->avg_rating)
                            <span style="color: #ffc107;">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= ($book->avg_rating / 2))
                                        ★
                                    @else
                                        ☆
                                    @endif
                                @endfor
                            </span>
                            <br>
                            <small>{{ number_format($book->avg_rating, 2) }}/10</small>
                        @else
                            <span style="color: #999;">Belum ada rating</span>
                        @endif
                    </td>
                    <td>
                        <span style="font-weight: bold;">{{ number_format($book->ratings_count) }}</span> voter
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination" style="display: flex; justify-content: center; gap: 5px; margin-top: 2rem;">
            {{ $books->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    @else
        <div style="text-align: center; padding: 3rem; color: #666;">
            <h3>Tidak ada buku ditemukan</h3>
            @if(request('search'))
                <p>Coba gunakan kata kunci lain atau <a href="{{ route('books.index') }}">lihat semua buku</a></p>
            @else
                <p>Belum ada data buku. Silakan jalankan seeder terlebih dahulu.</p>
            @endif
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
// Auto submit form when changing per_page
document.getElementById('per_page').addEventListener('change', function() {
    this.form.submit();
});
</script>
@endsection
