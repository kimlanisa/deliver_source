@extends('layouts.katalog')

@section('content')
    <div class="container" style="background-color: #fff; padding: 20px; border-radius: 8px;">
        <div class="row">
            <div class="col-md-12">
                <h3 >{{ $mainImage->name }}</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 d-flex">
                <div class="main-image" style="flex: 1;">
                    <img src="{{ $mainImage->thumbnail }}" alt="Main Thumbnail" class="img-fluid" style="width: 100%;">

                    <div class="photo-thumbnail mt-4">
                        <img src="{{ asset($photo->thumbnail) }}" alt="{{ $photo->title }}" class="img-fluid"
                            style="width: 50%;">
                    </div>
                </div>

                <div class="photo-details" style="flex: 1; padding-left: 15px;">
                    <h3>{{ $photo->name }}</h3>
                    <p>{{ $photo->description }}</p>

                    @php
                        $variations = explode(',', $photo->variasi);
                    @endphp
                    <div class="variasi-container mt-4">
                        @foreach ($variations as $variation)
                            <span class="variasi-item">{{ trim($variation) }}</span>
                        @endforeach
                    </div>
                    <p class="mt-4">
                        <a href="{{ $photo->link_url }}" target="_blank" rel="noopener noreferrer">
                            {{ $photo->link_url }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
