@extends('admin.layouts.master')

@section('title')
    Shoplink || Products
@endsection

@section('body')
<style>
        .upload-container {
            padding: 5px;
        }

        .upload-container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .upload-area {
            border: 2px dashed #007B3A;
            border-radius: 10px;
            padding: 40px 20px;
            transition: background 0.3s, border-color 0.3s;
            color: #888;
            cursor: pointer;
            text-align: center;
        }

        .upload-areaedit {
            border: 2px dashed #007B3A;
            border-radius: 10px;
            padding: 40px 20px;
            transition: background 0.3s, border-color 0.3s;
            color: #888;
            cursor: pointer;
            text-align: center;
        }

        .upload-area.dragover {
            background: #f0f0ff;
            border-color: #4a42d4;
            color: #4a42d4;
        }

        .upload-areaedit.dragover {
            background: #f0f0ff;
            border-color: #4a42d4;
            color: #4a42d4;
        }

        #file-input {
            display: none;
        }

        #file-inputedit {
            display: none;
        }

        .preview {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }

        .preview img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .previewedit {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }

        .previewedit img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .btn-clearfiles {
            margin-top: 20px;
            background: #007B3A;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
    <div class="row ">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-3 mb-4 d-none d-md-block">Item</h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-server"></i> Item
                                </h6>
                            </div>
                            <div class="card-body">
                                <button type="button" class="btn btn-outline-success btn-shoplink btn-md" data-bs-toggle="modal" data-bs-target="#addProductItemModal">
                                    <i class="fas fa-plus"></i> Add New Product
                                </button>
                                <div class="table-responsive mt-3 p-2">
                                    <table id="prodTable" class="table table-striped" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th>Item/Product</th>
                                                <th>Price</th>
                                                <th>Stock</th>
                                                <th>Category</th>
                                                <th>Sub Category</th>
                                                <th>Image</th>
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

    <div class="modal fade" id="addProductItemModal" tabindex="-1" role="dialog" aria-labelledby="addProductItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductItemModalLabel">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="adProduct" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="productCat">Category :</label>
                                    <select name="catid" class="form-control" id="productCat">
                                        <option disabled selected> --Select Category-- </option>
                                        @foreach ($categories as $item)
                                            <option value="{{ $item->id }}">{{ $item->catname }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="productSubCat">Sub Category :</label>
                                    <select name="subcatid" class="form-control" id="productSubCat">
                                        <option disabled selected> --Select Sub Category-- </option>
                                        <option value="1">Men</option>
                                        <option value="2">Women</option>
                                        <option value="3">Kids</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="productName">Product Name :</label>
                                    <input type="text" class="form-control" id="productName" name="prdctname"
                                        placeholder="Enter Product name">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="productDescription">Product Description :</label>
                                    <textarea name="prdctdesc" class="form-control" id="productDescription" cols="30" rows="5"
                                        placeholder="Enter Product Description"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="productSku">Product SKU :</label>
                                    <input type="text" class="form-control" id="productSku" name="prdctsku"
                                        placeholder="Enter Product SKU">
                                </div>
                                <div class="col-md-4">
                                    <label for="productPrice">Product Price :</label>
                                    <input type="number" class="form-control" id="productPrice" name="prdctprice"
                                        placeholder="Enter Product price">
                                </div>
                                <div class="col-md-4">
                                    <label for="productStock">Product Stock :</label>
                                    <input type="number" class="form-control" id="productStock" name="prdctstock"
                                        placeholder="Enter Product stock">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="productVariation">Product Variation :</label>
                                    <input type="text" class="form-control" id="productVariation" name="prdctvariation"
                                        placeholder="Enter Product variation">
                                </div>
                                <div class="col-md-4">
                                    <label for="productTag">Product Tag :</label>
                                        <select name="prdcttag" id="productTagSelect" class="form-control">
                                            <option value="">Select a tag</option>
                                            <option value="Popular">Popular</option>
                                            <option value="New Arrival">New Arrival</option>
                                            <option value="Sale">Sale</option>
                                        </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="productPercentageOff">Product Percentage Off :</label>
                                    <input type="number" class="form-control" id="productPercentageOff" name="prdctpercentageoff"
                                        placeholder="Enter Product percentage off">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="upload-container">
                                        <label for="file-input" style="text-align: left !important">Product Image :</label>
                                        <div id="upload-area" class="upload-area">
                                            Drag & Drop files here<br>or<br><strong>Click to Upload</strong>
                                            <input type="file" name="prdctimage[]" id="file-input" accept="image/*" multiple>
                                        </div>
                                        <div id="preview" class="preview"></div>
                                        <button class="btn btn-default btn-clearfiles" id="clear-btn">Clear
                                            Files</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 60vw;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Edit Product Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editProductForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editproductId">

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="editproductCat">Category :</label>
                                    <select name="catid" class="form-control" id="editproductCat">
                                        <option disabled selected> --Select Category-- </option>
                                        @foreach ($categories as $item)
                                            <option value="{{ $item->id }}">{{ $item->catname }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="editproductSubCat">Sub Category :</label>
                                    <select name="subcatid" class="form-control" id="editproductSubCat">
                                        <option disabled selected> --Select Sub Category-- </option>
                                        <option value="1">Men</option>
                                        <option value="2">Women</option>
                                        <option value="3">Kids</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="editproductName">Product Name :</label>
                                    <input type="text" class="form-control" id="editproductName" name="prdctname"
                                        placeholder="Enter Product name">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="editproductDescription">Product Description :</label>
                                    <textarea name="prdctdesc" class="form-control" id="editproductDescription" cols="30" rows="5"
                                        placeholder="Enter Product Description"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="editproductCode">Product Code :</label>
                                    <input type="text" class="form-control" id="editproductCode" name="prdctcode"
                                        placeholder="Enter Product code">
                                </div>
                                <div class="col-md-4">
                                    <label for="editproductPrice">Product Price :</label>
                                    <input type="number" class="form-control" id="editproductPrice" name="prdctprice"
                                        placeholder="Enter Product price">
                                </div>
                                <div class="col-md-4">
                                    <label for="editproductStock">Product Stock :</label>
                                    <input type="number" class="form-control" id="editproductStock" name="prdctstock"
                                        placeholder="Enter Product stock">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="upload-container">
                                        <label for="file-inputedit" style="text-align: left !important">Product Image
                                            :</label>
                                        <div id="upload-areaedit" class="upload-areaedit">
                                            Drag & Drop files here<br>or<br><strong>Click to Upload</strong>
                                            <input type="file" name="prdctimage" id="file-inputedit" accept="image/*">
                                        </div>
                                        <div id="previewedit" class="previewedit"></div>
                                        <button class="btn btn-default btn-clearfiles" id="clear-btnedit">Clear
                                            Files</button>
                                    </div>
                                </div>

                                <div class="col-md-6 mt-3">
                                    <input type="hidden" id="editUploadPhotoProd" class="form-control form-control-sm" >
                                    <img id="uploadedPhoto" class="img-square" width="90%" src="" alt="Image">
                                    <p id="noDocumentText" style="text-align: center;" class="big-text">No document uploaded</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var productReadRoute = "{{ route('product.show') }}";
        var productCreateRoute = "{{ route('product.store') }}";
        var productUpdateRoute = "{{ route('product.update', ['id' => ':id']) }}";
        var productDeleteRoute = "{{ route('product.destroy', ['id' => ':id']) }}";
        var photoStorage = "{{ asset('storage/') }}";
    </script>

    <script>
        const uploadArea = document.getElementById('upload-area');
        const fileInput = document.getElementById('file-input');
        const preview = document.getElementById('preview');
        const clearBtn = document.getElementById('clear-btn');

        uploadArea.addEventListener('click', () => fileInput.click());

        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files; 
                displayPreview(files);
            }
        });

        fileInput.addEventListener('change', (e) => {
            displayPreview(e.target.files);
        });

        clearBtn.addEventListener('click', () => {
            preview.innerHTML = '';
            fileInput.value = '';
        });

        function displayPreview(files) {
            preview.innerHTML = '';
            Array.from(files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                } else {
                    const fileIcon = document.createElement('div');
                    fileIcon.textContent = file.name;
                    fileIcon.style.cssText =
                        'padding:10px; border:1px solid #ddd; border-radius:8px; background:#fafafa;';
                    preview.appendChild(fileIcon);
                }
            });
        }
    </script>

    <script>
        const uploadAreaedit = document.getElementById('upload-areaedit');
        const fileInputedit = document.getElementById('file-inputedit');
        const previewedit = document.getElementById('previewedit');
        const clearBtnedit = document.getElementById('clear-btnedit');


        uploadAreaedit.addEventListener('click', () => fileInputedit.click());


        uploadAreaedit.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadAreaedit.classList.add('dragover');
        });


        uploadAreaedit.addEventListener('dragleave', () => {
            uploadAreaedit.classList.remove('dragover');
        });


        uploadAreaedit.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadAreaedit.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInputedit.files = files; // 👈 This line attaches dropped files to the <input>
                displayPreviewedit(files);
            }
        });


        fileInputedit.addEventListener('change', (e) => {
            displayPreviewedit(e.target.files);
        });


        clearBtnedit.addEventListener('click', () => {
            previewedit.innerHTML = '';
            fileInputedit.value = '';
        });


        function displayPreviewedit(files) {
            previewedit.innerHTML = '';
            Array.from(files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        previewedit.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                } else {
                    const fileIcon = document.createElement('div');
                    fileIcon.textContent = file.name;
                    fileIcon.style.cssText =
                        'padding:10px; border:1px solid #ddd; border-radius:8px; background:#fafafa;';
                    previewedit.appendChild(fileIcon);
                }
            });
        }
    </script>
@endsection