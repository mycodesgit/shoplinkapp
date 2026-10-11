<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right"
    };

    // Row cache lives at script scope so the edit handler can reach it
    var bannerCache = {};

    $(document).ready(function() {
        $('#addBanner').submit(function(event) {
            event.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                url: welcomeCreateRoute,
                type: "POST",
                data: formData,
                success: function(response) {
                    if(response.success) {
                        toastr.success(response.message);
                        console.log(response);
                        $(document).trigger('welcomeBannerAdded');
                        $('#addWelcomeBannerModal').modal('hide');
                        $('#addBanner')[0].reset();
                    } else {
                        toastr.error(response.message);
                        console.log(response);
                    }
                },
                error: function(xhr, status, error, message) {
                    var errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'An error occurred';
                    toastr.error(errorMessage);
                }
            });
        });

        var dataTable = $('#welcomeTable').DataTable({
            "ajax": {
                "url": welcomeReadRoute,
                "type": "GET",
            },
            destroy: true,
            info: true,
            responsive: true,
            lengthChange: true,
            searching: true,
            paging: true,
            "columns": [
                {data: 'welcometext'},
                {data: 'welcomesubtext'},
                {data: 'welcomestatus',
                        render: function(data, type, row) {
                        switch(data){
                            case 'Active':
                                return '<span class="badge bg-success">Active</span>';
                            case 'Inactive':
                                return '<span class="badge bg-danger">Inactive</span>';
                            default:
                                return '<span class="badge bg-secondary">Unknown Status</span>';
                        }
                    },
                },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        if (type === 'display') {
                            var buttons = '<button type="button" class="btn btn-sm btn-outline-success btn-banneredit" data-id="' + row.id + '" data-toggle="tooltip" data-placement="top" title="Edit Welcome Banner."><i class="ti ti-pencil"></i></button>&nbsp;';
                                buttons += '<button type="button" value="' + data + '" class="btn btn-sm btn-outline-danger welcome-delete" data-toggle="tooltip" data-placement="top" title="Delete Welcome Banner."><i class="ti ti-trash"></i> </button>';
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
        $(document).on('welcomeBannerAdded', function() {
            dataTable.ajax.reload();
        });

        dataTable.on('draw', function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

        // Cache rows so the edit modal never breaks on quotes in banner text
        bannerCache = {};
        dataTable.on('xhr', function (e, settings, json) {
            bannerCache = {};
            (json.data || []).forEach(function (row) { bannerCache[row.id] = row; });
        });
    });

    $(document).on('click', '.btn-banneredit', function() {
        var row = bannerCache[$(this).data('id')] || {};

        $('#editBannerId').val(row.id);
        $('#editBannerText').val(row.welcometext);
        $('#editBannerSubText').val(row.welcomesubtext);
        $('#editBannerStatus').val((row.welcomestatus || 'inactive').toLowerCase());

        $('#editBannerModal').modal('show');
    });

    $('#editBannerForm').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: welcomeUpdateRoute,
            type: "POST",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    $('#editBannerModal').modal('hide');
                    $(document).trigger('welcomeBannerAdded');
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

    $(document).on('click', '.welcome-delete', function(e) {
        var id = $(this).val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        });
        Swal.fire({
            title: 'Are you sure?',
            text: "This banner will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: welcomeDeleteRoute.replace('__ID__', id),
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
    

    
</script>