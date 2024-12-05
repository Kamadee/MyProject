@extends('layout.masterAdmin')

@section('title', 'Add Product')

@section('styles')
<style>
    body {
        margin-top: 20px;
        background-color: #f2f6fc;
        color: #69707a;
    }

    .img-account-profile {
        height: 50%;
        width: 50%;
    }

    .rounded-circle {
        border-radius: 50% !important;
    }

    .card {
        box-shadow: 0 0.15rem 1.75rem 0 rgb(33 40 50 / 15%);
    }

    .card .card-header {
        font-weight: 500;
    }

    .card-header:first-child {
        border-radius: 0.35rem 0.35rem 0 0;
    }

    .card-header {
        padding: 1rem 1.35rem;
        margin-bottom: 0;
        background-color: rgba(33, 40, 50, 0.03);
        border-bottom: 1px solid rgba(33, 40, 50, 0.125);
    }

    .form-control,
    .dataTable-input {
        display: block;
        width: 100%;
        padding: 0.875rem 1.125rem;
        font-size: 0.875rem;
        font-weight: 400;
        line-height: 1;
        color: #69707a;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #c5ccd6;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        border-radius: 0.35rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .nav-borders .nav-link.active {
        color: #0061f2;
        border-bottom-color: #0061f2;
    }

    .nav-borders .nav-link {
        color: #69707a;
        border-bottom-width: 0.125rem;
        border-bottom-style: solid;
        border-bottom-color: transparent;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        padding-left: 0;
        padding-right: 0;
        margin-left: 1rem;
        margin-right: 1rem;
    }
</style>
@stop

@section('content')
<div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">

            <div class="row g-3 mb-4 align-items-center justify-content-between" style="margin-top:50px">
                <div class="col-auto">
                    <h1 class="app-page-title mb-0">View product</h1>
                </div><!--//col-auto-->
            </div><!--//row-->
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form @if(isset($product)) action="{{ route('admin.saveEdit', ['productId' => $product->id]) }}"
                @else action="{{ route('admin.saveAdd') }}"
                @endif method="POST">
                @csrf
                <div class="mb-3">
                    <label class="small mb-1" for="a">Product name</label>
                    <input <?php if (isset($product)) { ?> value="<?php echo $product->product_name ?>" <?php } ?>
                        class="form-control"
                        name="product_name" id="a" type="text">
                </div>
                <div class="mb-3">
                    <label class="small mb-1" for="b">Brand</label>
                    <input <?php if (isset($product)) { ?> value="<?php echo $product->brand ?>" <?php } ?>
                        class="form-control"
                        name="brand" id="b" type="text">
                </div>
                <div class="mb-3">
                    <label class="small mb-1" for="c">Price</label>
                    <input <?php if (isset($product)) { ?> value="<?php echo $product->price ?>" <?php } ?>
                        class="form-control"
                        name="price" id="c" type="text">
                </div>
                <div class="mb-3">
                    <label class="small mb-1" for="d">Product image</label>
                    <input <?php if (isset($product)) { ?> value="<?php echo $product->thumbnail_url ?>" <?php } ?>
                        class="form-control"
                        name="thumbnail_url" id="d" type="text">
                </div>
                <!-- Form Row-->
                <!-- Form Group (email address)-->
                <button class="btn btn-primary" type="submit">Save edit</button>
            </form>

            <!-- Form Group (username)-->


        </div><!--//container-fluid-->
    </div><!--//app-content-->
</div><!--//app-wrapper-->


@stop

@section('scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
@stop