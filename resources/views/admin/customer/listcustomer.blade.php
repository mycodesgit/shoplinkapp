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
                                    <i class="ti ti-users"></i> Registered Customers
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive mt-3 p-2">
                                    <table id="customerTable" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Avatar</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Mobile</th>
                                                <th>Gender</th>
                                                <th>Status</th>
                                                <th>Registered</th>
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

    <div class="modal fade" id="viewCustomerModal" tabindex="-1" role="dialog" aria-labelledby="viewCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewCustomerModalLabel">Customer Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img id="viewAvatar" src="" alt="Avatar" class="rounded-circle" width="64" height="64" style="object-fit: cover;" />
                        <div>
                            <div class="fw-bold fs-5" id="viewFullName">—</div>
                            <small class="text-muted" id="viewEmail">—</small>
                        </div>
                        <span class="badge bg-dark ms-auto" id="viewStatus">—</span>
                    </div>
                    <div class="row g-3 small">
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <div class="fw-semibold mb-2">Personal Information</div>
                                <div>Gender: <span id="viewGender">—</span></div>
                                <div>Birthday: <span id="viewBday">—</span></div>
                                <div>Mobile: <span id="viewMobile">—</span></div>
                                <div>Member Since: <span id="viewCreated">—</span></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <div class="fw-semibold mb-2">Address</div>
                                <div id="viewAddress">—</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editCstatusModal" tabindex="-1" role="dialog" aria-labelledby="editCstatusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCstatusModalLabel">Customer Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCstatusForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editCstatusId">
                        <div class="form-group">
                            <label for="editCstatusName">Select Status for <span id="editCstatusWho" class="fw-semibold"></span></label>
                            <select name="cstatus" id="editCstatusName" class="form-control">
                                <option disabled selected> --Select-- </option>
                                <option value="1">Enabled</option>
                                <option value="2">Disabled</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-dark">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var customerReadRoute = "{{ route('customer.show') }}";
        var customerStatusRoute = "{{ route('customer.updateStatus') }}";
        var avatarBaseUrl = "{{ asset('storage/') }}";
    </script>
    @include('admin.adscript.customer.customerSerialize')
@endpush
