@extends('layouts.admin_parentLO')

@section('content')
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Tenants</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Tenants</li>
        </ol>

        <!-- Response Modal -->
        <div class="modal fade" id="responseModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Notification</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="responseMessage"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Existing Tenants Table -->
        <div class="container">
            <h3 class="my-4">Tenants</h3>
            <table id="tenants-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Tenant</th>
                        <th>Domain Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table rows will be populated dynamically -->
                </tbody>
            </table>
        </div>

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
            Add New Tenant
        </button>

        <!-- Add Tenant Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Enter Tenant Information</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf <!-- Add CSRF token field -->
                        <div class="form-group">
                            <label for="tenant_city">Tenant Cafe Name:</label>
                            <input type="text" class="form-control" id="tenant_city" placeholder="Enter Tenant Cafe Name">
                        </div>
                        <div class="form-group">
                            <label for="domain">Domain Name:</label>
                            <input type="text" class="form-control" id="domain" placeholder="Enter Domain Name">
                        </div>
                        <div class="form-group">
                            <label for="user_name">Name:</label>
                            <input type="text" class="form-control" id="user_name" placeholder="Enter User Name">
                        </div>
                        <div class="form-group">
                            <label for="user_email">Email:</label>
                            <input type="email" class="form-control" id="user_email" placeholder="Enter User Email">
                        </div>
                        <div class="form-group">
                            <label for="subscription_plan">Subscription Plan:</label>
                            <select class="form-control" id="subscription_plan">
                                <option value="Basic Plan">Basic Plan</option>
                                <option value="Standard Plan">Standard Plan</option>
                                <option value="Premium Plan">Premium Plan</option>
                            </select>
                        </div>
                        <div class="form-group" id="subscription_details">
                            <!-- Subscription details will be dynamically added here -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="execute()">Add Tenant</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Include SweetAlert for beautiful alerts -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

        <script>
        // Set up AJAX headers with CSRF token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function execute() {
    const tenantCity = document.getElementById('tenant_city').value;
    const domainName = document.getElementById('domain').value;
    const userName = document.getElementById('user_name').value;
    const userEmail = document.getElementById('user_email').value;
    const subscriptionPlan = document.getElementById('subscription_plan').value;
    const csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get CSRF token

    // Basic validation
    if (!tenantCity || !domainName || !userName || !userEmail) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Please fill in all required fields',
        });
        return;
    }

    // Show loading indicator
    Swal.fire({
        title: 'Processing',
        html: 'Creating tenant...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        type: 'POST',
        url: '/execute-tinker',
        data: {
            _token: csrfToken, // Include CSRF token
            tenant_city: tenantCity,
            domain: domainName,
            user_name: userName,
            user_email: userEmail,
            subscription_plan: subscriptionPlan 
        },
        success: function(response) {
            $('#exampleModal').modal('hide');
            
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message,
                    timer: 3000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message
                });
            }
        },
        error: function(xhr) {
            const errorMessage = xhr.responseJSON?.message || 'An error occurred while creating the tenant';
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage
            });
        }
    });
}

        $(document).ready(function() {
            // Fetch tenants data when the page loads
            fetchTenantsData();

            function fetchTenantsData() {
                $.ajax({
                    url: '/fetch-tenants',
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            // Populate the table with the fetched data
                            populateTable(response.data);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to fetch tenants: ' + response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to fetch tenants: ' + error
                        });
                    }
                });
            }

            function populateTable(data) {
                var tableBody = $('#tenants-table tbody');
                tableBody.empty(); // Clear existing rows
                
                data.forEach(function(tenant) {
                    var row = $('<tr>');
                    row.append($('<td>').text(tenant.id));
                    
                    var domainNames = '';
                    tenant.domains.forEach(function(domain) {
                        domainNames += domain.domain + '<br>';
                    });
                    row.append($('<td>').html(domainNames));
                    
                    // Add delete button with data-tenant-id attribute
                    var deleteBtn = $('<button>')
                        .text('Delete')
                        .addClass('btn btn-danger btn-sm delete-tenant')
                        .attr('data-tenant-id', tenant.id);
                    row.append($('<td>').append(deleteBtn));

                    tableBody.append(row);
                });
            }
        });

        $(document).on('click', '.delete-tenant', function() {
    var tenantId = $(this).data('tenant-id');
    var $button = $(this); // Store reference to the button
    
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
            const deleteSwal = Swal.fire({
                title: 'Deleting',
                html: 'Please wait...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '/delete-tenant/' + tenantId,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    deleteSwal.close(); // Close the loading dialog
                    
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            // Remove the row from the table immediately
                            $button.closest('tr').remove();
                            
                            // Optional: Refresh the entire table if needed
                            // fetchTenantsData();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Failed to delete tenant'
                        });
                    }
                },
                error: function(xhr) {
                    deleteSwal.close(); // Close the loading dialog
                    const errorMessage = xhr.responseJSON?.message || 'An error occurred while deleting the tenant';
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
        </script>
@endsection