@extends('layouts.admin_parentLO')

@section('content')

<!-- Table for Potential Clients -->
<div class="container mb-4">
    <h3 class="my-4">Potential Clients</h3>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>City</th>
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
                    <button class="btn btn-danger btn-sm delete-client-btn">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Potential Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this potential client?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-btn">Delete</button>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


<script>
    $(document).ready(function() {
        var clientId;

        // Store the client ID when the delete button is clicked
        $(document).on('click', '.delete-client-btn', function() {
            clientId = $(this).closest('tr').data('id');
        });

        // Handle the delete confirmation button click event
        $(document).on('click', '#confirm-delete-btn', function() {
            // Send a DELETE request to the server to delete the potential client
            axios({
                url: `/admin/potential-clients/${clientId}`,
                method: 'delete',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            .then(function(response) {
                // Close the modal
                $('#deleteModal').modal('hide');
                // Reload the table to show the updated data
                location.reload();
            })
            .catch(function(error) {
                // Handle the error response
                console.log(error.responseText);
            });
        });
    });
</script>

@endsection