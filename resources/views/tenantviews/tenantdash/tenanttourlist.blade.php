@extends('tenantviews.tenantlayout.tenantlayout')
@section('content')
<!-- Main Content -->
<div class="container">
    <h3 class="my-4">Tenants</h3>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show auto-dismiss">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
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
                    <th>Location</th>
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
                        <a href="#" class="btn btn-danger btn-sm delete-tourist-spot" data-id="{{ $touristSpot->id }}" data-toggle="modal" data-target="#deleteModal">Delete</a>
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
                    <form method="POST" action="{{ route('touristspot.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" autocomplete="name" required>
                        </div>
                        <div class="form-group">
                            <label for="location">Location:</label>
                            <input type="text" class="form-control" id="location" name="location" autocomplete="address-level2" required>
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
                    <form id="edit-form" method="POST" action="{{ route('touristspot.update', ['touristSpot' => $touristSpot->id]) }}">
                        @csrf
                        @method('PUT')
                        <!-- Display Validation Errors -->
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $touristSpot->name }}" required>
                        </div>

                        <div class="form-group">
                            <label for="location">Location:</label>
                            <input type="text" class="form-control" id="location" name="location" value="{{ $touristSpot->location }}" required>
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
            setTimeout(function() {
                $('.auto-dismiss').alert('close');
            }, 3000);

            // Rest of your existing JavaScript
            $('.edit-tourist-spot').on('click', function() {
                const id = $(this).data('id');
                $.get('/touristspot/' + id, function(data) {
                    $('#editModal-' + id + ' input[name="name"]').val(data.name);
                    $('#editModal-' + id + ' input[name="location"]').val(data.location);
                    $('#editModal-' + id + ' input[name="entry_fee"]').val(data.entry_fee);
                    $('#editModal-' + id + ' #edit-form').attr('action', '/touristspot/' + id);
                    $('#editModal-' + id).modal('show');
                }).fail(function() {
                    alert('Failed to load tourist spot data. Please try again.');
                });
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            $('.delete-tourist-spot').on('click', function(e) {
                e.preventDefault();

                const id = $(this).data('id');
                if (confirm('Are you sure you want to delete this tourist spot?')) {
                    $.get('/touristspot/' + id + '/delete', function() {
                        location.reload();
                    }).fail(function() {
                        alert('An error occurred while deleting the tourist spot.');
                    });
                }
            });
        });
    </script>
</div>
@endsection