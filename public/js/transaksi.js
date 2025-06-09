$(document).ready(function () {
    const modalSearchProduct = $("#modalSearchProduct");
    const detailsContainer = document.querySelector("#details-container");

    let listDetail = [];

    modalSearchProduct.on("click", 'button[id^="btnPilih-"]', function () {
        const btnPilih = $(this);
        let found = false;
        listDetail.forEach((detail) => {
            if (detail.id === btnPilih.data("id")) {
                found = true;
                return;
            }
        });

        if (found) {
            alert("Barang ini sudah ada di Detail Transaksi");
            return false;
        }

        const pilih = {
            // data produk yang barusan dipilih di popup modal
            id: btnPilih.data("id"),
            nama: btnPilih.data("nama"),
            id_kategori: btnPilih.data("id_kategori"),
            nama_kategori: btnPilih.data("nama_kategori"),
            id_supplier: btnPilih.data("id_supplier"),
            nama_supplier: btnPilih.data("nama_supplier"),
            harga_jual: btnPilih.data("harga_jual"),
            satuan: btnPilih.data("satuan"),
            stok: btnPilih.data("stok"),
        };
        listDetail.push(pilih);

        modalSearchProduct.modal("hide");

        let existingRowsElem = detailsContainer.querySelectorAll(".detail-row");
        let existingRows = existingRowsElem.length;
        const elemRow = document.createElement("div");
        elemRow.classList.add("detail-row", "row");
        elemRow.setAttribute("data-index", existingRows + 1);
        elemRow.innerHTML = `
            <input type="hidden" name="product_id[]" value="${pilih.id}"/>
            <input type="hidden" name="harga[]" value="${pilih.harga_jual}"/>
            <input type="hidden" name="stok[]" value="${pilih.stok}"/>
            <div class="col-sm-3">${pilih.nama}</div>
            <div class="col-sm-1 text-center">${pilih.nama_kategori}</div>
            <div class="col-sm-2 text-center">
                <input type="text" class="form-control" name="catatan[]" placeholder="Catatan tambahan">
            </div>
            <div class="col-sm-1 text-center">
                <input type="number" name="jumlah[]" class="form-control detail-input" value="1">
            </div>
            <div class="col-sm-1 text-center">
                <span>${pilih.satuan}</span>
            </div>
            <div class="col-sm-1 text-right">${pilih.harga_jual.toLocaleString()}</div>
            <div class="col-sm-2 text-right">
                Rp <span class="display-subtotal">0</span>
            </div>
            <div class="col-sm-1 text-center">
                <button class="btn btn-sm btn-outline-danger delete-detail-row"><i class="fa fa-trash-can"></i></button>
            </div>
        `;
        detailsContainer.appendChild(elemRow);

        doHitungSubtotal(elemRow);

        doHitungGrandTotal(detailsContainer);
    });
    // end click #btnPilih-*

    detailsContainer.addEventListener("input", function (event) {
        if (event.target.classList.contains('detail-input')) {
            const inputElem = event.target;
            const dataRow = event.target.closest(".detail-row"); // Dapatkan baris tempat tombol berada

            if (inputElem.name === 'jumlah[]') {
                let angka = parseInt(inputElem.value.replace(/[^0-9]/g, ""));
                let sisaStok = parseInt(dataRow.querySelector('[name="stok[]"]').value);
                if (angka < 0) {
                    inputElem.value = 0;
                } else if (angka > sisaStok) {
                    inputElem.value = sisaStok;
                }

                doHitungSubtotal(dataRow);

                doHitungGrandTotal(detailsContainer);
            }
        }
    });

    detailsContainer.addEventListener("click", function (event) {
        const targetButton = event.target.closest("button"); // Elemen yang sebenarnya diklik
        const dataRow = event.target.closest(".detail-row"); // Dapatkan baris tempat tombol berada
        const inputJumlah = dataRow.querySelector('[name^="jumlah"]');
        let angkaJumlah = displayJumlah.textContent.replace(/[^0-9]/g, "");
        angkaJumlah = parseInt(angkaJumlah); // Pastikan ini integer

        // Pastikan yang diklik adalah button
        if (targetButton && targetButton.classList.contains("btn")) {
            // --- Logika untuk Tombol Hapus ---
            if (targetButton.classList.contains("btn-delete-row")) {
                if (dataRow) {
                    const barangID = dataRow.querySelector('[name^="product_id"]');
                    const namaBarang = dataRow.querySelector(".display-nama").textContent;

                    if (
                        confirm(
                            `Data barang ${namaBarang} akan dihapus dari Detail Transaksi. Lanjutkan?`
                        )
                    ) {
                        dataRow.remove();

                        listDetail = listDetail.filter(
                            (detail) =>
                                detail.id.toString() !==
                                barangID.value.toString()
                        );

                        doHitungSubtotal(dataRow);

                        doHitungGrandTotal(detailsContainer);
                    }
                }
            }
        }
    });

    function doHitungSubtotal(elemRow) {
        let jumlahElem = elemRow.querySelector('[name="jumlah[]"]');
        let jumlah = jumlahElem.value;
        console.log('elem jumlah', jumlahElem, jumlah);
        jumlah = jumlah.replace(/[^0-9]/g, "");
        let harga = elemRow.querySelector('[name^="harga"]').value || 0;
        harga = harga.replace(/[^0-9]/g, "");
        let subtotal = jumlah * harga;

        let elemSubtotal = elemRow.querySelector(".display-subtotal");
        elemSubtotal.textContent = subtotal.toLocaleString();
    }

    function doHitungGrandTotal(detailsContainer) {
        // hitung grand total
        const subtotals = detailsContainer.querySelectorAll(".display-subtotal");
        let grandTotal = 0;
        subtotals.forEach((item) => {
            grandTotal += parseInt(item.textContent.replace(/[^0-9]/g, ""));
        });
        const displayGrandTotal = document.querySelector('[name="total"]');
        displayGrandTotal.innerHTML = grandTotal.toLocaleString();
    }
});
