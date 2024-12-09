<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Route -->
    <meta name="profile-route" content="{{ route('customer.profile') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{--CSRF Token--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', '@Master Layout'))</title>

    {{--Styles css common--}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    @yield('style-libraries')
    {{--Styles custom--}}
    @yield('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- admin -->
    <link rel="shortcut icon" href="favicon.ico">

    <!-- FontAwesome JS-->
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>
    <script src="{{ asset('js/global.js') }}"></script>
    <style>
        .container {
            margin-bottom: 0;
        }

        .navbar {
            font-size: 16px;
            font-weight: 400;
            background-color: white;
        }

        .navbar-brand {
            color: #100d0d !important;
            font-weight: 700;
            font-size: 20px;
        }

        .end-header {
            width: 100%;
            height: 32px;
            background-color: #100d0d;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 9px;
            /* margin-top: -20px; */
        }

        .start-footer {
            width: 100%;
            border-bottom-color: #D9D9D9;
            margin-bottom: 40px;
            padding-top: 50px;
        }

        .cart {
            align-items: center;
            display: flex;
            margin-left: 5px;
        }

        .icon-tool {
            display: flex;
            gap: 0 10px;
        }

        .menu-list li a {
            color: #100d0d !important;
            font-weight: 400;
        }

        .search-form-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            perspective: 1000px;
        }

        .search-form {
            display: none;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 999;
            transform-origin: top center;
            transition: transform 0.3s ease-in-out;
        }

        .search-form.show {
            display: block;
            transform: translateY(0);
            opacity: 1;
        }

        .search-form.hide {
            transform: translateY(-10%) scale(0.95);
            opacity: 0;
        }

        .search-form .input-group {
            display: flex;
            align-items: center;
        }

        .search-form .input-group input {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .search-form .input-group button {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .navbar-nav .nav-item a.active {
            font-weight: bold !important;
            color: #000 !important;
            /* Đổi màu chữ nếu cần */
        }

        /* Chỉnh cho các mục trên thanh nav */
        .navbar-nav .nav-item .nav-link {
            position: relative;
            font-weight: normal;
            /* Đặt trạng thái mặc định cho chữ */
            transition: all 0s ease;
        }

        .navbar-nav .nav-item .nav-link:hover {
            font-weight: bold;
            /* In đậm chữ khi di chuột vào */
            color: #000;
            /* Đổi màu chữ nếu muốn */
        }

        /* Hiệu ứng gạch dưới với chuyển động */
        .navbar-nav .nav-item .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background-color: #000;
            left: 0;
            bottom: -5px;
            /* Điều chỉnh vị trí của gạch dưới */
            transition: width 0.1s ease;
        }

        .navbar-nav .nav-item .nav-link:hover::after {
            width: 100%;
            /* Mở rộng gạch dưới khi di chuột vào */
        }

        /* Không áp dụng hiệu ứng cho GLAMIFY */
        .navbar-brand {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 1.5rem;
        }

        /* Chỉnh cho các icon */
        .icon-tool .nav-item .nav-link {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 10px;
        }

        .icon-tool .nav-item .nav-link:hover::after {
            content: attr(title);
            /* Lấy nội dung từ thuộc tính title để hiển thị */
            position: absolute;
            bottom: -20px;
            /* Vị trí của chữ chú thích */
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 1;
            transition: opacity 0s ease;
            z-index: 10;
        }

        .icon-tool .nav-item .nav-link:hover::before {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background-color: #000;
            /* Màu của gạch dưới */
            left: 0;
            bottom: -5px;
            /* Điều chỉnh vị trí của gạch dưới */
            transition: width 0s ease;
        }

        .icon-tool .nav-item .nav-link:hover::before {
            width: 100%;
            /* Mở rộng gạch dưới khi di chuột vào */
        }

        /* Điều chỉnh vị trí và kích thước của icon */
        .icon-tool .nav-item .nav-link img {
            width: 24px;
            height: 24px;
            transition: transform 0.3s ease;
        }

        .icon-tool .nav-item .nav-link:hover img {
            transform: scale(1);
            /* Phóng to icon khi di chuột vào */
        }

        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            background-color: red;
            color: white;
            font-size: 12px;
            font-weight: bold;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: translate(50%, -50%);
            z-index: 10;
        }
    </style>
</head>

<body>
    @include('partials.header')


    @yield('content') <!-- Đây là phần nội dung của từng trang con -->


    @include('partials.footer')

    {{--Scripts js common--}}
    {{--Scripts link to file or js custom--}}
    @yield('scripts')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        document.addEventListener("DOMContentLoaded", async function() {
            const notification = document.getElementById("cart-notification");
            try {
                const response = await fetch("{{ route('customer.cartCount') }}", {
                    method: "GET",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                    },
                });
                if (response.ok) {
                    const data = await response.json();
                    notification.textContent = data.cartCount;
                }
            } catch (error) {
                console.log(`${error}`);
            }
        });
        // Xử lý event click icon tìm kiếm
        function toggleSearchForm() {
            var searchForm = document.getElementById("search-form");
            if (searchForm.style.display === "none") {
                searchForm.style.display = "block";
            } else {
                searchForm.style.display = "none";
            }
        }
        $(document).ready(function() {
            $(".navbar-nav .nav-item a").on("click", function() {
                console.log("Đã nhấp vào:", $(this).text()); // Kiểm tra xem click có hoạt động không

                // Loại bỏ lớp 'active' khỏi tất cả các mục
                $(".navbar-nav .nav-item a").removeClass("active");

                // Thêm lớp 'active' cho mục được nhấp
                $(this).addClass("active");
            });
        });
        // Hiển thị notification số lượng sp trong cart
    </script>
</body>

</html>