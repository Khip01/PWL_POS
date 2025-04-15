@extends('layouts.template')

{{-- @section('title', 'User Profile') --}}

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center position-relative">
                    <div class="rounded-circle mb-3 mx-auto" style="width: 150px; height: 150px; background-color: #ccc; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        @if($user->profile_picture)
                            <img src="{{ $user->profile_picture }}" alt="Profile Picture" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <span style="font-size: 24px; color: #fff;">N/A</span>
                        @endif
                    </div>
                    <button onclick="modalAction('{{ url('/profile/import') }}')" class="btn btn-primary btn-sm rounded-circle position-absolute" id="editProfileButton" style="top: 120px; right: 110px;">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <h4>{{ $user->nama }}</h4>
                    <p class="text-muted">{{ $user->username }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>User Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>User ID</th>
                            <td>{{ $user->user_id }}</td>
                        </tr>
                        <tr>
                            <th>Level ID</th>
                            <td>{{ $user->level_id }}</td>
                        </tr>
                        <tr>
                            <th>Username</th>
                            <td>{{ $user->username }}</td>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <td>{{ $user->nama }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div> 

<!-- Modal for Edit Profile -->
{{-- <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProfileForm">
                    <div class="mb-3">
                        <label for="profilePicture" class="form-label">Profile Picture</label>
                        <input type="file" class="form-control" id="profilePicture" name="profile_picture">
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ $user->nama }}">
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('editProfileButton').addEventListener('click', function () {
        var editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));
        editProfileModal.show();
    });

    document.getElementById('editProfileForm').addEventListener('submit', function (e) {
        e.preventDefault();
        // Add AJAX request to save profile changes
        alert('Profile updated successfully!');
    });
</script> --}}
@endsection

@push('js')
<script>
    function modalAction(url = ''){ 
        $('#myModal').load(url,function(){ 
            $('#myModal').modal('show'); 
        }); 
    } 
</script>
@endpush