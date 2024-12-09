<div class="container">
  <nav class="navbar navbar-expand-lg navbar-light bg-white">
    <a class="navbar-brand" href="{{ route('product.homeShop') }}">GLAMIFY</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto menu-list">
        <li class="nav-item">
          <a class="nav-link" href="">Men</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Girl</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Blog</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('product.searchProduct')}}" title="">Explore</a>
        </li>
      </ul>
    </div>

    <div>
      <ul class=" navbar-nav mr-auto icon-tool">
        <li class="nav-item active">
          <a class="nav-link" href="#" onclick="toggleSearchForm()" title="Search">
            <i class="fa-solid fa-magnifying-glass"></i>
          </a>
        </li>
        <li class="nav-item">
          @if(Auth::check())
          <a class="nav-link" href="{{ route('customer.profile') }}" title="profile">
            {{Auth::user()->name}}
          </a>
          @else
          <a class="nav-link" href="{{ route('authentication.login') }}" title="profile">
            <i class="fa-regular fa-user"></i>
          </a>
          @endif
        </li>
        <li class="nav-item">
          @if(Auth::check())
          <a class="nav-link" href="{{ route('customer.favourite') }}" title="favourite">
            <i class="fa-regular fa-heart"></i>
          </a>
          @else
          <a class="nav-link" href="{{ route('authentication.login') }}" title="favourite">
            <i class="fa-regular fa-heart"></i>
          </a>
          @endif
        </li>
        <li class="nav-item position-relative">
          @if(Auth::check())
          <a class="nav-link" href="{{ route('customer.cart') }}" title="cart">
            <i class="fa-solid fa-cart-shopping"></i>
            <span id="cart-notification" class="notification-badge">3</span>
          </a>
          @else
          <a class="nav-link" href="{{ route('authentication.login') }}" title="cart">
            <i class="fa-solid fa-cart-shopping"></i>
          </a>
          @endif
        </li>


      </ul>
    </div>
  </nav>
</div>


<div class="search-form" id="search-form" style="display: none; text-align: center;">
  <form action="{{ route('product.search') }}" method="GET" style="display: inline-block;">
    <div class="input-group" style="width: 800px;">
      <input type="text" class="form-control" name="keyword" placeholder="Search...">
      <div class="input-group-append">
        <button class="btn btn-outline-secondary" type="submit">
          <i class="fas fa-search"></i>
        </button>
      </div>
    </div>
  </form>
</div>



<div class="end-header">

</div>