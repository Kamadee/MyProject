document.addEventListener("DOMContentLoaded", function () {
    const links = document.querySelectorAll(".nav-link");

    links.forEach((link) => {
        link.addEventListener("click", async function (e) {
            e.preventDefault();
            const csrfToken = document.querySelector(
                'meta[name="csrf-token"]'
            ).content;
            const target = this.getAttribute("title");
            try {
                const target = this.getAttribute("title");
                if (!target) return;
                const response = await fetch(
                    `{{ route('customer.${target}') }}`,
                    {
                        method: "GET",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrfToken,
                        },
                    }
                );
                console.log(response);
                if (response.ok) {
                    const html = await response.text();
                    document.getElementById("content-area").innerHTML = html; // Thay thế nội dung
                } else {
                    console.error(
                        `Failed to fetch Explore page: ${response.status}`
                    );
                }
            } catch (error) {
                console.error(`Error: ${error}`);
            }
        });
    });
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
$(document).ready(function () {
    $(".navbar-nav .nav-item a").on("click", function () {
        console.log("Đã nhấp vào:", $(this).text()); // Kiểm tra xem click có hoạt động không

        // Loại bỏ lớp 'active' khỏi tất cả các mục
        $(".navbar-nav .nav-item a").removeClass("active");

        // Thêm lớp 'active' cho mục được nhấp
        $(this).addClass("active");
    });
});

// Hiển thị notification số lượng sp trong cart
document.addEventListener("DOMContentLoaded", async function () {
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
