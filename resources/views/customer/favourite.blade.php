@extends('layout.master')

@section('title', ' List product')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link href="{{ asset('css/favourite.css') }}" rel="stylesheet">
@stop

@section('content')

<body>
    <main class="container">
        <div class=" p-5 rounded mt-3 text-center">
            <button type="button" class="btn btn-outline-secondary">Filter</button>
        </div>
    </main>

    <div class="container">
        <div class="product-list">
            @foreach($favouriteProducts as $product)
            <div class="product">
                <div class="product-img">
                    <a href="{{ route('product.detailProduct', ['productId' => $product->id])}}">
                        <img src="{{ $product->thumbnail_url }}" alt="">
                        @if(Auth::check())
                        @php
                        $productExist = \App\Models\Favourite::where([
                        'product_id' => $product->id,
                        'user_id' => Auth::id()
                        ])->exists();
                        @endphp
                        <a class="favorite-icon" href="javascript:void(0);" data-product-id="{{ $product->id }}" data-is-favorite="{{ $productExist }}">
                            @if($productExist)
                            <i class="fas fa-heart"></i>
                            @else
                            <i class="far fa-heart"></i>
                            @endif
                        </a>
                        @endif
                    </a>
                </div>
                <div class="product-name">{{ $product->product_name }}</div>
                <div class="product-price">{{ $product->price }}</div>
                <div class="product-color">
                    <div class="box-left"></div>
                    <div class="box-right"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <ul class="pagination container_pagination">
        <li class="pagination-item">
            <a href="" class="pagination-item_link">
                <i class="pagination-item_icon fas fa-angle-left"></i>
            </a>
        </li>
        <li class="pagination-item pagination-item--active">
            <a href="" class="pagination-item_link">1</a>
        </li>
        <li class="pagination-item">
            <a href="" class="pagination-item_link">2</a>
        </li>
        <li class="pagination-item">
            <a href="" class="pagination-item_link">3</a>
        </li>
        <li class="pagination-item">
            <a href="" class="pagination-item_link">4</a>
        </li>
        <li class="pagination-item">
            <a href="" class="pagination-item_link">...</a>
        </li>
        <li class="pagination-item">
            <a href="" class="pagination-item_link">14</a>
        </li>
        <li class="pagination-item">
            <a href="" class="pagination-item_link">
                <i class="pagination-item_icon fas fa-angle-right"></i>
            </a>
        </li>
    </ul>
    <div class="container">
        <hr>
    </div>
</body>


@stop

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Xử lý sự kiện click icon yêu thích sp
    const favourites = document.querySelectorAll('a.favorite-icon');
    favourites.forEach(favourite => {
        favourite.addEventListener('click', async function() {
            try {
                const icon = favourite.querySelector('i'); // Lấy icon liên quan tới nút này
                const productId = favourite.getAttribute('data-product-id');
                const isFavorite = favourite.getAttribute('data-is-favorite');

                const response = await $.ajax({
                    url: "{!! route('customer.favourite.toggle') !!}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        productId: productId
                    },
                });

                if (response.status === 'added') {
                    icon.classList.add('fas');
                    icon.classList.remove('far');
                    favourite.setAttribute('data-is-favorite', true);
                } else if (response.status === 'removed') {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    favourite.setAttribute('data-is-favorite', false);
                    const product = favourite.closest('.product');
                    if (product) product.remove();
                }
            } catch (err) {
                console.error(`${err}`);
                alert("Có lỗi xảy ra khi xử lý yêu thích. Vui lòng thử lại.");
            }
        });
    });
</script>
@stop