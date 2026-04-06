@extends('admin.layouts.master')

@section('title')
    Shoplink || Customers
@endsection

@section('body')
    <div class="row ">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-3 mb-4 d-none d-md-block">Customers</h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-server"></i> Customers
                                </h6>
                            </div>
                            <div class="card-body">
                                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modal-category">
                                    <i class="ti ti-plus"></i> Add New Category
                                </button>
                                <div class="table-responsive mt-3 p-2">
                                    <table id="customerTable" class="table table-striped">
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

    <script>
        
    </script>
@endsection