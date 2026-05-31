@extends('customer.layouts.app')

@section('content')
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-24 md:pb-10 page-fade">    
        <h5 class="text-md font-bold mb-6">Shopping Cart</h5>
        <div id="cartContent"></div>
    </main>

    <script>
        function loadCart() { return JSON.parse(localStorage.getItem('cart') || '[]'); }
        function saveCart(cart) { localStorage.setItem('cart', JSON.stringify(cart)); updateCounts(); renderCart(); }
        
        function updateCounts() {
            const cart = loadCart();
            const total = cart.reduce((sum, i) => sum + i.quantity, 0);
            document.getElementById('cartCount').innerText = total;
            const mobile = document.getElementById('mobileCartBadge');
            if (mobile) mobile.innerText = total;
        }
        
        function updateQuantity(index, newQty) {
            let cart = loadCart();
            if (newQty <= 0) cart.splice(index, 1);
            else cart[index].quantity = newQty;
            saveCart(cart);
        }
        
        function renderCart() {
            const cart = loadCart();
            const container = document.getElementById('cartContent');
            if (cart.length === 0) {
                container.innerHTML = `<div class="text-center py-16 bg-white rounded-2xl"><i class="fas fa-shopping-cart text-5xl text-gray-300 mb-4"></i><p>Your cart is empty</p><a href="/shop" class="inline-block mt-4 text-black underline">Continue Shopping</a></div>`;
                return;
            }
            let subtotal = 0;
            const itemsHtml = cart.map((item, idx) => {
                const total = item.price * item.quantity;
                subtotal += total;
                return `<div class="cart-item bg-white rounded-xl p-4 mb-3 shadow-sm flex gap-4"><img src="${item.image || 'https://images.unsplash.com/photo-1534030347209-467a5b0ad3e6?w=80&h=80&fit=crop'}" class="w-20 h-20 rounded-lg object-cover"><div class="flex-1"><h3 class="font-semibold">${item.name}</h3><p class="text-sm text-gray-500">Size: ${item.size || 'M'}</p><p class="text-black font-semibold">$${item.price}</p></div><div><div class="flex items-center gap-2"><button class="cart-dec w-8 h-8 rounded-full border" data-index="${idx}">−</button><span class="w-8 text-center">${item.quantity}</span><button class="cart-inc w-8 h-8 rounded-full border" data-index="${idx}">+</button></div><button class="cart-remove text-red-500 text-sm mt-2" data-index="${idx}">Remove</button></div><div class="font-semibold">$${total}</div></div>`;
            }).join('');
            container.innerHTML = `<div class="space-y-3">${itemsHtml}</div><div class="bg-white rounded-xl p-5 mt-6"><div class="flex justify-between mb-2"><span>Subtotal</span><span>$${subtotal}</span></div><div class="flex justify-between border-b pb-3 mb-3"><span>Shipping</span><span>Free</span></div><div class="flex justify-between font-bold text-lg"><span>Total</span><span>$${subtotal}</span></div><a href="checkout.html" class="block w-full bg-black text-white text-center py-3 rounded-full mt-4 font-semibold">Proceed to Checkout →</a></div>`;
            
            document.querySelectorAll('.cart-dec').forEach(btn => btn.onclick = () => { const i = parseInt(btn.dataset.index); let c = loadCart(); updateQuantity(i, c[i].quantity - 1); });
            document.querySelectorAll('.cart-inc').forEach(btn => btn.onclick = () => { const i = parseInt(btn.dataset.index); let c = loadCart(); updateQuantity(i, c[i].quantity + 1); });
            document.querySelectorAll('.cart-remove').forEach(btn => btn.onclick = () => { const i = parseInt(btn.dataset.index); updateQuantity(i, 0); });
        }
        
        updateCounts();
        renderCart();
    </script>
@endsection