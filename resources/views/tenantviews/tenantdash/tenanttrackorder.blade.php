@extends('tenantviews.tenantlayout.tenantlayout')

@section('content')
<style>
.order-img {
    width: 100px;
    height: auto;
    object-fit: cover;
}
.modal-lg {
    max-width: 850px;
}
</style>
<div class="col-md-12">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Order Tracking Dashboard</h5>
            </div>
        </div>
        <div class="card-body">

            <!-- 🔍 Simple Search Form -->
            <form method="GET" action="{{ route('orders.search') }}" class="form-inline mb-3">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" name="search" placeholder="Search orders..." value="{{ request('search') }}">
                    <button class="btn btn-light" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>

            <h5 class="card-title">Welcome, {{ $tenantName }}!</h5>
            <p class="card-text text-muted">Track and manage all customer orders</p>

            <!-- 🧾 Orders Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Image</th>
                            <th>Item</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Qty</th>
                            <th>Type</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                        <tr>
                            <td>
                                <img src="/storage/visitor/image/{{ $customer->touristspot->image ?? 'default.jpg' }}" 
                                     alt="Image" class="order-img">
                            </td>
                            <td>{{ $customer->touristspot->name ?? 'N/A' }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->phone ?? 'N/A' }}</td>
                            <td>{{ $customer->quantity }}</td>
                            <td>{{ ucfirst($customer->order_type) }}</td>
                            <td>₱{{ number_format($customer->total_price, 2) }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary view-orders" data-name="{{ $customer->name }}">
                                    <i class="fas fa-history"></i> History
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">No orders found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- 📦 Order History Modal -->
<div class="modal fade" id="orderHistoryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-history"></i> Order History for <span id="customerName"></span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-0">
                <div class="input-group input-group-sm p-3">
                    <input type="text" id="modalSearch" class="form-control" placeholder="Search history...">
                    <div class="input-group-append">
                        <button class="btn btn-light" id="searchHistoryBtn"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Date</th><th>Item</th><th>Image</th><th>Qty</th><th>Type</th><th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="modalHistoryBody"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(function() {
    let currentCustomerData = [];

    // Open modal
    $(document).on('click', '.view-orders', function() {
        const name = $(this).data('name');
        $('#customerName').text(name);
        $('#modalSearch').val('');
        $('#modalHistoryBody').html(`<tr><td colspan="6" class="text-center">Loading...</td></tr>`);
        $('#orderHistoryModal').modal('show');

        $.get(`/orders/customer/${encodeURIComponent(name)}`, function(data) {
            currentCustomerData = data;
            renderModalTable(data);
        }).fail(() => {
            $('#modalHistoryBody').html(`<tr><td colspan="6" class="text-center text-danger">Error loading history</td></tr>`);
        });
    });

    // Render data
    function renderModalTable(data) {
        const body = $('#modalHistoryBody');
        body.empty();

        if (!data.length) {
            body.append(`<tr><td colspan="6" class="text-center text-muted">No order history found</td></tr>`);
            return;
        }

        data.forEach(order => {
            const date = new Date(order.created_at).toLocaleString('en-US');
            const img = order.touristspot?.image || 'default.jpg';
            const item = order.touristspot?.name || 'N/A';
            body.append(`
                <tr>
                    <td>${date}</td>
                    <td>${item}</td>
                    <td><img src="/storage/visitor/image/${img}" class="order-img"></td>
                    <td>${order.quantity}</td>
                    <td>${order.order_type}</td>
                    <td>₱${parseFloat(order.total_price).toFixed(2)}</td>
                </tr>
            `);
        });
    }

    // Filter
    $('#searchHistoryBtn, #modalSearch').on('keyup click', function(e) {
        if (e.type === 'keyup' && e.key !== 'Enter') return;
        const term = $('#modalSearch').val().toLowerCase();
        if (!term) return renderModalTable(currentCustomerData);

        const filtered = currentCustomerData.filter(o =>
            o.touristspot?.name?.toLowerCase().includes(term) ||
            o.order_type.toLowerCase().includes(term) ||
            o.quantity.toString().includes(term) ||
            o.total_price.toString().includes(term) ||
            new Date(o.created_at).toLocaleString().toLowerCase().includes(term)
        );
        renderModalTable(filtered);
    });

    // Force close modal
    $('#orderHistoryModal .close, #orderHistoryModal .btn-secondary').on('click', function() {
        $('#orderHistoryModal').modal('hide');
    });
});
</script>
@endsection
