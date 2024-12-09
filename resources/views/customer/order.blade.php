@extends('layout.master')

@section('title', 'My order')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link href="{{ asset('css/order.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-8">
            <h2>Order Details</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product image</th>
                        <th>Product name</th>
                        <th>Size</th>
                        <th>Color</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($orderItem) && $orderItem)
                    <tr>
                        <td>
                            @if($orderItem->thumbnail_url)
                            <img src="{{ $orderItem->thumbnail_url }}" class="card-image">
                            @endif
                        </td>
                        <td>{{ $orderItem->product_name }}</td>
                        <td>{{ $orderItem->size }}</td>
                        <td>{{ $orderItem->color }}</td>
                        <td>{{ $orderItem->quantity }}</td>
                        <td>${{ $orderItem->price }}</td>
                    </tr>
                    @elseif(isset($orderItems) && $orderItems)
                    @foreach($orderItems as $item)
                    <tr>
                        <td>
                            @if($item->products->thumbnail_url)
                            <img src="{{ $item->products->thumbnail_url }}" class="card-image">
                            @endif
                        </td>
                        <td>{{ $item->products->product_name }}</td>
                        <td>{{ $item->size }}</td>
                        <td>{{ $item->color }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->products->price * $item->quantity }}</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h3>Order Summary</h3>
                    <hr>
                    <p>Total Amount: {{ $totalAmount }}</p>
                    <hr>
                    <h5>Payment Method</h5>
                    <form action="{{ route('customer.placeOrder') }}" method="POST">
                        @csrf
                        <div class="form-check">
                            <label class="container">
                                <input type="radio" name="payment_method" value="credit_card" checked>
                                <span class="checkmark"></span>
                                Credit Card
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="container">
                                <input type="radio" name="payment_method" value="paypal">
                                <span class="checkmark"></span>
                                PayPal
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="container">
                                <input type="radio" name="payment_method" value="cash_on_delivery">
                                <span class="checkmark"></span>
                                Cash on Delivery
                            </label>
                        </div>

                        <input type="hidden" name="orderItem" value="{{ json_encode($orderItem) }}">
                        <input type="hidden" name="orderItems" value="{{ json_encode($orderItems) }}">
                        <input type="hidden" name="totalAmount" value="{{ $totalAmount }}">
                        <input type="hidden" name="size" value="{{ isset($orderItem) && $orderItem->size ? $orderItem->size : '' }}">
                        <input type="hidden" name="color" value="{{ isset($orderItem) && $orderItem->color ? $orderItem->color : '' }}">
                        <input type="hidden" name="productId" value="{{ $orderItem ? $orderItem->id : '' }}">
                        <!-- <input type="hidden" name="payment_method"> -->
                        <button type="submit" class="btn btn-primary btn-block" id="placeOrderButton" onclick="placeOrder()">Place Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // Select all checkboxes
        $('#selectAll').on('click', function() {
            $('.product-checkbox').prop('checked', this.checked);
        });

        // Select individual checkboxes
        $('.product-checkbox').on('click', function() {
            if ($('.product-checkbox:checked').length == $('.product-checkbox').length) {
                $('#selectAll').prop('checked', true);
            } else {
                $('#selectAll').prop('checked', false);
            }
        });
    });
    //lắng nghe sự kiện "change" trên các nút radio, và khi người dùng chọn một phương thức thanh toán, nó sẽ cập nhật giá trị của trường ẩn payment_method tương ứng.
    // document.querySelectorAll('input[name="payment_method"]').forEach(function(radio) {
    //     radio.addEventListener('change', function() {
    //         document.querySelector('input[name="payment_method"]').value = this.value;
    //     });
    // });

    // Gửi các biến như orderItems, orderItem, totalAmount,... từ view order lên controller placeOrder
</script>
@stop