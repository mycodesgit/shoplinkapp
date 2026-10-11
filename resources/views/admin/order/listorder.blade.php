@extends('admin.layouts.master')

@section('title')
    Shoplink || Orders
@endsection

@section('body')
    <div class="row ">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-3 mb-4 d-none d-md-block">Orders</h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-shopping-bag"></i> Orders
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <button type="button" class="btn btn-dark btn-sm order-filter active" data-filter="all">All</button>
                                    <button type="button" class="btn btn-outline-dark btn-sm order-filter" data-filter="pending">Pending</button>
                                    <button type="button" class="btn btn-outline-dark btn-sm order-filter" data-filter="active">Active</button>
                                    <button type="button" class="btn btn-outline-dark btn-sm order-filter" data-filter="ready">Ready</button>
                                    <button type="button" class="btn btn-outline-dark btn-sm order-filter" data-filter="delivered">Delivered</button>
                                    <button type="button" class="btn btn-outline-dark btn-sm order-filter" data-filter="cancelled">Cancelled</button>
                                </div>
                                <div class="table-responsive mt-3 p-2">
                                    <table id="orderTable" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Order #</th>
                                                <th>Customer</th>
                                                <th>Items</th>
                                                <th>Total</th>
                                                <th>Payment</th>
                                                <th>Status</th>
                                                <th>Ordered</th>
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

    <div class="modal fade" id="orderDetailsModal" tabindex="-1" role="dialog" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <div>
                            <div class="fw-bold fs-5" id="detailOrderNumber">—</div>
                            <small class="text-muted" id="detailOrderDate">—</small>
                        </div>
                        <span class="badge bg-dark" id="detailOrderStatus">—</span>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <div class="fw-semibold mb-2"><i class="ti ti-user"></i> Customer</div>
                                <div class="small" id="detailCustomer">—</div>
                                <div class="small text-muted" id="detailContact">—</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <div class="fw-semibold mb-2"><i class="ti ti-truck"></i> Fulfilment</div>
                                <div class="small" id="detailDelivery">—</div>
                                <div class="small text-muted" id="detailPayment">—</div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Variation</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="detailItems">
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <div style="min-width: 220px;">
                            <div class="d-flex justify-content-between small"><span>Subtotal</span><span id="detailSubtotal">—</span></div>
                            <div class="d-flex justify-content-between small"><span>Tax</span><span id="detailTax">—</span></div>
                            <div class="d-flex justify-content-between small"><span>Delivery Fee</span><span id="detailFee">—</span></div>
                            <div class="d-flex justify-content-between small"><span>Discount</span><span id="detailDiscount">—</span></div>
                            <div class="d-flex justify-content-between fw-bold mt-1 pt-1 border-top"><span>Total</span><span id="detailTotal">—</span></div>
                        </div>
                    </div>

                    <div class="small text-muted mt-2" id="detailNotes"></div>
                </div>
                <div class="modal-footer flex-wrap gap-2" id="detailActions">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var orderReadRoute = "{{ route('order.show') }}";
        var orderDetailsRoute = "{{ route('order.details', ['id' => '__ID__']) }}";
        var orderStatusRoute = "{{ route('order.updateStatus') }}";
    </script>
    @include('admin.adscript.order.orderSerialize')
@endpush
