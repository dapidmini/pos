document.addEventListener("DOMContentLoaded", function () {
    // bagian button refresh transaksi
    const btnRefreshTransaksi = document.getElementById("btnRefreshTransaksi");
    if (btnRefreshTransaksi) {
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
    }
    // end button refresh transaksi

    // bagian filter di halaman index transaksi
    const btnFilter = document.getElementById("btnFilter");
    if (btnFilter) {
        btnFilter.addEventListener("click", function () {
            const filterBy = document.getElementById("filterBy").value;
            const filterKeyword = document.getElementById("filterKeyword").value;

            if (filterKeyword === "") {
                return false;
            }

            let url = new URL(window.location.href);
            url.hash = "";
            url.searchParams.set("getAjax", "1");
            url.searchParams.set("filterBy", filterBy);
            url.searchParams.set("filterKeyword", filterKeyword);
            let urlAjax = url.toString();
            alert(urlAjax);
    
            fetch(urlAjax, {
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
            })
            .then((response) => {
                console.log('response', response);
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
    }
    // end bagian filter di halaman index transaksi

    // bagian filter List Produk halaman Show Transaksi
    const btnFilterListProduk = document.getElementById("btnFilterListProduk");
    if (btnFilterListProduk) {
        btnFilterListProduk.addEventListener("click", function () {
            const dataContainer = document.querySelector("#transaksiContainer #dataContainer");
            // hilangkan .rowProduk-empty
            const getEmptyRow = dataContainer.querySelector("tbody tr.rowProduk-empty");
            if (getEmptyRow) {
                getEmptyRow.remove();
            }
            // reset tampilan semua baris item .rowProduk
            let allRows = Array.from(dataContainer.querySelectorAll("tbody tr"));
            allRows.forEach(row => {
                row.style.display = "table-row";
            });

            const filterKeyword = document.getElementById("filterKeyword").value;

            if (filterKeyword === "") {
                return false;
            }

            let found = false;
            allRows.forEach(row => {
                if (row.querySelector('.product-name').textContent.toLowerCase().includes(filterKeyword.toLowerCase())) {
                    found = true;
                    row.style.display = "table-row";
                } else {
                    row.style.display = "none";
                }
            });
            if (!found) {
                const rowNoData = '<tr class="rowProduk-empty"><td colspan="99">Data tidak ditemukan.</td></tr>';
                dataContainer.querySelector("tbody").insertAdjacentHTML('beforeend', rowNoData);
            }
        });
    }
    // end bagian filter List Produk halaman Show Transaksi
});
