<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right"
    };
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
                            var buttons = '<button type="button" class="btn btn-sm btn-outline-success btn-banneredit" data-id="' + row.id + '" data-welcometext="' + row.welcometext + '" data-toggle="tooltip" data-placement="top" title="Edit Welcome Banner."><i class="ti ti-pencil"></i></button>&nbsp;';
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
    });
    

    
</script>