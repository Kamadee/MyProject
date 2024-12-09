@extends('layout.master')

@section('title', 'Home')

@section('styles')
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"> -->
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
  .banner {
    background-image: url("{{ asset('images/banner/young-friends-sitting-curb-city.png') }}");
    width: 100%;
    height: 580px;
    background-size: cover;

    position: relative;
    display: flex;
    justify-content: center;
    /* margin-top: 50px; */
  }

  .banner .banner-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    color: #ffff;
    position: absolute;
    bottom: 100px;
  }

  .season-name {
    font-size: 25px;
    font-weight: 300;
  }

  .tell {
    font-size: 40px;
    font-weight: 430;
  }

  .banner-menu button,
  .banner-menu button:hover {
    background-color: #fff;
    color: black;
    font-size: 20px;
  }

  .container {
    padding: 20px;
  }

  .product-img {
    height: 468px;
    width: 100%;
    position: relative !important;
  }

  .product-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    position: relative;
  }

  .product-name {
    text-align: center;
  }

  .product-price {
    text-align: center;
  }

  .product-color {
    display: flex;
    flex-direction: row;
    justify-content: center;
    margin-top: 5px;
  }

  .box-left {
    border-width: 3px;
    margin-right: 5px;
    width: 30px;
    height: 30px;
    background-color: #AB6B26;

  }

  .box-right {
    border-width: 3px;
    margin-left: 5px;
    width: 30px;
    height: 30px;
    background-color: #1E262E;
  }

  .box {
    width: 30px;
    height: 30px;
  }

  .favorite-icon {
    position: absolute !important;
    top: 10px;
    left: 10px;
    padding: 5px;
    font-size: 20px;
    border-radius: 50%;
    cursor: pointer;


  }

  .favorite-icon i {
    color: #ff6666;
  }

  .favorite-icon.active {
    border: none;
    /* Thêm border màu đen khi active */
  }

  .favorite-icon.active i {
    color: black;
  }

  .match-table {
    margin-top: 20px;
  }

  .bestseller {
    font-weight: 350;
  }

  .collection {
    background-image: url("{{ asset('images/banner/2149003088.png') }}");
    width: 100%;
    height: 580px;
    background-size: cover;

    position: relative;
    display: flex;
    justify-content: center;
    margin-top: 40px;
    margin-bottom: 100px;
  }

  .collection .collection-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    color: #ffff;
    position: absolute;
    bottom: 100px;
  }

  hr {
    border: none;
    height: 2px;
    background-color: rgb(83, 74, 74);
    margin-top: 70px;
  }
</style>
@stop

@section('content')
<div class="banner">
  <div class="banner-wrapper">
    <div class="season-name">
      <span>SPRING SUMMER</span>
    </div>
    <div class="tell">
      <span>TELL ME MORE</span>
    </div>
    <div class="banner-menu">
      <button class="btn btn-default">SHOP MEN</button>
      <button class="btn btn-default">SHOP WOMEN</button>
    </div>
  </div>
</div>

<main class="container">
  <div class=" p-5 rounded mt-3 text-center">
    <h3 class="bestseller">BESTSELLER</h3>
    <a href="{{ route('product.listProduct') }}"><button type="button" id="myButton" class="btn btn-outline-secondary">VIEW ALL</button></a>
  </div>
</main>

<div class="container swiper">
  <div class="slide-wrapper">
    <div class="d-flex justify-content-between card-list swiper-wrapper">
      @foreach($products as $product)
      <div class="card-product d-flex flex-column  swiper-slide">
        <div class="product-img">
          <a href="{{ route('product.detailProduct', ['productId' => $product->id])}}">
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
        <div class="product-price">${{ number_format($product->price,0) }}</div>
        <div class="product-color">
          <div class="box-left"></div>
          <div class="box-right"></div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  <div class="swiper-pagination"></div>

  <!-- If we need navigation buttons -->
  <div class="swiper-button-prev"></div>
  <div class="swiper-button-next"></div>
</div>

<div class="collection">
  <div class="collection-wrapper">
    <div class="season-name">
      <span>NEW IN</span>
    </div>
    <div class="tell">
      <span>STUDIO COLLECTION</span>
    </div>
    <div class="banner-menu">
      <button class="btn btn-default">SHOP NOW</button>
    </div>
  </div>
</div>

<div class="container swiper">
  <div class="slide-wrapper">
    <div class="d-flex justify-content-between card-list swiper-wrapper">
      @foreach($products as $product)
      <div class="card-product d-flex flex-column  swiper-slide">
        <div class="product-img">
          <a href="{{ route('product.detailProduct', ['productId' => $product->id])}}">
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
        <div class="product-price">{{ $product->price }}</div>
        <div class="product-color">
          <div class="box-left"></div>
          <div class="box-right"></div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  <div class="swiper-pagination"></div>

  <!-- If we need navigation buttons -->
  <div class="swiper-button-prev"></div>
  <div class="swiper-button-next"></div>
</div>

@stop

@section('scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  // Xử lý sự kiện click icon yêu thích
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
          // // Thêm hiệu ứng
          // favourite.classList.add('clicked');
          // setTimeout(() => {
          //   favourite.classList.remove('clicked');
          // }, 50);
        } else if (response.status === 'removed') {
          icon.classList.remove('fas');
          icon.classList.add('far');
          favourite.setAttribute('data-is-favorite', false);
        }
      } catch (err) {
        console.error(`${err}`);
        alert("Có lỗi xảy ra khi xử lý yêu thích. Vui lòng thử lại.");
      }
    });
  });

  // $(document).ready(function() {
  //   $('.favorite-icon').on('click', function() {
  //     var $icon = $(this).find('i');
  //     var productId = $(this).data('product-id');
  //     var isFavorite = $(this).data('is-favorite');

  //     $.ajax({
  //       url: "{!! route('customer.favourite.toggle') !!}",
  //       method: 'POST',
  //       data: {
  //         _token: "{{ csrf_token() }}",
  //         productId: productId
  //       },
  //       success: function(response) {
  //         if (response.status === 'added') {
  //           $icon.removeClass('far').addClass('fas');
  //           $icon.closest('.favorite-icon').data('is-favorite', true);
  //         } else if (response.status === 'removed') {
  //           $icon.removeClass('fas').addClass('far');
  //           $icon.closest('.favorite-icon').data('is-favorite', false);
  //         }
  //       },
  //       error: function() {
  //         alert('Có lỗi xảy ra. Vui lòng thử lại.');
  //       }
  //     });
  //   });
  // });

  // Js carousel
  const swiper = new Swiper('.slide-wrapper', {
    loop: true,
    grabCursor: true,
    spaceBetween: 30,

    // If we need pagination
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
      dynamicBullets: true,
    },

    // Navigation arrows
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },

    // Responsive breakpoints
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
      1024: {
        slidesPerView: 4
      }
    }
  });
</script>
@stop