@extends('tenantviews.tenantlayout.homelayout')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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

    /* New styles for the order button and modal */
    .order-btn {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-weight: bold;
        display: block;
        margin: 20px auto 0;
        transition: background-color 0.3s;
    }

    .order-btn:hover {
        background-color: #218838;
    }

    .modal-header {
        background-color: #28a745;
        color: white;
    }

    .modal-title {
        font-weight: bold;
    }

    .close {
        color: white;
        opacity: 1;
    }

    /* Total price display */
    .total-price-display {
        font-size: 1.2rem;
        font-weight: bold;
        color: #d9534f;
        margin-top: 10px;
    }
</style>

<section class="page-section attraction-section" id="home">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-heading text-uppercase">Cafe Menu in {{ $tenantName }}</h2>
            <h6 class="text-muted">
                Explore the delightful menu of DineFlow Café in {{ $tenantName }}, where every dish is crafted to offer a unique blend of flavor and comfort.
                Whether you're craving a quick bite in a lively setting or a relaxing sip in a cozy corner,
                discover the perfect café experience that suits your taste and mood.
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
                            <i class="fas fa-tag icon"></i>
                            <span class="location">{{ $touristSpot->location }}</span>
                        </p>
                        <p style="text-align: center;" class="card-text">Description: {{ $touristSpot->description }}</p>
                        <p class="card-text" style="text-align: center;">
                            <i class="fas fa-money-bill-alt icon"></i>
                            <span class="price">Price: ₱{{ number_format($touristSpot->entry_fee, 2) }}</span>
                        </p>

                        <!-- Order Button -->
                        <button type="button" class="order-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#orderModal"
                            data-spot-id="{{ $touristSpot->id }}"
                            data-spot-name="{{ $touristSpot->name }}"
                            data-spot-price="{{ $touristSpot->entry_fee }}">
                            Order Now
                        </button>

                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Order Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderModalLabel">Place Your Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="orderForm" method="POST" action="{{ route('orders.store') }}">
                    @csrf
                    <input type="hidden" id="spotId" name="touristspot_id">
                    <div class="form-group">
                        <label for="spotName">Item:</label>
                        <input type="text" class="form-control" id="spotName" readonly>
                    </div>
                    <div class="form-group">
                        <label for="spotPrice">Unit Price:</label>
                        <input type="text" class="form-control" id="spotPrice" readonly>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity:</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="1" required>
                        <div class="total-price-display">Total: ₱<span id="displayTotalPrice">0.00</span></div>
                    </div>
                    <input type="hidden" id="total_price" name="total_price">
                    <div class="form-group">
                        <label for="customerName">Your Name:</label>
                        <input type="text" class="form-control" id="customerName" name="name" required placeholder="Enter your full name">
                    </div>
                    <div class="form-group">
                        <label for="customerPhone">Phone Number:</label>
                        <input type="tel" class="form-control" id="customerPhone" name="phone" required placeholder="Enter your phone number">
                    </div>
                    <div class="form-group">
                        <label for="orderType">Order Type:</label>
                        <select class="form-control" id="orderType" name="order_type" required>
                            <option value="Dine-in">Dine-in</option>
                            <option value="Takeaway">Takeaway</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submitOrder">Submit Order</button>
            </div>
        </div>
    </div>
</div>

<!-- Cafe Location Map -->
<section class="page-section bg-light" id="location">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="section-heading text-uppercase">Find Us</h2>
            <h3 class="section-subheading text-muted">Visit our {{ $tenantName }}</h3>
        </div>
        <div class="card shadow-lg border-0">
            <div class="card-body p-0">
                <div id="map" style="height: 450px; border-radius: 0 0 10px 10px;"></div>
            </div>
        </div>
    </div>
</section>

<!-- Leaflet Map Styles and Scripts -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const map = L.map('map').setView([14.5995, 120.9842], 14); // Default to Manila

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        L.marker([14.5995, 120.9842])
            .addTo(map)
            .bindPopup("<b>DineFlow Café</b><br>Manila, PH")
            .openPopup();

        // Geolocation if allowed
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(pos => {
                const lat = pos.coords.latitude;
                const lon = pos.coords.longitude;
                map.setView([lat, lon], 15);
                L.marker([lat, lon]).addTo(map).bindPopup("Here!").openPopup();
            });
        }
    });
</script>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Add SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // Initialize modal
        var orderModal = new bootstrap.Modal(document.getElementById('orderModal'));

        // When the order button is clicked, populate the modal with the spot data
        $('.order-btn').click(function() {
            var spotId = $(this).data('spot-id');
            var spotName = $(this).data('spot-name');
            var spotPrice = parseFloat($(this).data('spot-price'));

            $('#spotId').val(spotId);
            $('#spotName').val(spotName);
            $('#spotPrice').val('₱' + spotPrice.toFixed(2));

            // Calculate initial total price
            calculateTotalPrice(spotPrice, 1);
        });

        // When quantity changes, update the total price
        $('#quantity').on('input', function() {
            var quantity = parseInt($(this).val()) || 0;
            var unitPrice = parseFloat($('#spotPrice').val().replace('₱', '')) || 0;

            if (quantity < 1) {
                $(this).val(1);
                quantity = 1;
            }

            calculateTotalPrice(unitPrice, quantity);
        });

        // Submit order form with SweetAlert confirmation
        $('#submitOrder').click(function(e) {
            e.preventDefault();
            
            // Validate form
            if ($('#orderForm')[0].checkValidity()) {
                Swal.fire({
                    title: 'Confirm Order',
                    text: 'Are you sure you want to place this order?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, place order!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit the form
                        $('#orderForm').submit();
                    }
                });
            } else {
                // If form is invalid, show validation messages
                $('#orderForm')[0].reportValidity();
            }
        });

        // Handle form submission response
        @if(session('success'))
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonColor: '#28a745'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                title: 'Error!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonColor: '#dc3545'
            });
        @endif

        function calculateTotalPrice(unitPrice, quantity) {
            var totalPrice = unitPrice * quantity;
            $('#total_price').val(totalPrice.toFixed(2));
            $('#displayTotalPrice').text(totalPrice.toFixed(2));
        }
    });
</script>

<!-- Add this to your controller's store method to return success/error messages -->
@if(session()->has('success') || session()->has('error'))
    <script>
        $(document).ready(function() {
            @if(session('success'))
                Swal.fire({
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonColor: '#28a745'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    confirmButtonColor: '#dc3545'
                });
            @endif
        });
    </script>
@endif
@endsection