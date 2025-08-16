document.addEventListener("DOMContentLoaded", function () {
    // bagian button refresh transaksi
    const btnRefreshTransaksi = document.getElementById("btnRefreshTransaksi");
    btnRefreshTransaksi.addEventListener("click", function () {
        btnRefreshTransaksi.disabled = true;

        const loadingIcon = document.createElement("i");
        loadingIcon.className = "fas fa-spinner fa-spin";
        btnRefreshTransaksi.appendChild(loadingIcon);

        let url = new URL(window.location.href);
        // hapus fragment/hash
        url.hash = "";
        url.searchParams.set("getAjax", "1");
        let urlAjax = url.toString();

        fetch(urlAjax, {
            headers: {
                "X-Requested-With": "XMLHttpRequest", // penting supaya request()->ajax() == true
            },
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.text();
            })
            .then((html) => {
                // console.log("response 2", html);
                document.getElementById("transaksiContainer").innerHTML = html;
            })
            .catch((err) => {
                console.error("Fetch error:", err);
            })
            .finally(() => {
                btnRefreshTransaksi.disabled = false;
                loadingIcon.remove();
            });
    });
    // end button refresh transaksi

    // bagian filter
    const btnFilter = document.getElementById("btnFilter");
    btnFilter.addEventListener("click", function () {
        const filterBy = document.getElementById("filterBy").value;
        const filterKeyword = document.getElementById("filterKeyword").value;

        let url = new URL(window.location.href);
        url.hash = "";
        url.searchParams.set("getAjax", "1");
        url.searchParams.set("filterBy", filterBy);
        url.searchParams.set("filterKeyword", filterKeyword);
        let urlAjax = url.toString();

        fetch(urlAjax, {
            headers: {
                "X-Requested-With": "XMLHttpRequest",
            },
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.text();
            })
            .then((html) => {
                document.getElementById("transaksiContainer").innerHTML = html;
            })
            .catch((err) => {
                console.error("Fetch error:", err);
            });
    });
    // end bagian filter
});
