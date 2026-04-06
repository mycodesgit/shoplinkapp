@extends('admin.layouts.master')

@section('title')
    Shoplink || Users
@endsection

@section('body')
    <div class="row ">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-3 mb-4 d-none d-md-block">Users</h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-users"></i> Users
                                </h6>
                            </div>
                            <div class="card-body">
                                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modal-user">
                                    <i class="ti ti-plus"></i> Add New User
                                </button>
                                <div class="table-responsive mt-3 p-2">
                                    <table id="userTable" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>First Name</th>
                                                <th>Middle Initial</th>
                                                <th>Last Name</th>
                                                <th>Username</th>
                                                <th>Role</th>
                                                <th>Avatar</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-user">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus"></i> Add User
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <form class="form-horizontal" method="post" id="addUser">  
                        @csrf

                        <div class="form-group">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label>First Name:</label>
                                    <input type="text" name="fname" oninput="this.value = this.value.toUpperCase()" placeholder="Enter First Name" class="form-control">
                                </div>

                                <div class="col-md-4">
                                    <label>Middle Name:</label>
                                    <input type="text" name="mname" oninput="this.value = this.value.toUpperCase()" placeholder="Enter Middle Name" class="form-control">
                                </div>

                                <div class="col-md-4">
                                    <label>Last Name:</label>
                                    <input type="text" name="lname" oninput="this.value = this.value.toUpperCase()" placeholder="Enter Last Name" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label>Username:</label>
                                    <input type="text" id="username" name="username" placeholder="Enter Username" class="form-control">
                                </div>

                                <div class="col-md-4">
                                    <label>Password:</label>
                                    <input type="password" name="password" placeholder="Enter Password" class="form-control">
                                </div>

                                <div class="col-md-4">
                                    <label>Gender:</label>
                                    <select name="gender" class="form-control">
                                        <option value=""> --- Select ---</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3"> 
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label>Role:</label>
                                    <select class="form-control" name="role">
                                        <option value=""> --- Select Role --- </option>
                                        @if(Auth::user()->role=='Administrator')
                                            <option value="Administrator">Administrator</option>
                                        @endif
                                        <option value="Staff">Staff</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                        Close
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Save
                                    </button>
                                </div>
                            </div>
                        </div>   
                    </form>
                </div>
                
                <div class="modal-footer justify-content-between">
                    <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editInfoModal" tabindex="-1" role="dialog" aria-labelledby="editInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 60vw;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editInfoModalLabel">Edit User Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editInfoForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edituserId">
                        <div class="form-group">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="editfirstname">First Name</label>
                                    <input type="text" class="form-control" id="editfirstname" name="fname" oninput="this.value = this.value.toUpperCase()">
                                </div>
                                <div class="col-md-4">
                                    <label for="editmiddlename">Middle Name</label>
                                    <input type="text" class="form-control" id="editmiddlename" name="mname" oninput="this.value = this.value.toUpperCase()">
                                </div>
                                <div class="col-md-4">
                                    <label for="editlastname">Last Name</label>
                                    <input type="text" class="form-control" id="editlastname" name="lname" oninput="this.value = this.value.toUpperCase()">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="editusername">Username</label>
                                    <input type="text" class="form-control" id="editusername" name="username">
                                </div>
                                <div class="col-md-4">
                                    <label for="editgender">Gender</label>
                                    <select name="gender" class="form-control" id="editgender">
                                        <option value=""> --- Select ---</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="editrole">Role</label>
                                    <select name="role" class="form-control" id="editrole">
                                        <option value=""> --- Select ---</option>
                                        <option value="Administrator">Administrator</option>
                                        <option value="Staff">Staff</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editPasswordModal" tabindex="-1" role="dialog" aria-labelledby="editPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPasswordModalLabel">Edit Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editPasswordForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editPasswordId">
                        <div class="form-group">
                            <label for="editPasswordName">Enter New Password</label>
                            <input type="text" class="form-control" id="editPasswordName" name="password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editUstatusModal" tabindex="-1" role="dialog" aria-labelledby="editUstatusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUstatusModalLabel">Edit User Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editUstatusForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editUstatusId">
                        <div class="form-group">
                            <label for="editUstatusName">Select User Status</label>
                            <select name="ustatus" id="editUstatusName" class="form-control">
                                <option disabled selected> --Select-- </option>
                                <option value="1">Enabled</option>
                                <option value="2">Disabled</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var userReadRoute = "{{ route('user.show') }}";
        var userCreateRoute = "{{ route('user.store') }}";
        var userUpdateRoute = "{{ route('user.update', ['id' => ':id']) }}";
        var userDeleteRoute = "{{ route('user.destroy', ['id' => ':id']) }}";
        var userPassUpdateRoute = "{{ route('userUpdatePassword', ['id' => ':id']) }}";
        var userStatusUpdateRoute = "{{ route('userUpdateStatus', ['id' => ':id']) }}";
    </script>
@endsection