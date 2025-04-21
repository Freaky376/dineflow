@extends('tenantviews.tenantlayout.tenantlayout')
@section('content')
<!-- Main Content -->
<div class="container">
    <h3 class="my-4">Tenants</h3>

    <!-- Include SweetAlert for beautiful alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Alert Messages will be handled by SweetAlert -->
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
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
                text: '{{ session('error') }}'
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
                        <a href="#" class="btn btn-primary btn-sm edit-tourist-spot" data-id="{{ $touristSpot->id }}" data-toggle="modal" data-target="#editModal">Edit</a>
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
    <select class="form-control" name="location" id="location" required>
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
    <select class="form-control" name="location" id="location" required>
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

    <!-- Ensure jQuery is included -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Ensure Bootstrap JavaScript is included -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Handle form submissions with SweetAlert
            $('#addForm').on('submit', function(e) {
                e.preventDefault();
                const form = this;
                
                Swal.fire({
                    title: 'Processing',
                    html: 'Adding new item...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: $(form).attr('action'),
                    type: 'POST',
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message || 'Item added successfully',
                            timer: 3000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        const errorMessage = xhr.responseJSON?.message || 'An error occurred while adding the item';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage
                        });
                    }
                });
            });

            // Edit tourist spot handling
            $('.edit-tourist-spot').on('click', function() {
                const id = $(this).data('id');
                $.get('/touristspot/' + id, function(data) {
                    $('#editModal-' + id + ' input[name="name"]').val(data.name);
                    $('#editModal-' + id + ' input[name="location"]').val(data.location);
                    $('#editModal-' + id + ' input[name="entry_fee"]').val(data.entry_fee);
                    $('#editModal-' + id + ' #edit-form').attr('action', '/touristspot/' + id);
                    $('#editModal-' + id).modal('show');
                }).fail(function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load tourist spot data. Please try again.'
                    });
                });
            });

            // Delete tourist spot handling
            $('.delete-tourist-spot').on('click', function(e) {
    e.preventDefault();
    const id = $(this).data('id');
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
            Swal.fire({
                title: 'Deleting',
                html: 'Please wait...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Change to GET request if your server expects it
            $.ajax({
                url: '/touristspot/' + id + '/delete',
                type: 'GET', // Changed from DELETE to GET to match your route
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: response.message || 'Item deleted successfully',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        $row.fadeOut(400, function() {
                            $(this).remove();
                        });
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'An error occurred while deleting the item'
                    });
                }
            });
        }
    });
});

            // Edit form submissions
            $('[id^="edit-form-"]').on('submit', function(e) {
                e.preventDefault();
                const form = this;
                const id = $(form).attr('id').split('-')[2];
                
                Swal.fire({
                    title: 'Updating',
                    html: 'Saving changes...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: $(form).attr('action'),
                    type: 'POST',
                    data: $(form).serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message || 'Changes saved successfully',
                            timer: 3000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        const errorMessage = xhr.responseJSON?.message || 'An error occurred while saving changes';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage
                        });
                    }
                });
            });
        });
    </script>
</div>
@endsection