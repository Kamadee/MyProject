@extends('layout.master')

@section('title', 'My order')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link href="{{ asset('css/order.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-12 mb-3">
            <div class="d-flex align-items-center mb-3">
                <i class="fas fa-check-circle text-success mr-2" style="font-size: 24px;"></i>
                <h4 class="mb-0">Order Success</h4>
            </div>
        </div>
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
                    @if(isset($orderItem->size) && isset($orderItem->color) && isset($orderItem->quantity) && isset($orderItem->price))
                    <tr>
                        <td>
                            @if($orderItem->product->thumbnail_url)
                            <img src="{{ $orderItem->product->thumbnail_url }}" class="card-image">
                            @endif
                        </td>
                        <td>{{ $orderItem->product->product_name }}</td>
                        <td>{{ $orderItem->size }}</td>
                        <td>{{ $orderItem->color }}</td>
                        <td>{{ $orderItem->quantity }}</td>
                        <td>{{ $orderItem->product->price }}</td>
                    </tr>
                    @elseif(isset($orderItems) && $orderItems)
                    @foreach($orderItems as $item)
                    <tr>
                        <td>
                            @if($item->product->thumbnail_url)
                            <img src="{{ $item->product->thumbnail_url }}" class="card-image">
                            @endif
                        </td>
                        <td>{{ $item->product->product_name }}</td>
                        <td>{{ $item->size }}</td>
                        <td>{{ $item->color }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ $item->product->price * $item->quantity }}</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
            <hr>
            <p>Total Amount: {{ $order->total_amount }}</p>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h3>Consignee Information</h3>
                    <hr>
                    <ul>
                        <li>Name: {{ $order->user->name }}</li>
                        <li>Phone: {{ $order->user->phone }}</li>
                        <li>Address: {{ $order->user->address }}</li>
                        <li>Order date: {{ $order->date_order }}</li>
                        <li>Receive date: {{ $order->received_date }}</li>
                        <li>Payment method: {{ $order->payment_method }}</li>
                    </ul>
                    <button>Print bill</button>
                    <button>Continue shoping</button>
                </div>
            </div>
        </div>
    </div>
</div>
@stop