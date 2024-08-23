@extends('layouts.admin_parentLO')

@section('content')
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Tenants</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Tenants</li>
        </ol>

        <!-- Existing Tenants Table -->
        <div class="container">
            <h3 class="my-4">Tenants</h3>
            <table id="tenants-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Tenant</th>
                        <th>Domain Name</th>
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

        <!-- Modal -->
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
                        <div class="form-group">
                            <label for="tenant_city">Tenant City:</label>
                            <input type="text" class="form-control" id="tenant_city" placeholder="Enter Tenant City">
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

        <!-- Remove integrity and crossorigin attributes from script tags -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

        <script>
        function execute() {
            const tenantCity = document.getElementById('tenant_city').value;
            const domainName = document.getElementById('domain').value;
            const userName = document.getElementById('user_name').value;
            const userEmail = document.getElementById('user_email').value;
            const subscriptionPlan = document.getElementById('subscription_plan').value;

            // Retrieve CSRF token from meta tag
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            let description = '';
            let monthlyPrice = '';

            // Set description and monthly price based on selected plan type
            switch (subscriptionPlan) {
                case 'Basic Plan':
                    description = 'Manage up to 10 boarding houses';
                    monthlyPrice = '$19.99';
                    break;
                case 'Standard Plan':
                    description = 'Manage up to 50 boarding houses';
                    monthlyPrice = '$49.99';
                    break;
                case 'Premium Plan':
                    description = 'Manage unlimited boarding houses';
                    monthlyPrice = '$99.99';
                    break;
                default:
                    // Set default description and monthly price for unknown plan types
                    description = 'Plan details not available';
                    monthlyPrice = 'Price not available';
            }

            // Update the subscription details in the modal
            const subscriptionDetailsDiv = document.getElementById('subscription_details');
            subscriptionDetailsDiv.innerHTML = `
                <label>Description:</label>
                <p>${description}</p>
                <label>Monthly Price:</label>
                <p>${monthlyPrice}</p>
            `;

            $.ajax({
                type: 'POST',
                url: '/execute-tinker',
                data: {
                    _token: token,
                    tenant_city: tenantCity,
                    domain: domainName,
                    user_name: userName,
                    user_email: userEmail,
                    subscription_plan: subscriptionPlan 
                },
                success: function(response) {
                    $('#exampleModal').modal('hide');
                    $('#responseMessage').text(response.message);
                    $('#responseModal').modal('show');

                    var autoCloseTimer = setTimeout(function() {
                        $('#responseModal').modal('hide');
                    }, 5000);

                    $('#responseModal').on('hidden.bs.modal', function (e) {
                        clearTimeout(autoCloseTimer);
                    });

                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
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
                            // Handle error
                            console.error('Error fetching tenants data: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching tenants data: ' + error);
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
                    var deleteBtn = $('<button>').text('Delete').addClass('btn btn-danger btn-sm delete-tenant')
                                                .attr('data-tenant-id', tenant.id);
                    row.append($('<td>').append(deleteBtn));

                    tableBody.append(row);
                });
            }
        });

        $(document).on('click', '.delete-tenant', function() {
            var tenantId = $(this).data('tenant-id');
        
            if (confirm('Are you sure you want to delete this tenant?')) {
                $.ajax({
                    url: '/delete-tenant/' + tenantId,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            fetchTenantsData(); // Refresh table after deletion
                        } else {
                            alert('Failed to delete tenant: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error deleting tenant: ' + error);
                    }
                });
            }
        });
        </script>
@endsection
