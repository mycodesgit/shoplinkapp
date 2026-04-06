<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right"
    };
    $(document).ready(function() {
       $('#adProduct').submit(function(event) {
            event.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: productCreateRoute,
                type: "POST",
                data: formData,
                processData: false,   
                contentType: false,  
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        console.log(response);
                        $(document).trigger('productAdded');
                        $('#addProductItemModal').modal('hide');
                        $('#adProduct')[0].reset();
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


        var dataTable = $('#prodTable').DataTable({
            "ajax": {
                "url": productReadRoute,
                "type": "GET",
            },
            destroy: true,
            info: true,
            responsive: true,
            lengthChange: true,
            searching: true,
            paging: true,
            "columns": [
                {data: 'prdctname'},
                {data: 'prdctprice'},
                {data: 'prdctsku'},
                {data: 'catname'},
                {
                    data: 'subcatid',
                    title: 'Sub Category',
                    render: function(data) {
                        if (data == 1) return 'Men';
                        if (data == 2) return 'Women';
                        if (data == 3) return 'Kids';
                        return 'Unknown';
                    }
                },
                {
                    data: 'prdctimage',
                    title: 'Image',
                    render: function (data, type, row) {

                        if (data) {
                            let images;

                            try {
                                images = JSON.parse(data);
                            } catch (e) {
                                images = [data]; // fallback if it's still single string
                            }

                            let firstImage = images.length ? images[0] : null;

                            if (firstImage) {
                                return `<img src="${photoStorage}/${firstImage}" 
                                            alt="Product Image" 
                                            width="30" 
                                            height="30"
                                            style="object-fit:cover; border-radius:8px;">`;
                            }
                        }

                        return `<i class="ti ti-image"></i>`;
                    }
                },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        if (type === 'display') {
                            var buttons = '<button type="button" class="btn btn-sm btn-outline-success btn-productedit" data-id="' + row.id + '" data-catid="' + row.catid + '" data-subcatid="' + row.subcatid + '" data-prdctname="' + row.prdctname + '" data-prdctname="' + row.prdctname + '" data-prdctdesc="' + row.prdctdesc + '" data-prdctcode="' + row.prdctcode + '" data-prdctprice="' + row.prdctprice + '" data-prdctsku="' + row.prdctsku + '" data-prdctimage="' + row.prdctimage + '" data-toggle="tooltip" data-placement="top" title="Edit Product."><i class="ti ti-pencil"></i> </button>&nbsp;';
                                buttons += '<button type="button" value="' + data + '" class="btn btn-sm btn-outline-danger prod-delete" data-toggle="tooltip" data-placement="top" title="Delete Product."><i class="ti ti-trash"></i> </button>';
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
        $(document).on('productAdded', function() {
            dataTable.ajax.reload();
        });

        dataTable.on('draw', function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    });
    

    $(document).on('click', '.btn-productedit', function() {
        var id = $(this).data('id');
        var catid = $(this).data('catid');
        var subcatid = $(this).data('subcatid');
        var prdctname = $(this).data('prdctname');
        var prdctdesc = $(this).data('prdctdesc');
        var prdctcode = $(this).data('prdctcode');
        var prdctprice = $(this).data('prdctprice');
        var prdctsku = $(this).data('prdctsku');
        var prdctimage = $(this).data('prdctimage');

        $('#editproductId').val(id);
        $('#editproductName').val(prdctname);
        $('#editproductCat').val(catid);
        $('#editproductSubCat').val(subcatid);
        $('#editproductDescription').val(prdctdesc);
        $('#editproductCode').val(prdctcode);
        $('#editproductPrice').val(prdctprice);
        $('#productSku').val(prdctsku);
        $('#editUploadPhotoProd').val(prdctimage);

        if (prdctimage) {
            $('#uploadedPhoto').attr('src', photoStorage + "/" + prdctimage).show();
            $('#uploadedPhoto').removeAttr('alt');
            $('#noDocumentText').hide();
        } else {
            $('#uploadedPhoto').attr('src', '').hide();
            $('#noDocumentText').show();
            $('#noDocumentText').css('font-size', '58px');
        }

        $('#editProductModal').modal('show');
    });

    $('#editProductForm').submit(function(event) {
        event.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: productUpdateRoute,
            type: "POST",
            data: formData,
            processData: false,   
            contentType: false, 
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    $('#editProductModal').modal('hide');
                    $(document).trigger('productAdded');
                    $('#clear-btnedit').hide();

                    // (Optional) also clear the file input and preview
                    $('#file-inputedit').val('');
                    $('#previewedit').html('');
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

    $(document).on('click', '.prod-delete', function(e) {
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
                    url: productDeleteRoute.replace(':id', id),
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