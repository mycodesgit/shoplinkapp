@extends('admin.layouts.master')

@section('title')
    Shoplink || Products
@endsection

@section('body')
    <div class="row ">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-3 mb-4 d-none d-md-block">Welcome Banner Text</h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-server"></i> Welcome Banner Text
                                </h6>
                            </div>
                            <div class="card-body">
                                <button type="button" class="btn btn-outline-success btn-shoplink btn-md" data-bs-toggle="modal" data-bs-target="#addWelcomeBannerModal">
                                    <i class="fas fa-plus"></i> Add New Welcome Banner
                                </button>
                                <div class="table-responsive mt-3 p-2">
                                    <table id="welcomeTable" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Welcome Text</th>
                                                <th>Sub Text</th>
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

    <div class="modal fade" id="addWelcomeBannerModal">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus"></i> Add New Welcome Banner
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <form class="form-horizontal" method="post" id="addBanner">  
                        @csrf

                        <div class="form-group">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label>Welcome Text:</label>
                                    <input type="text" name="welcometext" placeholder="Enter Welcome Text" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label>Welcome Sub Text:</label>
                                    <input type="text" name="welcomesubtext" placeholder="Enter Welcome Sub Text" class="form-control">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label>Welcome Status:</label>
                                    <select name="welcomestatus" class="form-control">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
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
                </div>
            </div>
        </div>
    </div>

    <script>
        var welcomeReadRoute = "{{ route('welcomebanner.show') }}";
        var welcomeCreateRoute = "{{ route('welcomebanner.store') }}";
    </script>
@endsection