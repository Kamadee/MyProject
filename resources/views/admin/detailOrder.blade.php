@extends('layout.masterAdmin')

@section('title', 'Order detail')

@section('styles')
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

    .container {
        padding-left: 10px;
        margin-left: 300px;
    }
</style>
@stop

@section('content')
<div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">

            <div class="row g-3 mb-4 align-items-center justify-content-between" style="margin-top:50px">
                <div class="col-auto">
                    <h1 class="app-page-title mb-0">Orders Detail #{{$order->id}}</h1>
                </div>
            </div><!--//row-->
            <div class="tab-content" id="orders-table-tab-content">
                <div class="tab-pane fade show active" id="orders-all" role="tabpanel" aria-labelledby="orders-all-tab">
                    <div class="app-card app-card-orders-table shadow-sm mb-5">
                        <div class="app-card-body">
                            <div class="table-responsive">
                                <table class="table app-table-hover mb-0 text-left">
                                    <thead>
                                        <tr>
                                            <th class="cell">Product image</th>
                                            <th class="cell">Product name</th>
                                            <th class="cell">Brand</th>
                                            <th class="cell">Size</th>
                                            <th class="cell">Color</th>
                                            <th class="cell">Quantity</th>
                                            <th class="cell">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->orderItems as $item)
                                        <tr>
                                            <td class="cell">
                                                @if ($item->product->thumbnail_url)
                                                <img src="{{ $item->product->thumbnail_url }}" class="card-image">
                                                @endif
                                            </td>
                                            <td class="cell">{{ $item->product->product_name }}</td>
                                            <td class="cell">{{ $item->product->brand }}</td>
                                            <td class="cell">{{ $item->size }}</td>
                                            <td class="cell">{{ $item->color }}</td>
                                            <td class="cell">{{ $item->quantity }}</td>
                                            <td class="cell">{{ $item->product->price * $item->quantity }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div><!--//table-responsive-->

                        </div><!--//app-card-body-->
                    </div><!--//app-card-->
                    <nav class="app-pagination">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav><!--//app-pagination-->

                </div><!--//tab-pane-->
            </div><!--//tab-content-->



        </div><!--//container-fluid-->
    </div><!--//app-content-->

</div><!--//app-wrapper-->
@stop

@section('scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
@stop