@extends('admin.layouts.master') {{-- এখানে তোমার layout ফাইল include করো --}}
@section('admin_content')
<div class="container mt-4">
    <h3 class="mb-4">Clients</h3>
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#clientModal" id="addClientBtn">+ Add Client</button>

    <table class="table table-bordered" id="clientsTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be loaded via JS -->
        </tbody>
    </table>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="clientModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <form id="clientForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Client Info</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
          <input type="hidden" id="client_id">
          <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control" id="name" required>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" id="email" required>
          </div>
          <div class="form-group">
            <label>Gender</label>
            <select class="form-control" id="gender" required>
              <option value="">Select</option>
              <option>Male</option>
              <option>Female</option>
            </select>
          </div>
          <div class="form-group">
            <label>Phone</label>
            <input type="text" class="form-control" id="phone" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success" id="saveBtn">Save</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>

      </div>
    </form>
  </div>
</div>
@endsection


@push('admin_scripts')

<script>
    // const APP_URL = "{{ config('app.url') }}" +"/api";

    const token = localStorage.getItem('token');

    // 🔃 Load Clients
    function loadClients() {
        $.ajax({
            url: APP_URL + '/clients',
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token
            },
            success: function(res) {
                let result = res.data;

                let rows = '';
                result.forEach(client => {
                    rows += `
                    <tr>
                        <td>${client.name}</td>
                        <td>${client.email}</td>
                        <td>${client.gender}</td>
                        <td>${client.phone}</td>
                        <td>
                            <button class="btn btn-sm btn-info editBtn" data-id="${client.id}">Edit</button>
                            <button class="btn btn-sm btn-danger deleteBtn" data-id="${client.id}">Delete</button>
                        </td>
                    </tr>`;
                });
                $('#clientsTable tbody').html(rows);
            }
        });
    }




$(document).ready(function () {

    loadClients();

    $('#addClientBtn').on('click', function () {
        $('#clientForm')[0].reset();
        $('#client_id').val('');
    });

    // Submit form
    $('#clientForm').on('submit', function(e) {
        e.preventDefault();

        const id = $('#client_id').val();
        const data = {
            name: $('#name').val(),
            email: $('#email').val(),
            gender: $('#gender').val(),
            phone: $('#phone').val()
        };

        $.ajax({
            url: id ? `${API_URL}/${id}` : API_URL,
            method: id ? 'PUT' : 'POST',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            },
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function() {
                $('#clientModal').modal('hide');
                loadClients();
            },
            error: function(err) {
                alert('Error: ' + (err.responseJSON.message || 'Something went wrong'));
            }
        });
    });

    // ✏️ Edit
    $(document).on('click', '.editBtn', function() {
        const id = $(this).data('id');

        $.ajax({
            url: `${API_URL}/${id}`,
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token
            },
            success: function(client) {
                $('#client_id').val(client.id);
                $('#name').val(client.name);
                $('#email').val(client.email);
                $('#gender').val(client.gender);
                $('#phone').val(client.phone);
                $('#clientModal').modal('show');
            }
        });
    });

    // 🗑️ Delete
    $(document).on('click', '.deleteBtn', function() {
        if (!confirm('Are you sure?')) return;

        const id = $(this).data('id');
        $.ajax({
            url: `${API_URL}/${id}`,
            method: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + token
            },
            success: loadClients
        });
    });
});

</script>
@endpush