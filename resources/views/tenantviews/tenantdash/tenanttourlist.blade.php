@extends('tenantviews.tenantlayout.tenantlayout')
@section('content')
<!-- Main Content -->
<div class="container">
    <h3 class="my-4">Tenants</h3>

    <!-- Alert Messages will be handled by SweetAlert -->
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('
            success ') }}',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('
            error ') }}'
        });
    </script>
    @endif

    <!-- Add search form -->
    <form method="GET" action="{{ route('tenantlist') }}" class="form-inline float-right">
        <div class="input-group">
            <input type="text" class="form-control form-control-sm" style="max-width: 200px;" name="search" placeholder="Search" value="{{ request('search') }}">
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary btn-sm" style="font-size: 0.8rem; padding: 0.25rem 0.5rem;">Search</button>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($touristSpots as $touristSpot)
                <tr>
                    <td>{{ $touristSpot->name }}</td>
                    <td>{{ $touristSpot->location }}</td>
                    <td>{{ $touristSpot->description }}</td>
                    <td>{{ $touristSpot->entry_fee }}</td>
                    <td><img src="/storage/visitor/image/{{ $touristSpot->image }}" alt="Tourist Image" style="width: 100px; height: auto;"></td>
                    <td>
                        <a href="#" class="btn btn-primary btn-sm edit-tourist-spot" data-id="{{ $touristSpot->id }}" data-toggle="modal" data-target="#editModal-{{ $touristSpot->id }}">Edit</a>
                        <a href="#" class="btn btn-danger btn-sm delete-tourist-spot" data-id="{{ $touristSpot->id }}">Delete</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#addTouristSpotModal">Add Menu Item</button>
        </div>
    </div>

    <!-- Add Tourist Spot Modal -->
    <div class="modal fade" id="addTouristSpotModal" tabindex="-1" role="dialog" aria-labelledby="addTouristSpotModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTouristSpotModalLabel">Add Menu Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form for adding tourist spot -->
                    <form id="addForm" method="POST" action="{{ route('touristspot.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" autocomplete="name" required>
                        </div>
                        <div class="form-group">
                            <label for="location">Category:</label>
                            <select class="form-control" id="location" name="location" required>
                                <option value="">Select a category</option>
                                <option value="Appetizers">Appetizers</option>
                                <option value="Main Courses">Main Courses</option>
                                <option value="Desserts">Desserts</option>
                                <option value="Beverages">Beverages</option>
                                <option value="Specials">Specials</option>
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea class="form-control" id="description" name="description" rows="3" autocomplete="off"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="entry_fee">Price:</label>
                            <input type="text" class="form-control" id="entry_fee" name="entry_fee" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <label for="image">Image:</label>
                            <input type="file" class="form-control" id="image" name="image" autocomplete="off">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modals -->
    @foreach ($touristSpots as $touristSpot)
    <!-- Edit Modal for Tourist Spot -->
    <div class="modal fade" id="editModal-{{ $touristSpot->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel-{{ $touristSpot->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel-{{ $touristSpot->id }}">Edit Tourist Spot</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form for editing tourist spot -->
                    <form id="edit-form-{{ $touristSpot->id }}" method="POST" action="{{ route('touristspot.update', ['touristSpot' => $touristSpot->id]) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $touristSpot->name }}" required>
                        </div>

                        <div class="form-group">
                            <label for="location">Category:</label>
                            <select class="form-control" id="location" name="location" required>
                                <option value="">Select a category</option>
                                <option value="Appetizers" {{ $touristSpot->category == 'Appetizers' ? 'selected' : '' }}>Appetizers</option>
                                <option value="Main Courses" {{ $touristSpot->category == 'Main Courses' ? 'selected' : '' }}>Main Courses</option>
                                <option value="Desserts" {{ $touristSpot->category == 'Desserts' ? 'selected' : '' }}>Desserts</option>
                                <option value="Beverages" {{ $touristSpot->category == 'Beverages' ? 'selected' : '' }}>Beverages</option>
                                <option value="Specials" {{ $touristSpot->category == 'Specials' ? 'selected' : '' }}>Specials</option>
                            </select>
                        </div>


                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea class="form-control" id="description" name="description">{{ $touristSpot->description }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="entry_fee">Price:</label>
                            <input type="number" class="form-control" id="entry_fee" name="entry_fee" value="{{ $touristSpot->entry_fee }}">
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Add these right before the closing </body> tag -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    // Handle Add User form submission
    $('#addUserForm').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        
        // Disable submit button to prevent multiple clicks
        submitBtn.prop('disabled', true);
        
        // Show loading indicator
        Swal.fire({
            title: 'Creating User Account',
            html: 'Please wait while we set up the new user...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Submit form via AJAX
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                // Close loading indicator
                Swal.close();
                
                // Show success message with credentials info
                Swal.fire({
                    icon: 'success',
                    title: 'User Created Successfully!',
                    html: `
                        <div class="text-start">
                            <p><strong>Username:</strong> ${response.username}</p>
                            <p><strong>Email:</strong> ${response.email}</p>
                            <div class="alert alert-info mt-3">
                                <strong>Credentials have been sent to the user's email.</strong>
                            </div>
                        </div>
                    `,
                    confirmButtonText: 'Return to Dashboard',
                    confirmButtonColor: '#3085d6'
                }).then((result) => {
                    // Reset form and close modal
                    form[0].reset();
                    $('#addUserModal').modal('hide');
                    
                    // Redirect to dashboard
                    window.location.href = response.redirect;
                });
            },
            error: function(xhr) {
                // Enable submit button
                submitBtn.prop('disabled', false);
                
                // Close loading indicator
                Swal.close();
                
                // Show error message
                let errorMessage = 'An error occurred while creating the user';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: errorMessage,
                    confirmButtonText: 'Try Again'
                });
            }
        });
    });

    // Reset form when modal is closed
    $('#addUserModal').on('hidden.bs.modal', function() {
        $('#addUserForm')[0].reset();
        $('#addUserForm button[type="submit"]').prop('disabled', false);
    });
});
</script>
</div>
@endsection