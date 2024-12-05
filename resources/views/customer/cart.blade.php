@extends('layout.master')

@section('title', 'My cart')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
    .match-table {
        margin-top: 25px;
    }

    .card-image {
        width: 15%;
        height: 15%;
    }

    .td {
        width: 27%;
    }
</style>
@stop

@section('content')
<div class="container">
    <table class="table ">
        <thead class="thead-light">
            <tr>
                <th><input type="checkbox" id="select-all"> <label for="select-all">Select All</label></th>
                <th>Product image</th>
                <th>Product name</th>
                <th>Size</th>
                <th>Color</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($carts as $cart)
            <tr class="table-row">
                <td><input type="checkbox" class="product-checkbox" value="{{ $cart->id }}" onchange="toggleProductSelection(this)"></td>
                <td class="td">
                    @if($cart->thumbnail_url)
                    <img src="{{ $cart->thumbnail_url }}" class="card-image">
                    @endif
                </td>
                <td>{{ $cart->products->product_name }}</td>
                <td>{{ $cart->size }}</td>
                <td>{{ $cart->color  }}</td>
                <td>
                    <input type="number" name="quantity" value="{{ $cart->quantity }}" min="1" data-price="{{ $cart->products->price }}" data-cart-id="{{ $cart->id }}" class="form-control quantity-input" style="width: 60px;">
                </td>
                <td id="product-total-{{ $cart->id }}">{{ number_format($cart->products->price * $cart->quantity,2) }}</td>
                <td>
                    <form action="{{ route('customer.deleteCart') }}" method="POST" onsubmit="return confirm('Bạn có muốn bỏ sp khỏi giỏ?')">
                        @csrf
                        <input type="hidden" name="id" value="{{ $cart->id }}">
                        <input type="hidden" name="userId" value="{{ Auth::user()->id }}">
                        <button type="submit" class="text-danger" style="border: none;"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach

        </tbody>
    </table>
    <form action="{{ route('customer.addOrder') }}" method="POST">
        @csrf
        <input type="hidden" name="selectedProducts" id="selectedProducts">
        <div class="text-right mb-3">
            <button class="btn btn-primary" type="submit">Buy now</button>
        </div>
    </form>

</div>
@stop

@section('scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script>
    function setLocalStorage() {
        localStorage.setItem("selectedProducts", JSON.stringify(selectedProducts));
    }
    // Lưu trạng thái tích chọn của các sản phẩm vào một mảng
    function toggleProductSelection(checkbox) {
        let selectedProducts = JSON.parse(localStorage.getItem('selectedProducts')) || [];
        const cartId = checkbox.value;
        console.log(cartId);
        if (checkbox.checked) {
            selectedProducts.push(cartId);
        } else {
            const index = selectedProducts.indexOf(cartId);
            if (index !== -1) {
                selectedProducts.splice(index, 1);
            }
        }
        setLocalStorage();
        document.getElementById('selectedProducts').value = selectedProducts.join(',');
    }

    selectedProducts = [];
    // Xử lý sự kiện click vào "Select All" (chọn tất cả sp trong giỏ)
    document.addEventListener("DOMContentLoaded", function() {
        const selectAllCheckbox = document.getElementById('select-all');
        const productCheckboxes = document.querySelectorAll('.product-checkbox');

        // Xử lý sự kiện click vào "Select All"
        selectAllCheckbox.addEventListener('change', function() {
            if (selectAllCheckbox.checked) {
                // Clear the selectedProductIds array
                productCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = selectAllCheckbox.checked;
                    if (checkbox.checked) {
                        selectedProducts.push(checkbox.value);
                    }
                });
            } else {
                productCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = false
                    var index = selectedProducts.indexOf(checkbox.value);
                    if (index !== -1) {
                        selectedProducts.splice(index, 1);
                    }
                });
            }
            setLocalStorage();
            document.getElementById('selectedProducts').value = selectedProducts.join(',');
        });


        // Cần fix lại!! Cập nhật trạng thái của "Select All" khi có sự thay đổi trong các checkbox sản phẩm
        productCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                if (!checkbox.checked) {
                    selectAllCheckbox.checked = false;
                    var index = selectedProducts.indexOf(checkbox.value);
                    if (index !== -1) {
                        selectedProducts.splice(index, 1);
                    }
                } else {
                    selectedProducts.push(checkbox.value);
                    const allChecked = Array.from(productCheckboxes).every(cb => cb.checked);
                    selectAllCheckbox.checked = allChecked;
                }
                document.getElementById('selectedProducts').value = selectedProducts.join(',');
            });
        });
    });

    // Cập nhật giá sp sau khi thay đổi số lượng sp
    document.addEventListener("DOMContentLoaded", function() {
        const inputQuantitys = document.querySelectorAll('.quantity-input')
        inputQuantitys.forEach(input => {
            input.addEventListener('input', async function() {
                const cartId = this.getAttribute('data-cart-id');
                const price = parseFloat(this.getAttribute('data-price'));
                const quantity = parseInt(this.value) || 1;
                const newTotal = price * quantity;
                document.getElementById(`product-total-${cartId}`).textContent = newTotal;

                try {
                    const response = await fetch("{{ route('customer.updateCart') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            id: cartId,
                            quantity: quantity
                        })
                    });

                } catch (err) {
                    console.error(`${err}`);
                }
            });
            input.addEventListener('blur', function() {
                if (this.value < 1 || !this.value) {
                    this.value = 1; // Không cho phép giá trị nhỏ hơn 1
                    const cartId = this.getAttribute('data-cart-id');
                    const price = parseFloat(this.getAttribute('data-price'));
                    const newTotal = price * this.value;
                    document.getElementById(`product-total-${cartId}`).textContent = newTotal;
                }
            });
        })
    })
</script>
@stop
<!-- <td>
    <input type="number"
        name="quantity"
        value="{{ $cart->quantity }}" min="1"
        data-price="{{ $cart->products->price }}"
        data-cart-id="{{ $cart->id }}"
        class="form-control quantity-input" style="width: 60px;">
</td>
<td id="product-total-{{ $cart->id }}">{{ number_format($cart->products->price * $cart->quantity,2) }}</td> -->