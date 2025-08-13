@extends('layouts.app')

@section('title', 'Input Rating - Toko Buku John Doe')

@section('content')
<div class="card">
    <h1>Input Rating Buku</h1>
    <p>Berikan rating untuk buku yang Anda beli atau pinjam (skala 1-10)</p>

    <form method="POST" action="{{ route('ratings.store') }}" id="ratingForm">
        @csrf
        
        <div class="form-group">
            <label for="author_id">Pilih Penulis:</label>
            <select name="author_id" id="author_id" class="form-control" required>
                <option value="">-- Pilih Penulis --</option>
                @foreach($authors as $author)
                    <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>
                        {{ $author->name }}
                    </option>
                @endforeach
            </select>
            @error('author_id')
                <small style="color: #dc3545;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="book_id">Pilih Buku:</label>
            <select name="book_id" id="book_id" class="form-control" required disabled>
                <option value="">-- Pilih penulis terlebih dahulu --</option>
            </select>
            @error('book_id')
                <small style="color: #dc3545;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="rating">Rating (1-10):</label>
            <select name="rating" id="rating" class="form-control" required>
                <option value="">-- Pilih Rating --</option>
                @for($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                        {{ $i }} - 
                        @if($i <= 2) Sangat Buruk
                        @elseif($i <= 4) Buruk
                        @elseif($i <= 6) Cukup
                        @elseif($i <= 8) Bagus
                        @else Sangat Bagus
                        @endif
                        @if($i == 10) (Sempurna) @endif
                    </option>
                @endfor
            </select>
            @error('rating')
                <small style="color: #dc3545;">{{ $message }}</small>
            @enderror
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-success" id="submitBtn" disabled>
                Submit Rating
            </button>
            <a href="{{ route('books.index') }}" class="btn" style="background-color: #6c757d; color: white;">
                Kembali ke Daftar Buku
            </a>
        </div>
    </form>
</div>


@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const authorSelect = document.getElementById('author_id');
    const bookSelect = document.getElementById('book_id');
    const ratingSelect = document.getElementById('rating');
    const submitBtn = document.getElementById('submitBtn');

    // Function to check if all fields are filled
    function checkFormValidity() {
        const authorFilled = authorSelect.value !== '';
        const bookFilled = bookSelect.value !== '';
        const ratingFilled = ratingSelect.value !== '';
        
        submitBtn.disabled = !(authorFilled && bookFilled && ratingFilled);
    }

    authorSelect.addEventListener('change', function() {
        const authorId = this.value;
        
        bookSelect.innerHTML = '<option value="">-- Loading... --</option>';
        bookSelect.disabled = true;
        
        if (authorId) {
            fetch(`/api/authors/${authorId}/books`)
                .then(response => response.json())
                .then(books => {
                    bookSelect.innerHTML = '<option value="">-- Pilih Buku --</option>';
                    
                    if (books.length > 0) {
                        books.forEach(book => {
                            const option = document.createElement('option');
                            option.value = book.id;
                            option.textContent = book.title;
                            
                            if ('{{ old("book_id") }}' == book.id) {
                                option.selected = true;
                            }
                            
                            bookSelect.appendChild(option);
                        });
                        bookSelect.disabled = false;
                    } else {
                        bookSelect.innerHTML = '<option value="">-- Tidak ada buku dari penulis ini --</option>';
                    }
                    
                    checkFormValidity();
                })
                .catch(error => {
                    console.error('Error fetching books:', error);
                    bookSelect.innerHTML = '<option value="">-- Error loading books --</option>';
                    checkFormValidity();
                });
        } else {
            bookSelect.innerHTML = '<option value="">-- Pilih penulis terlebih dahulu --</option>';
            bookSelect.disabled = true;
            checkFormValidity();
        }
    });

    bookSelect.addEventListener('change', checkFormValidity);
    ratingSelect.addEventListener('change', checkFormValidity);

    @if(old('author_id'))
        authorSelect.dispatchEvent(new Event('change'));
    @endif

    // Initial form validity check
    checkFormValidity();
});
</script>
@endsection
