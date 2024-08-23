@extends('tenantviews.tenantlayout.homelayout')

@section('content')
<style>
    .attraction-section {
        background-color: #f7f7f7;
        padding: 60px 0;
    }

    .attraction-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        background-color: #fff;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .attraction-card:hover {
        transform: scale(1.05);
        box-shadow: 0 0 25px rgba(0, 0, 0, 0.3);
    }

    .attraction-image {
        height: 300px;
        object-fit: cover;
        border-bottom: 2px solid #ddd;
    }

    .attraction-card-body {
        padding: 30px;
    }

    .attraction-card-body h3 {
        font-size: 1.5rem;
        font-weight: bold;
        margin-top: 0;
    }

    .attraction-card-body p {
        font-size: 18px;
        margin-bottom: 15px;
    }

    .attraction-card-body .icon {
        font-size: 24px;
        margin-right: 10px;
        color: #337ab7;
    }

    .attraction-card-body .location {
        color: #555;
    }

    .attraction-card-body .price {
        font-weight: bold;
        color: #d9534f;
    }
</style>

<section class="page-section attraction-section" id="home">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-heading text-uppercase">Boarding Houses in {{ $tenantName }}</h2>
            <h6 class="text-muted">
                Explore the diverse range of boarding houses in {{ $tenantName }}, where each location offers a unique blend of comfort and convenience. 
                Whether you're looking for a cozy room in a bustling neighborhood or a serene retreat away from the city center, 
                discover the perfect boarding house that matches your needs and lifestyle.
            </h6>
        </div>

        <div class="row">
            @foreach($touristSpots as $touristSpot)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card attraction-card">
                        <img src="/storage/visitor/image/{{ $touristSpot->image }}" class="card-img-top attraction-image" alt="{{ $touristSpot->name }}">
                        <div class="card-body attraction-card-body">
                            <h3 class="card-title text-center">{{ $touristSpot->name }}</h3>
                            <p class="card-text text-center">
                                <i class="fas fa-map-marker-alt icon"></i>
                                <span class="location">{{ $touristSpot->location }}</span>
                            </p>
                            <p class="card-text">Description: {{ $touristSpot->description }}</p>
                            <p class="card-text">
                                <i class="fas fa-clock icon"></i>
                                <span>Curfew Hours: {{ $touristSpot->opening_hours }}</span>
                            </p>
                            <p class="card-text">
                                <i class="fas fa-tag icon"></i>
                                <span>Category: {{ $touristSpot->category }}</span>
                            </p>
                            <p class="card-text">
                                <i class="fas fa-money-bill-alt icon"></i>
                                <span class="price">Monthly Rent: ₱{{ $touristSpot->entry_fee }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
