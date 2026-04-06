@extends('admin.layouts.master')

@section('title')
    Shoplink || Dashboard
@endsection

@section('body')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-3 mb-4 d-none d-md-block">Dashboard</h1>

                <div class="card bg-success bg-opacity-10 border border-success border-opacity-25 rounded-2 mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-shape icon-md bg-success text-white rounded-2 d-none d-md-block">
                                <i class="ti ti-user fs-4"></i>
                            </div>
                            <div>
                                <h1 class="mb-0 fs-2">Welcome back,
                                    
                                </h1>
                                <p class="text-secondary mb-0 small"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-5">
                    
                </div>
            </div>
        </div>
    </div>
@endsection