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
