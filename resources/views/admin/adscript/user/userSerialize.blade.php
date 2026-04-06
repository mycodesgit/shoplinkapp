<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right"
    };
    $(document).ready(function() {
       $('#addUser').submit(function(event) {
            event.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: userCreateRoute,
                type: "POST",
                data: formData, 
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        console.log(response);
                        $(document).trigger('userAdded');
                        $('#modal-user').modal('hide');
                        $('#addUser')[0].reset();
                    } else {
                        toastr.error(response.message);
                        console.log(response);
                    }
                },
                error: function(xhr) {
                    var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'An error occurred';
                    toastr.error(errorMessage);
                    console.error(xhr.responseText);
                }
            });
        });


        var dataTable = $('#userTable').DataTable({
            "ajax": {
                "url": userReadRoute,
                "type": "GET",
            },
            destroy: true,
            info: true,
            responsive: true,
            lengthChange: true,
            searching: true,
            paging: true,
            "columns": [
                {data: 'fname'},
                {data: 'mname'},
                {data: 'lname'},
                {data: 'username'},
                {data: 'role'},
                {data: 'avatar'},
                {data: 'ustatus',
                        render: function(data, type, row) {
                        switch(parseInt(data)) {
                            case 1:
                                return '<span class="badge bg-info">Enabled</span>';
                            case 2:
                                return '<span class="badge bg-danger">Disabled</span>';
                            case 3:
                                return '<span class="badge bg-warning">Deleted</span>';
                            default:
                                return '<span class="badge bg-secondary">Unknown Status</span>';
                        }
                    },
                },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        if (type === 'display') {
                            var buttons = '<button type="button" class="btn btn-sm btn-success btn-useredit mr-1" data-id="' + row.id + '" data-fname="' + row.fname + '" data-mname="' + row.mname + '" data-lname="' + row.lname + '" data-username="' + row.username + '" data-gender="' + row.gender + '" data-role="' + row.role + '" data-toggle="tooltip" data-placement="top" title="Edit User."><i class="fas fa-pencil"></i> </button>&nbsp;';
                                buttons += '<button type="button" class="btn btn-sm btn-info btn-passedit mr-1" data-id="' + row.id + '" data-password="' + row.password + '" data-toggle="tooltip" data-placement="top" title="Edit User Password."><i class="fas fa-lock"></i> </button>&nbsp;';
                                buttons += '<button type="button" class="btn btn-sm btn-warning btn-ustatusedit mr-1" data-id="' + row.id + '" data-ustatus="' + row.ustatus + '" data-toggle="tooltip" data-placement="top" title="Enabled/Disabled."><i class="fas fa-toggle-on"></i> </button>&nbsp;';
                                buttons += '<button type="button" value="' + data + '" class="btn btn-sm btn-danger user-delete" data-toggle="tooltip" data-placement="top" title="Delete User."><i class="fas fa-trash"></i> </button>';
                            return buttons;
                        } else {
                            return data;
                        }
                    },
                },
            ],
            "createdRow": function (row, data, index) {
                $(row).attr('id', 'tr-' + data.id); 
            }
        });
        $(document).on('userAdded', function() {
            dataTable.ajax.reload();
        });

        dataTable.on('draw', function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    });

    $(document).on('click', '.btn-useredit', function() {
        var id = $(this).data('id');
        var fName = $(this).data('fname');
        var mName = $(this).data('mname');
        var lName = $(this).data('lname');
        var userName = $(this).data('username');
        var gender = $(this).data('gender');
        var role = $(this).data('role');

        $('#edituserId').val(id);
        $('#editfirstname').val(fName);
        $('#editmiddlename').val(mName);
        $('#editlastname').val(lName);
        $('#editusername').val(userName);
        $('#editgender').val(gender);
        $('#editrole').val(role);

        $('#editInfoModal').modal('show');
    });

    $('#editInfoForm').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: userUpdateRoute,
            type: "POST",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    $('#editInfoModal').modal('hide');
                    $(document).trigger('userAdded');
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr, status, error, message) {
                var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'An error occurred';
                toastr.error(errorMessage);
            }
        });
    });

    $(document).on('click', '.user-delete', function(e) {
        var id = $(this).val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        });
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to recover this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: userDeleteRoute.replace(':id', id),
                    success: function(response) {
                        $("#tr-" + id).delay(1000).fadeOut();
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Successfully Deleted!',
                            icon: 'warning',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        if(response.success) {
                            toastr.success(response.message);
                            console.log(response);
                        }
                    }
                });
            }
        })
    });

    $(document).on('click', '.btn-passedit', function() {
        var id = $(this).data('id');
        var password = $(this).data('password');

        $('#editPasswordId').val(id);
        $('#editPassword').val(password);

        $('#editPasswordModal').modal('show');
    });

    $(document).on('click', '.btn-ustatusedit', function() {
        var id = $(this).data('id');
        var ustatus = $(this).data('ustatus');

        $('#editUstatusId').val(id);
        $('#editUstatusName').val(ustatus);

        $('#editUstatusModal').modal('show');
    });

    $('#editPasswordForm').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: userPassUpdateRoute,
            type: "POST",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    $('#editPasswordModal').modal('hide');
                    $(document).trigger('userAdded');
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr, status, error, message) {
                var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'An error occurred';
                toastr.error(errorMessage);
            }
        });
    });

    $('#editUstatusForm').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: userStatusUpdateRoute,
            type: "POST",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    $('#editUstatusModal').modal('hide');
                    $(document).trigger('userAdded');
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr, status, error, message) {
                var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'An error occurred';
                toastr.error(errorMessage);
            }
        });
    });
</script>