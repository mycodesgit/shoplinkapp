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
                "dataSrc": "data"
            },
            destroy: true,
            info: true,
            responsive: true,
            lengthChange: true,
            searching: true,
            paging: true,
            "columns": [
                {data: 'prdctname'},  // Shows "Product Name - Size: Large"
                {data: 'variant_price'},
                {data: 'variant_sku'},
                {
                    data: 'available_stock',
                    title: 'Stocks',
                    render: function(data, type, row) {
                        if (data <= 0) {
                            return '<span class="badge bg-danger">Out of Stock</span>';
                        } else if (data <= 10) {
                            return '<span class="badge bg-warning">Only ' + data + ' left!</span>';
                        } else {
                            return '<span class="badge bg-success">' + data + ' in stock</span>';
                        }
                    }
                },
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
                    data: 'display_image',
                    title: 'Image',
                    render: function(data, type, row) {
                        if (data) {
                            let images;
                            try {
                                images = JSON.parse(data);
                            } catch (e) {
                                images = [data];
                            }
                            let firstImage = images.length ? images[0] : null;
                            
                            if (firstImage) {
                                let imagePath = firstImage;
                                if (!imagePath.startsWith('http')) {
                                    imagePath = `${photoStorage}/${firstImage}`;
                                }
                                
                                // Create variation info text
                                let variationInfo = '';
                                if (row.variation_name && row.variation_value) {
                                    variationInfo = `${row.variation_name}: ${row.variation_value}`;
                                }
                                
                                // Escape special characters for data attributes
                                let escapedProductName = (row.product_name || row.prdctname || '').replace(/'/g, "\\'");
                                let escapedVariationInfo = variationInfo.replace(/'/g, "\\'");
                                let escapedImagePath = imagePath.replace(/'/g, "\\'");
                                
                                return `<div class="image-container" style="position: relative; display: inline-block;">
                                            <img src="${imagePath}" 
                                                alt="Product Image" 
                                                width="40" 
                                                height="40"
                                                style="object-fit:cover; border-radius:8px; cursor:pointer;"
                                                class="img-thumbnail previewable-image"
                                                data-full-image="${escapedImagePath}"
                                                data-product-name="${escapedProductName}"
                                                data-variation-info="${escapedVariationInfo}"
                                                data-image-list='${JSON.stringify(images)}'>
                                            <div class="image-preview" style="display:none; position:absolute; top:50px; left:0; z-index:1000; 
                                                        background:white; padding:5px; border-radius:8px; box-shadow:0 4px 8px rgba(0,0,0,0.2);">
                                                <img src="${imagePath}" style="width:150px; height:150px; object-fit:cover; border-radius:4px;">
                                            </div>
                                        </div>`;
                            }
                        }
                        
                        return `<div class="text-center">
                                    <i class="ti ti-image" style="font-size: 24px; color: #ccc;"></i>
                                    <br>
                                    <small class="text-muted">No image</small>
                                </div>`;
                    }
                },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        if (type === 'display') {
                            var buttons = '<button type="button" class="btn btn-sm btn-outline-success btn-productedit" ' +
                                'data-id="' + row.id + '" ' +
                                'data-variation-id="' + (row.variation_id || '') + '" ' +
                                'data-catid="' + row.catid + '" ' +
                                'data-subcatid="' + row.subcatid + '" ' +
                                'data-prdctname="' + row.product_name + '" ' +
                                'data-prdctdesc="' + row.prdctdesc + '" ' +
                                'data-prdctimage="' + row.prdctimage + '" ' +
                                'data-variation-name="' + (row.variation_name || '') + '" ' +
                                'data-variation-value="' + (row.variation_value || '') + '" ' +
                                'data-variant-price="' + row.variant_price + '" ' +
                                'data-variant-sku="' + row.variant_sku + '" ' +
                                'data-toggle="tooltip" data-placement="top" title="Edit Product.">' +
                                '<i class="ti ti-pencil"></i> </button>&nbsp;';
                            buttons += '<button type="button" value="' + data + '" class="btn btn-sm btn-outline-danger prod-delete" ' +
                                'data-toggle="tooltip" data-placement="top" title="Delete Product.">' +
                                '<i class="ti ti-trash"></i> </button>';
                            return buttons;
                        }
                        return data;
                    }
                }
            ],
            "createdRow": function(row, data, index) {
                $(row).attr('id', 'tr-' + data.id + '-' + (data.variation_id || '0'));
                
                // Add low stock highlight
                if (data.available_stock <= 10 && data.available_stock > 0) {
                    $(row).addClass('table-warning');
                } else if (data.available_stock <= 0) {
                    $(row).addClass('table-danger');
                }
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
        var prdctstock = $(this).data('prdctstock');
        var prdctimage = $(this).data('prdctimage');

        $('#editproductId').val(id);
        $('#editproductName').val(prdctname);
        $('#editproductCat').val(catid);
        $('#editproductSubCat').val(subcatid);
        $('#editproductDescription').val(prdctdesc);
        $('#editproductCode').val(prdctcode);
        $('#editproductPrice').val(prdctprice);
        $('#editproductStock').val(prdctstock);
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

    // Simpler image preview with SweetAlert
    $(document).on('click', '.previewable-image', function(e) {
        e.preventDefault();
        
        var imageUrl = $(this).data('full-image');
        var productName = $(this).data('product-name');
        var variationInfo = $(this).data('variation-info');
        
        if (!imageUrl) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No image available'
            });
            return;
        }
        
        var htmlContent = `
            <div class="text-center">
                ${variationInfo ? '<p class="text-muted mb-2"><strong>' + variationInfo + '</strong></p>' : ''}
                <img src="${imageUrl}" style="max-width: 100%; max-height: 60vh; border-radius: 8px; cursor: pointer;" id="preview-img">
            </div>
        `;
        
        Swal.fire({
            title: productName || 'Product Image',
            html: htmlContent,
            showCloseButton: true,
            showConfirmButton: false,
            showDenyButton: true,
            denyButtonText: '<i class="ti ti-download"></i> Download',
            customClass: {
                popup: 'image-preview-popup'
            },
            didOpen: () => {
                // Add zoom functionality
                var img = document.getElementById('preview-img');
                var scale = 1;
                
                img.addEventListener('wheel', function(e) {
                    e.preventDefault();
                    if (e.deltaY < 0) {
                        scale += 0.1;
                    } else {
                        scale -= 0.1;
                    }
                    scale = Math.min(Math.max(scale, 0.5), 3);
                    img.style.transform = 'scale(' + scale + ')';
                    img.style.transition = 'transform 0.2s';
                });
            }
        }).then((result) => {
            if (result.isDenied) {
                downloadImage(imageUrl, productName || 'product-image');
            }
        });
    });
</script>