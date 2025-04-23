@extends('layouts.admin_parentLO')

@section('content')
<!-- Include SweetAlert for beautiful alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>




<!-- Table for Potential Clients -->
<div class="container mb-4">
    <h3 class="my-4">Potential Clients</h3>

    <div class="container mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Search form -->
        <form id="client-search-form" class="form-inline">
            <div class="input-group">
                <input type="text" class="form-control form-control-sm" 
                       style="max-width: 200px;" id="client-search-input" 
                       placeholder="Search clients...">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary btn-sm" 
                            style="font-size: 0.8rem; padding: 0.25rem 0.5rem;">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Cafe</th>
                <th>Payment Method</th>
                <th>Type of Plan</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($potentialClients as $client)
            <tr data-id="{{ $client->id }}">
                <td>{{ $client->name }}</td>
                <td>{{ $client->email }}</td>
                <td>{{ $client->city }}</td>
                <td>{{ $client->payment_method }}</td>
                <td>{{ $client->plan_type }}</td>
                <td>
                    <button class="btn btn-success btn-sm approve-client-btn"
                        data-id="{{ $client->id }}"
                        data-name="{{ $client->name }}"
                        data-email="{{ $client->email }}"
                        data-city="{{ $client->city }}"
                        data-plan="{{ $client->plan_type }}">
                        Approve
                    </button>
                    <button class="btn btn-danger btn-sm delete-client-btn" data-id="{{ $client->id }}">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    $(document).ready(function() {
      // Handle approve button click
$(document).on('click', '.approve-client-btn', function() {
    const clientId = $(this).data('id');
    const clientName = $(this).data('name');
    const clientEmail = $(this).data('email');
    const clientCity = $(this).data('city');
    const clientPlan = $(this).data('plan');
    const $row = $(this).closest('tr');

    // First confirmation dialog
    Swal.fire({
        title: 'Approve Client',
        text: `Are you sure you want to approve ${clientName} (${clientEmail})?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, approve!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show domain input dialog
            Swal.fire({
                title: 'Assign Domain',
                html: `
                    <p>Please enter the domain for ${clientCity}:</p>
                    <input type="text" id="domainInput" class="swal2-input" placeholder="example.com">
                    <p class="text-muted small mt-2">Include the full domain (e.g., mysite.example.com)</p>
                `,
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Assign Domain',
                cancelButtonText: 'Cancel',
                focusConfirm: false,
                preConfirm: () => {
                    const domain = Swal.getPopup().querySelector('#domainInput').value.trim();
                    if (!domain) {
                        Swal.showValidationMessage('Please enter a domain');
                        return false;
                    }
                    // Basic domain validation
                    if (!/^([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,}$/i.test(domain)) {
                        Swal.showValidationMessage('Please enter a valid domain');
                        return false;
                    }
                    return domain;
                }
            }).then((domainResult) => {
                if (domainResult.isConfirmed) {
                    const assignedDomain = domainResult.value;

                    // Show loading indicator
                    const approveSwal = Swal.fire({
                        title: 'Processing Approval',
                        html: `Creating tenant for ${clientName}...`,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Make the AJAX call to create tenant
                     // First AJAX call to create tenant
                     $.ajax({
                        type: 'POST',
                        url: '/execute-tinker',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            tenant_city: clientCity,
                            domain: assignedDomain,
                            user_name: clientName,
                            user_email: clientEmail,
                            subscription_plan: clientPlan
                        },
                        success: function(response) {
                            if (response.success) {
                                // If tenant creation successful, delete the potential client
                                $.ajax({
                                    type: 'DELETE',
                                    url: `/admin/potential-clients/${clientId}`,
                                    data: {
                                        _token: $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function(deleteResponse) {
                                        approveSwal.close();
                                        
                                        if (deleteResponse.success) {
                                            // Log all details to console
                                            console.log('Tenant Created and Client Removed:', {
                                                id: clientId,
                                                name: clientName,
                                                email: clientEmail,
                                                city: clientCity,
                                                plan: clientPlan,
                                                domain: assignedDomain
                                            });

                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Success',
                                                text: 'Client approved and tenant created successfully!',
                                                timer: 3000,
                                                showConfirmButton: false
                                            }).then(() => {
                                                // Remove the row from the table
                                                $row.fadeOut(300, function() {
                                                    $(this).remove();
                                                });
                                            });
                                        } else {
                                            approveSwal.close();
                                            Swal.fire({
                                                icon: 'warning',
                                                title: 'Partial Success',
                                                html: `Tenant created but failed to remove potential client.<br>${deleteResponse.message}`,
                                            });
                                        }
                                    },
                                    error: function(xhr) {
                                        approveSwal.close();
                                        const errorMessage = xhr.responseJSON?.message || 'An error occurred while deleting the potential client';
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Partial Success',
                                            html: `Tenant created but failed to remove potential client.<br>${errorMessage}`,
                                        });
                                    }
                                });
                            } else {
                                approveSwal.close();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr) {
                            approveSwal.close();
                            const errorMessage = xhr.responseJSON?.message || 'An error occurred while creating the tenant';
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMessage
                            });
                        }
                    });
                }
            });
        }
    });
});
        // Handle delete button click
        $(document).on('click', '.delete-client-btn', function() {
            const clientId = $(this).data('id');
            const $row = $(this).closest('tr');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading indicator
                    const deleteSwal = Swal.fire({
                        title: 'Deleting',
                        html: 'Please wait...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Send DELETE request
                    $.ajax({
                        url: `/admin/potential-clients/${clientId}`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            deleteSwal.close();
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message || 'Client deleted successfully',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    $row.remove(); // Remove the row from table
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message || 'Failed to delete client'
                                });
                            }
                        },
                        error: function(xhr) {
                            deleteSwal.close();
                            const errorMessage = xhr.responseJSON?.message || 'An error occurred while deleting the client';
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMessage
                            });
                        }
                    });
                }
            });
        });
    });


    $(document).ready(function() {
    // Search form handler
    $('#client-search-form').on('submit', function(e) {
        e.preventDefault();
        const searchTerm = $('#client-search-input').val().toLowerCase();
        filterClientTable(searchTerm);
    });

    // Real-time search (optional)
    $('#client-search-input').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase();
        filterClientTable(searchTerm);
    });

    function filterClientTable(searchTerm) {
        $('table tbody tr').each(function() {
            const $row = $(this);
            const name = $row.find('td:eq(0)').text().toLowerCase();
            const email = $row.find('td:eq(1)').text().toLowerCase();
            const cafe = $row.find('td:eq(2)').text().toLowerCase();
            const paymentMethod = $row.find('td:eq(3)').text().toLowerCase();
            const planType = $row.find('td:eq(4)').text().toLowerCase();
            
            if (name.includes(searchTerm) || 
                email.includes(searchTerm) || 
                cafe.includes(searchTerm) || 
                paymentMethod.includes(searchTerm) || 
                planType.includes(searchTerm)) {
                $row.show();
            } else {
                $row.hide();
            }
        });
    }
});
</script>

@endsection