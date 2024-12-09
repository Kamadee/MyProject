@extends('layout.master')

@section('title', 'detail Product')

@section('styles')
<link href="{{ asset('css/detailProduct.css') }}" rel="stylesheet">

@stop

@section('content')
<div class="detail-product-wrapper">
    <div class="detail-product-img">
        <img src="{{ $product->thumbnail_url }}" class="img-product">
    </div>
    <div class="detail-product-info">

        <h2 style="font-weight: 400; font-size: 32px">{{ $product->product_name }}</h2>

        <h4 style="color: #807D7E">{{ $categoryName }}</h4>
        <p class="rating" style="color: #807D7E">
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
            <span class="fa fa-star checked"></span>
            <img style="padding-left: 25px" src="{{ asset('images/icons/message.png') }}">
            120 COMMENTS
        </p>
        <p style=" word-spacing: 3px; color: #807D7E"><b style="font-weight: 600; padding-right:15px; color: black">SELECT SIZE</b> SIZE GUIDE <img style="padding-left: 12px" src="{{ asset('images/icons/arrow.png') }}"></p>

        @if(Auth::check())
        <form action="{{ route('customer.addCart', ['productId' => $product->id]) }}" method="POST" id="main-form">
            @csrf
            <div>
                @foreach ($sizes as $size)
                <input type="radio" name="size" value="{{ $size->size }}" id="size_{{ $size->size }}" class="size-radio" required>
                <label for="size_{{ $size->size }}" class="size-button">{{ $size->size }}</label>
                @endforeach
            </div>
            <p><b style="font-weight: 600;">COLOUR AVAILABLE</b></p>
            <div class="color-picker">
                @foreach ($colors as $color)
                <input type="radio" name="color" value="{{ $color->color }}" id="color_{{ $color->color }}" class="color-radio" required>
                <label for="color_{{ $color->color }}" class="color-option" style="background-color: {{ $color->color }}"></label>
                @endforeach
            </div>
            <div class="button-cart">
                <button type="submit" class="btn btn-dark" formaction="{{ route('customer.addCart', ['productId' => $product->id]) }}">
                    <span class="button-text">ADD TO CART</span>
                </button>
                <div class="price-box">{{ $product->price }}</div>
            </div>
            <div class="button-cart">
                <button type="submit" class="btn btn-dark" formaction="{{ route('customer.addOrder', ['productId' => $product->id]) }}">
                    <span class="button-text">BUY NOW</span>
                </button>
            </div>
        </form>
        @else
        <div>
            @foreach ($sizes as $size)
            <input type="radio" name="size" value="{{ $size->size }}" id="size_{{ $size->size }}" class="size-radio" required>
            <label for="size_{{ $size->size }}" class="size-button">{{ $size->size }}</label>
            @endforeach
        </div>
        <p><b style="font-weight: 600;">COLOUR AVAILABLE</b></p>
        <div class="color-picker">
            @foreach ($colors as $color)
            <input type="radio" name="color" value="{{ $color->color }}" id="color_{{ $color->color }}" class="color-radio" required>
            <label for="color_{{ $color->color }}" class="color-option" style="background-color: {{ $color->color }}"></label>
            @endforeach
        </div>
        <div class="button-cart">
            <button class="btn btn-dark">
                <span class="button-text">ADD TO CART</span>
            </button>
            <div class="price-box">${{ number_format($product->price, 0) }}</div>
        </div>
        @endif

        <div class="horizontal-line"></div>
        <div class="support">
            <div class="tooltip-container">
                <img src="{{ asset('images/icons/Frame 24.png') }}">
                <span>SECURE PAYMENT</span>
                <div class="tooltip-text">
                    Bảo mật tuyệt đối!
                </div>
            </div>
            <div class="tooltip-container">
                <img src="{{ asset('images/icons/Frame 25.png') }}">
                <span>SIZE & FIT</span>
                <div class="tooltip-text">
                    Đổi trả miễn phí nếu không vừa!
                </div>
            </div>
            <div class="tooltip-container">
                <img src="{{ asset('images/icons/Frame 26.png') }}">
                <span>FREE SHIPPING</span>
                <div class="tooltip-text">
                    Miễn phí ship cho đơn hàng từ 200000$!
                </div>
            </div>
            <div class="tooltip-container">
                <img src="{{ asset('images/icons/Frame 27.png') }}">
                <span>FREE SHIPPING & RETURNS</span>
                <div class="tooltip-text">
                    Đổi trả miễn phí, nếu như sp còn chưa qua sử dụng, giặt!
                </div>
            </div>
        </div>
    </div>
</div>
<main class="container">
    <div class=" p-5 rounded mt-3 text-center">
        <h3 class="bestseller">BESTSELLER</h3>
    </div>
</main>
<div class="container swiper">
    <div class="slide-wrapper">
        <div class="d-flex justify-content-between card-list swiper-wrapper">
            @foreach($bestItems as $product)
            <div class="card-product d-flex flex-column swiper-slide">
                <div class="product-img">
                    <a href="{{ route('product.detailProduct', ['productId' => $product->product_id])}}">
                        <img src="{{ $product->thumbnail_url }}">
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
                <div class="product-price">${{ number_format($product->price, 0) }}</div>
                <div class="product-color">
                    <div class="box-left"></div>
                    <div class="box-right"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="swiper-pagination"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>
@stop

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script>
    document.querySelectorAll("button[formaction='{{route('customer.addCart',['productId ' => $product->id])}}']").forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault(); // Ngăn form submit mặc định

            const form = this.closest("form");
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: formData
                });

                if (response.ok) {
                    const data = await response.json();

                    // Cập nhật số lượng sản phẩm trong badge
                    const cartNotification = document.getElementById("cart-notification");
                    cartNotification.textContent = data.totalItems; // 'totalItems' là số lượng sản phẩm được backend trả về
                    cartNotification.style.display = data.totalItems > 0 ? "flex" : "none";
                } else {
                    console.error("Failed to add to cart");
                }
            } catch (error) {
                console.error("Error:", error);
            }
        });
    });

    const swiper = new Swiper('.slide-wrapper', {
        loop: true,
        grabCursor: true,
        spaceBetween: 30,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
            dynamicBullets: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            0: {
                slidesPerView: 1
            },
            768: {
                slidesPerView: 2
            },
            1024: {
                slidesPerView: 3
            },
        }
    });
</script>

@stop