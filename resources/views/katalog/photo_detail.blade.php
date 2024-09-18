@extends('layouts.katalog')

@section('content')
<div class="container" style="background-color: #fff; padding: 20px; border-radius: 8px;">
    <div class="row">
        <div class="col-md-12 d-flex">
            <div class="main-image" style="flex: 1  ;">
                <img src="{{ $thumbnailImage->file_name }}" alt="Main Thumbnail" class="img-fluid main-thumbnail">

                <div class="photo-thumbnails mt-2 d-flex flex-wrap">
                    @foreach($mainImages as $image)
                        <div class="photo-thumbnail mr-2">
                            <img src="{{ $image->file_name }}" alt="{{ $photo->name }}" class="img-fluid thumbnail-img">
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="photo-details" style="flex: 1; padding-left: 15px;">
                <h3>{{ $photo->name }}</h3>
                <p>{{ $photo->description }}</p>

                <div class="variasi-container mt-2">
                    @foreach ($variasi as $item)
                        <span class="variasi-item">{{ $item->name }}</span>
                    @endforeach
                </div>
                <p class="mt-2">
                    <a href="{{ $photo->link_url }}" target="_blank" rel="noopener noreferrer">
                        {{ $photo->link_url }}
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
