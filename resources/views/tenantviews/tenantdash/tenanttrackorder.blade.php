@extends('tenantviews.tenantlayout.tenantlayout')

@section('content')
<!-- Main Content -->
<div class="col-md-12">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Order Tracking Dashboard</h5>
                <form method="GET" action="{{ route('your.route.name') }}" class="form-inline">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" placeholder="Search orders..." name="search" value="{{ request('search') }}">
                        <button class="btn btn-light" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="card-title">Welcome, {{ $tenantName }}!</h5>
                    <p class="card-text text-muted">Track and manage all customer orders</p>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th width="120px">Image</th>
                            <th>Item</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Qty</th>
                            <th>Type</th>
                            <th>Total</th>
                            <th width="120px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                        <tr>
                            <td>
                                @if($customer->touristspot && $customer->touristspot->image)
                                <img src="/storage/visitor/image/{{ $customer->touristspot->image }}" 
                                     alt="Item Image" 
                                     class="img-thumbnail" 
                                     style="width: 100px; height: auto; object-fit: cover;">
                                @else
                                <img src="/storage/visitor/image/default.jpg" 
                                     alt="Default Image" 
                                     class="img-thumbnail" 
                                     style="width: 100px; height: auto; object-fit: cover;">
                                @endif
                            </td>
                            <td class="align-middle">{{ $customer->touristspot->name ?? 'N/A' }}</td>
                            <td class="align-middle">{{ $customer->name }}</td>
                            <td class="align-middle">{{ $customer->phone ?? 'N/A' }}</td>
                            <td class="align-middle">{{ $customer->quantity }}</td>
                            <td class="align-middle">{{ ucfirst($customer->order_type) }}</td>
                            <td class="align-middle">₱{{ number_format($customer->total_price, 2) }}</td>
                            <td class="align-middle">
                                <button class="btn btn-sm btn-outline-primary view-orders" 
                                        data-name="{{ $customer->name }}">
                                    <i class="fas fa-history"></i> History
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-box-open fa-2x mb-2"></i>
                                    <p>No orders found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Order History Modal -->
<div class="modal fade" id="orderHistoryModal" tabindex="-1" role="dialog" aria-labelledby="orderHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="orderHistoryModalLabel">
                    <i class="fas fa-history"></i> Order History for <span id="customerName"></span>
                </h5>
                <div class="input-group input-group-sm ml-3" style="width: 250px;">
                    <input type="text" class="form-control" id="modalSearch" placeholder="Search in history...">
                    <div class="input-group-append">
                        <button class="btn btn-light" type="button" id="searchHistoryBtn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th width="150px">Date</th>
                                <th width="150px">Item</th>
                                <th width="60px">Image</th>
                                <th width="50px">Qty</th>
                                <th width="80px">Type</th>
                                <th width="90px">Total</th>
                            </tr>
                        </thead>
                        <tbody id="modalHistoryBody">
                            <!-- Will be populated by AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- jQuery (must be first) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    let currentCustomerData = []; // Store the loaded data for searching
    
    // View order history
    $(document).on('click', '.view-orders', function() {
        const name = $(this).data('name');
        console.log('Viewing history for:', name);
        
        // Set customer name in modal title
        $('#customerName').text(name);
        $('#modalSearch').val(''); // Clear search on modal open
        
        // Show loading state
        $('#modalHistoryBody').html(`
            <tr>
                <td colspan="6" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2 mb-0">Loading order history...</p>
                </td>
            </tr>
        `);
        
        // Show the modal
        $('#orderHistoryModal').modal('show');
        
        // Load data via AJAX
        $.ajax({
            url: `/orders/customer/${encodeURIComponent(name)}`,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                console.log('Order history data received:', data);
                currentCustomerData = data; // Store the data for searching
                renderModalTable(data);
            },
            error: function(xhr, status, error) {
                console.error('Error loading order history:', error);
                $('#modalHistoryBody').html(`
                    <tr>
                        <td colspan="6" class="text-center py-4 text-danger">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                            <p class="mb-0">Error loading order history. Please try again.</p>
                        </td>
                    </tr>
                `);
            }
        });
    });
    
    // Function to render the modal table
    function renderModalTable(data) {
        const historyBody = $('#modalHistoryBody');
        historyBody.empty();
        
        if (data.length === 0) {
            historyBody.append(`
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
                        <p class="mb-0">No order history found</p>
                    </td>
                </tr>
            `);
        } else {
            data.forEach(function(order) {
                const imageUrl = order.touristspot && order.touristspot.image 
                    ? '/storage/visitor/image/' + order.touristspot.image 
                    : '/storage/visitor/image/default.jpg';
                    
                const orderDate = new Date(order.created_at);
                const formattedDate = orderDate.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                
                historyBody.append(`
                    <tr>
                        <td class="align-middle">${formattedDate}</td>
                        <td class="align-middle">${order.touristspot ? order.touristspot.name : 'N/A'}</td>
                        <td class="align-middle">
                            <img src="${imageUrl}" 
                                 alt="Item Image" 
                                 class="img-thumbnail"
                                 style="width: 60px; height: 60px; object-fit: cover;">
                        </td>
                        <td class="align-middle">${order.quantity}</td>
                        <td class="align-middle">${order.order_type.charAt(0).toUpperCase() + order.order_type.slice(1)}</td>
                        <td class="align-middle">₱${parseFloat(order.total_price).toFixed(2)}</td>
                    </tr>
                `);
            });
        }
    }
    
    // Search functionality in modal
    $('#searchHistoryBtn, #modalSearch').on('keyup', function(e) {
        if (e.type === 'keyup' && e.key !== 'Enter') return;
        
        const searchTerm = $('#modalSearch').val().toLowerCase();
        if (!searchTerm) {
            renderModalTable(currentCustomerData);
            return;
        }
        
        const filteredData = currentCustomerData.filter(order => {
            return (
                (order.touristspot?.name?.toLowerCase().includes(searchTerm)) ||
                (order.order_type.toLowerCase().includes(searchTerm)) ||
                (order.quantity.toString().includes(searchTerm)) ||
                (order.total_price.toString().includes(searchTerm)) ||
                (new Date(order.created_at).toLocaleString().toLowerCase().includes(searchTerm))
            );
        });
        
        renderModalTable(filteredData);
    });
    
    // Force close modal when X or footer button clicked
    $(document).on('click', '#orderHistoryModal .close, #orderHistoryModal .btn-secondary', function() {
        $('#orderHistoryModal').modal('hide');
    });
});
</script>

<style>
    .card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    
    .table thead th {
        border-top: none;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .img-thumbnail {
        padding: 0;
        border: 1px solid #dee2e6;
    }
    
    .modal-lg {
        max-width: 800px;
    }
    
    .modal-header {
        border-radius: 0;
        border: none;
    }
    
    .btn-outline-primary {
        transition: all 0.3s;
    }
    
    .btn-outline-primary:hover {
        background-color: #4e73df;
        color: white;
    }
    
    /* Search input styling */
    .input-group-sm {
        max-width: 300px;
    }
    
    /* Modal search styling */
    #modalSearch {
        transition: all 0.3s;
    }
    
    #modalSearch:focus {
        box-shadow: none;
        border-color: #ced4da;
    }
</style>
@endsection