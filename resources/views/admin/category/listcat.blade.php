@extends('admin.layouts.master')

@section('title')
    Shoplink || Category
@endsection

@section('body')
    <div class="row ">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-3 mb-4 d-none d-md-block">Category</h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="fas fa-server"></i> Category
                                </h6>
                            </div>
                            <div class="card-body">
                                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modal-category">
                                    <i class="fas fa-plus"></i> Add New Category
                                </button>
                                <div class="table-responsive mt-3 p-2">
                                    <table id="catTable" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Category Name</th>
                                                <th>Sub Category</th>
                                                <th>Status</th>
                                                <th>Posted</th>
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

    <div class="modal fade" id="modal-category">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus"></i> Add New Category
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <form class="form-horizontal" method="post" id="addCategory">  
                        @csrf

                        <div class="form-group">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label>Category Name:</label>
                                    <input type="text" name="catname" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');" placeholder="Enter Category Name" class="form-control">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label>Sub Category:</label>
                                    <input type="text" name="subcategory" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');" placeholder="Enter Sub Category Name" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label>Category Icon:</label>
                                    <input type="text" id="adicon-search" class="form-control" placeholder="Type icon name, e.g. bag, shopping, user">
                                    <div id="adicon-suggestions" class="list-group mt-2"></div>
                                    <input type="hidden" name="caticon" id="adcaticon-hidden">
                                    <div class="adicon-adpreview">
                                        <div id="adicon-preview" class="mt-2 fs-1"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
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

    <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Edit Category Name</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCategoryForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editCategoryId">
                        <div class="form-group">
                            <label for="editCategoryName">Category Name</label>
                            <input type="text" class="form-control" id="editCategoryName" name="catname">
                        </div>
                        
                        <div class="form-group mt-3">
                            <label for="editSubCategory">Sub Category</label>
                            <input type="text" class="form-control" id="editSubCategory" name="subcategory">
                        </div>

                        <div class="form-group mt-3">
                            <label for="editSubCategory">Status</label>
                            <select class="form-control" id="editStatus" name="pcstatus">
                                <option value="1">Available</option>
                                <option value="2">Unavailable</option>
                                <option value="3">Deleted</option>
                            </select>
                        </div>

                        <div class="form-group mt-3">
                            <label for="icon-search">Select Icon:</label>
                            <input type="text" id="icon-search" class="form-control" placeholder="Type icon name, e.g. bag, shopping, user">
                            <div id="icon-suggestions" class="list-group mt-2"></div>
                            <input type="hidden" name="caticon" id="caticon-hidden">
                            <div class="icon-preview">
                                <div id="icon-preview" class="mt-2 fs-1"></div>
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

    <script>
        var categoryReadRoute = "{{ route('category.show') }}";
        var categoryCreateRoute = "{{ route('category.store') }}";
        var categoryUpdateRoute = "{{ route('category.update', ['id' => ':id']) }}";
        var categoryDeleteRoute = "{{ route('category.destroy', ['id' => ':id']) }}";
    </script>
    
    <script>
        // Font Awesome 6 Icons - Common icons for categories
        const fontAwesomeIcons = [
            // Solid Icons (fas)
            { name: "fa-solid fa-bag-shopping", class: "fa-solid fa-bag-shopping" },
            { name: "fa-solid fa-cart-shopping", class: "fa-solid fa-cart-shopping" },
            { name: "fa-solid fa-tshirt", class: "fa-solid fa-tshirt" },
            { name: "fa-solid fa-female", class: "fa-solid fa-female" },
            { name: "fa-solid fa-shirt", class: "fa-solid fa-shirt" },
            { name: "fa-solid fa-mobile-alt", class: "fa-solid fa-mobile-alt" },
            { name: "fa-solid fa-mobile-screen-button", class: "fa-solid fa-mobile-screen-button" },
            { name: "fa-solid fa-laptop", class: "fa-solid fa-laptop" },
            { name: "fa-solid fa-computer", class: "fa-solid fa-computer" },
            { name: "fa-solid fa-headphones", class: "fa-solid fa-headphones" },
            { name: "fa-solid fa-gem", class: "fa-solid fa-gem" },
            { name: "fa-solid fa-ring", class: "fa-solid fa-ring" },
            { name: "fa-solid fa-shoe-prints", class: "fa-solid fa-shoe-prints" },
            { name: "fa-solid fa-socks", class: "fa-solid fa-socks" },
            { name: "fa-solid fa-home", class: "fa-solid fa-home" },
            { name: "fa-solid fa-house", class: "fa-solid fa-house" },
            { name: "fa-solid fa-futbol", class: "fa-solid fa-futbol" },
            { name: "fa-solid fa-basketball", class: "fa-solid fa-basketball" },
            { name: "fa-solid fa-spa", class: "fa-solid fa-spa" },
            { name: "fa-solid fa-puzzle-piece", class: "fa-solid fa-puzzle-piece" },
            { name: "fa-solid fa-tag", class: "fa-solid fa-tag" },
            { name: "fa-solid fa-tags", class: "fa-solid fa-tags" },
            { name: "fa-solid fa-box", class: "fa-solid fa-box" },
            { name: "fa-solid fa-box-open", class: "fa-solid fa-box-open" },
            { name: "fa-solid fa-gift", class: "fa-solid fa-gift" },
            { name: "fa-solid fa-clock", class: "fa-solid fa-clock" },
            { name: "fa-solid fa-watch", class: "fa-solid fa-clock" },
            { name: "fa-solid fa-camera", class: "fa-solid fa-camera" },
            { name: "fa-solid fa-video", class: "fa-solid fa-video" },
            { name: "fa-solid fa-book", class: "fa-solid fa-book" },
            { name: "fa-solid fa-toy", class: "fa-solid fa-gamepad" },
            { name: "fa-solid fa-gamepad", class: "fa-solid fa-gamepad" },
            { name: "fa-solid fa-dumbbell", class: "fa-solid fa-dumbbell" },
            { name: "fa-solid fa-utensils", class: "fa-solid fa-utensils" },
            { name: "fa-solid fa-car", class: "fa-solid fa-car" },
            { name: "fa-solid fa-paw", class: "fa-solid fa-paw" },
            { name: "fa-solid fa-heart", class: "fa-solid fa-heart" },
            { name: "fa-solid fa-star", class: "fa-solid fa-star" },
            { name: "fa-solid fa-user", class: "fa-solid fa-user" },
            { name: "fa-solid fa-users", class: "fa-solid fa-users" },
            { name: "fa-solid fa-store", class: "fa-solid fa-store" },
            { name: "fa-solid fa-truck", class: "fa-solid fa-truck" },
            { name: "fa-solid fa-credit-card", class: "fa-solid fa-credit-card" },
            
            // Regular Icons (far)
            { name: "fa-regular fa-heart", class: "fa-regular fa-heart" },
            { name: "fa-regular fa-star", class: "fa-regular fa-star" },
            { name: "fa-regular fa-gem", class: "fa-regular fa-gem" },
            { name: "fa-regular fa-clock", class: "fa-regular fa-clock" },
            { name: "fa-regular fa-user", class: "fa-regular fa-user" },
            
            // Brand Icons (fab)
            { name: "fa-brands fa-apple", class: "fa-brands fa-apple" },
            { name: "fa-brands fa-android", class: "fa-brands fa-android" },
            { name: "fa-brands fa-windows", class: "fa-brands fa-windows" },
            { name: "fa-brands fa-amazon", class: "fa-brands fa-amazon" },
            { name: "fa-brands fa-google", class: "fa-brands fa-google" },
            { name: "fa-brands fa-facebook", class: "fa-brands fa-facebook" },
            { name: "fa-brands fa-instagram", class: "fa-brands fa-instagram" },
            { name: "fa-brands fa-twitter", class: "fa-brands fa-twitter" },
            { name: "fa-brands fa-nike", class: "fa-brands fa-nike" },
            { name: "fa-brands fa-adidas", class: "fa-brands fa-adidas" }
        ];

        // For Add Modal
        const adsearchInput = document.getElementById("adicon-search");
        const adsuggestions = document.getElementById("adicon-suggestions");
        const adpreview = document.getElementById("adicon-preview");
        const adhiddenInput = document.getElementById("adcaticon-hidden");

        if (adsearchInput) {
            adsearchInput.addEventListener("input", function() {
                const adquery = this.value.toLowerCase();
                adsuggestions.innerHTML = "";

                if (!adquery) return;

                const matches = fontAwesomeIcons.filter(icon => 
                    icon.name.toLowerCase().includes(adquery)
                );

                matches.slice(0, 30).forEach(icon => {
                    const item = document.createElement("button");
                    item.type = "button";
                    item.className = "list-group-item list-group-item-action d-flex align-items-center gap-2";
                    item.innerHTML = `<i class="${icon.class} fa-fw me-2 fa-lg"></i> ${icon.name}`;

                    item.addEventListener("click", () => {
                        adhiddenInput.value = icon.class;
                        adpreview.innerHTML = `<i class="${icon.class} fa-2x"></i>`;
                        adsearchInput.value = icon.name;
                        adsuggestions.innerHTML = "";
                    });

                    adsuggestions.appendChild(item);
                });
            });
        }

        // For Edit Modal
        const searchInput = document.getElementById("icon-search");
        const suggestions = document.getElementById("icon-suggestions");
        const preview = document.getElementById("icon-preview");
        const hiddenInput = document.getElementById("caticon-hidden");

        if (searchInput) {
            searchInput.addEventListener("input", function() {
                const query = this.value.toLowerCase();
                suggestions.innerHTML = "";

                if (!query) return;

                const matches = fontAwesomeIcons.filter(icon => 
                    icon.name.toLowerCase().includes(query)
                );

                matches.slice(0, 30).forEach(icon => {
                    const item = document.createElement("button");
                    item.type = "button";
                    item.className = "list-group-item list-group-item-action d-flex align-items-center gap-2";
                    item.innerHTML = `<i class="${icon.class} fa-fw me-2 fa-lg"></i> ${icon.name}`;

                    item.addEventListener("click", () => {
                        hiddenInput.value = icon.class;
                        preview.innerHTML = `<i class="${icon.class} fa-2x"></i>`;
                        searchInput.value = icon.name;
                        suggestions.innerHTML = "";
                    });

                    suggestions.appendChild(item);
                });
            });
        }
    </script>
@endsection