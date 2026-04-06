@extends('customer.layouts.app')

@section('body')
    <section class="py-lg-5 py-8" id="shop">
        <div class="container">
            <div class="row mt-6">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-center overflow-hidden rounded-4 main-itemimage">
                                        @php
                                            $images = json_decode($product->prdctimage, true);
                                            $firstImage = is_array($images) ? ($images[0] ?? null) : $product->prdctimage;
                                        @endphp
                                        <img src="{{ $firstImage ? asset('storage/' . $firstImage) : asset('storage/products/default.png') }}" class="img-fluid overflow-hidden rounded-4" id="mainImage">
                                    </div>

                                    <div class="d-flex gap-2 mt-5 justify-content-center">
                                        @if(is_array(json_decode($product->prdctimage, true)))
                                            @foreach(json_decode($product->prdctimage, true) as $img)
                                                <img src="{{ asset('storage/' . $img) }}" class="img-thumbnail thumb" width="70">
                                            @endforeach
                                        @else
                                            <img src="{{ asset('storage/' . $product->prdctimage) }}" class="img-thumbnail thumb" width="70">
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6 mt-6">
                                    <h4 class="fw-bold">Macbook Pro M2</h4>

                                    <p class="text-muted mb-1">Laptop</p>

                                    <div class="mb-2">
                                        ⭐ 4.5 <small class="text-muted">(214 Reviews)</small>
                                    </div>

                                    <p><strong>Condition:</strong> New Item</p>
                                    <p><strong>Weight:</strong> 3 KG</p>
                                    <p><strong>Warranty:</strong> 24 Month</p>
                                    <p><strong>Order Status:</strong> Ready to Ship</p>

                                    <!-- Price -->
                                    <h5 class="text-primary fw-bold">Rp 96,902,500</h5>

                                    <!-- Quantity -->
                                    <div class="d-flex align-items-center mb-3">
                                        <button class="btn btn-outline-secondary btn-sm">-</button>
                                        <input type="text" class="form-control mx-2 text-center" value="5" style="width: 60px;">
                                        <button class="btn btn-outline-secondary btn-sm">+</button>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="d-flex gap-2 mb-3">
                                        <button class="btn btn-primary w-50">
                                        <i class="ti ti-shopping-cart"></i> Add to Cart
                                        </button>
                                        <button class="btn btn-outline-warning w-50">
                                            ♡ Add to Wishlist
                                        </button>
                                    </div>

                                    <p class="text-danger small">🔥 120 Units Sold</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- STORE INFO -->
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('uilibs/images/student.png') }}" width="40" class="rounded-circle me-2">
                    <div>
                        <strong>Bimore Gadget Universe</strong><br>
                        <small class="text-muted">1,539 Total Products</small>
                    </div>
                </div>
                <a href="#" class="btn btn-outline-primary btn-sm">Visit Store</a>
            </div>

            <hr>

            <!-- PRODUCT DESCRIPTION -->
            <h5 class="fw-bold">Product About</h5>
            <p class="text-muted">
                The MacBook Pro 13-inch with the M2 chip delivers incredible speed,
                long battery life, and a brilliant Retina display—all in a sleek, compact design.
            </p>

            <h6 class="fw-bold">Key Specs</h6>
            <ul class="text-muted">
                <li>Chip: Apple M2 (8-core CPU, 8-core GPU)</li>
                <li>RAM: 8GB (expandable)</li>
                <li>Storage: 256GB SSD</li>
                <li>Display: 13.3" Retina (2560 × 1600)</li>
                <li>Battery Life: Up to 20 hours</li>
                <li>Ports: Thunderbolt / USB 4, 3.5mm jack</li>
                <li>Camera: 720p FaceTime HD</li>
                <li>Keyboard: Magic Keyboard with Touch ID</li>
            </ul>
        </div>
    </section>

    <script>
        document.querySelectorAll('.thumb').forEach(img => {
            img.addEventListener('click', function() {
                document.getElementById('mainImage').src = this.src;
            });
        });
    </script>
@endsection