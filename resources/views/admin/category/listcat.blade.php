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
                                    <i class="ti ti-server"></i> Category
                                </h6>
                            </div>
                            <div class="card-body">
                                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modal-category">
                                    <i class="ti ti-plus"></i> Add New Category
                                </button>
                                <div class="table-responsive mt-3 p-2">
                                    <table id="catTable" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Category Name</th>
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
                                    <label>Category Icon:</label>
                                    <input type="text" id="adicon-search" class="form-control" placeholder="Type icon name, e.g. bag">
                                    <div id="adicon-suggestions" class="list-group mt-2"></div>
                                    <input type="hidden" name="caticon" id="adcaticon-hidden">
                                    <div class="adicon-adpreview">
                                        <div id="adicon-preview" class="mt-2 ti fs-1"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="form-group">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label>Icons:</label>
                                    <select name="caticon" id="caticon" class="form-control">
                                        <option value="">-- Select Category Icon --</option>
                                    </select>
                                    <div id="icon-preview" class="mt-2"></div>
                                </div>
                            </div>
                        </div> --}}

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
                                <label for="icon-search">Select Icon:</label>
                                <input type="text" id="icon-search" class="form-control" placeholder="Type icon name, e.g. bag">
                                <div id="icon-suggestions" class="list-group mt-2"></div>
                                <input type="hidden" name="caticon" id="caticon-hidden">
                                <div class="icon-preview">
                                    <div id="icon-preview" class="mt-2 ti fs-1"></div>
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
    </div>

    <script>
        var categoryReadRoute = "{{ route('category.show') }}";
        var categoryCreateRoute = "{{ route('category.store') }}";
        var categoryUpdateRoute = "{{ route('category.update', ['id' => ':id']) }}";
        var categoryDeleteRoute = "{{ route('category.destroy', ['id' => ':id']) }}";
    </script>
    <script>
        const adsearchInput = document.getElementById("adicon-search");
        const adsuggestions = document.getElementById("adicon-suggestions");
        const adpreview = document.getElementById("adicon-preview");
        const adhiddenInput = document.getElementById("adcaticon-hidden");

        const adicons = [];
        for (let sheet of document.styleSheets) {
            try {
                for (let rule of sheet.cssRules) {
                    if (rule.selectorText && rule.selectorText.startsWith(".ti-") && rule.style.content) {
                        const className = rule.selectorText.replace(".","").replace(":before","");
                        let content = rule.style.content.replace(/["']/g,""); // remove quotes
                        adicons.push({ name: className, content: content });
                    }
                }
            } catch(e) {
                continue;
            }
        }

        adsearchInput.addEventListener("input", function() {
            const adquery = this.value.toLowerCase();
            adsuggestions.innerHTML = "";

            if (!adquery) return;

            const matches = adicons.filter(icon => icon.name.includes(adquery));

            matches.forEach(icon => {
                const item = document.createElement("button");
                item.type = "button";
                item.className = "list-group-item list-group-item-action d-flex align-items-center gap-2";
                item.innerHTML = `<span class="ti">${icon.content}</span> ${icon.name}`;

                item.addEventListener("click", () => {
                    let cleanName = icon.name.trim();
                    cleanName = cleanName.replace(/[^a-z0-9\-]/gi, '');
                    cleanName = 'ti ' + cleanName;
                    adhiddenInput.value = cleanName;
                    adpreview.innerHTML = icon.content;
                    adsearchInput.value = icon.name;
                    adsuggestions.innerHTML = "";
                });

                adsuggestions.appendChild(item);
            });
        });
    </script>
    <script>
        const searchInput = document.getElementById("icon-search");
        const suggestions = document.getElementById("icon-suggestions");
        const preview = document.getElementById("icon-preview");
        const hiddenInput = document.getElementById("caticon-hidden");

        const icons = [];
        for (let sheet of document.styleSheets) {
            try {
                for (let rule of sheet.cssRules) {
                    if (rule.selectorText && rule.selectorText.startsWith(".ti-") && rule.style.content) {
                        const className = rule.selectorText.replace(".","").replace(":before","");
                        let content = rule.style.content.replace(/["']/g,""); // remove quotes
                        icons.push({ name: className, content: content });
                    }
                }
            } catch(e) {
                continue;
            }
        }

        searchInput.addEventListener("input", function() {
            const query = this.value.toLowerCase();
            suggestions.innerHTML = "";

            if (!query) return;

            const matches = icons.filter(icon => icon.name.includes(query));

            matches.forEach(icon => {
                const item = document.createElement("button");
                item.type = "button";
                item.className = "list-group-item list-group-item-action d-flex align-items-center gap-2";
                item.innerHTML = `<span class="ti">${icon.content}</span> ${icon.name}`;

                item.addEventListener("click", () => {
                    let cleanName = icon.name.trim();
                    cleanName = cleanName.replace(/[^a-z0-9\-]/gi, '');
                    cleanName = 'ti ' + cleanName;
                    hiddenInput.value = cleanName;
                    preview.innerHTML = icon.content;
                    searchInput.value = icon.name;
                    suggestions.innerHTML = "";
                });

                suggestions.appendChild(item);
            });
        });
    </script>
@endsection