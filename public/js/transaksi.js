$(document).ready(function () {
    const modalSearchProduct = $("#modalSearchProduct");
    const detailsContainer = document.querySelector("#details-container");
    let listDetail = [];

    modalSearchProduct.on("click", 'button[id^="btnPilih-"]', function () {
        const btnPilih = $(this);
        const productId = btnPilih.data("id");
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
        /*
        <th class="col-min-width">Nama Barang</th>
        <th class="col-min-width">Kategori</th>
        <th class="col-min-width">Jumlah</th>
        <th class="col-min-width">Satuan</th>
        <th class="col-min-width">Harga</th>
        <th class="col-min-width">Diskon</th>
        <th class="col-min-width">Subtotal</th>
        <th class="text-center">Actions</th>
        */
        let existingRowsElem = detailsContainer.querySelectorAll("tr");
        let existingRows = existingRowsElem.length;
        const elemRow = document.createElement("tr");
        elemRow.setAttribute("data-index", existingRows + 1);

        for (let index = 0; index < 9; index++) {
            const elemCell = document.createElement("td");
            let elemHidden;

            if (index == 0) {
                // nama product
                elemCell.innerHTML = `
              <span class="display-nama">${pilih.nama}</span>
            `;
                elemHidden = setupProductHiddenElem(
                    "product_id",
                    pilih.id,
                    existingRows + 1
                );
                elemCell.appendChild(elemHidden);
            } else if (index == 1) {
                // kategori
                elemCell.textContent = pilih.nama_kategori;
            } else if (index == 2) {
                // catatan
                elemCell.innerHTML = `
              <input type="text" class="form-control" name="catatan[]" placeholder="Catatan tambahan">
            `;
            } else if (index == 3) {
                // jumlah
                elemCell.innerHTML = `
                <input type="hidden" class="value-stok" value="${pilih.stok}" />
              <button type="button" class="btn btn-sm btn-secondary ml-2 mr-2 btn-minus">
                <i class="fa-solid fa-minus"></i>
              </button>
              <span class="display-jumlah">1</span>
              <button type="button" class="btn btn-sm btn-secondary ml-2 mr-2 btn-plus">
                <i class="fa-solid fa-plus"></i>
              </button>
            `;
                elemCell.classList.add("text-center");
                (elemHidden = setupProductHiddenElem("jumlah", 0)),
                    existingRows + 1;
                elemCell.appendChild(elemHidden);
            } else if (index == 4) {
                // satuan
                elemCell.classList.add("text-center");
                elemCell.textContent = pilih.satuan;
            } else if (index == 5) {
                // harga jual
                elemCell.classList.add("text-right");
                elemHidden = setupProductHiddenElem(
                    "harga",
                    0,
                    existingRows + 1
                );
                elemCell.appendChild(elemHidden);
                elemCell.innerHTML = `
              <input type="hidden" name="harga[]" value="${pilih.harga_jual}">
              <span class="display-harga">${pilih.harga_jual.toLocaleString()}</span>
            `;
            } else if (index == 6) {
                // diskon
                elemHidden = setupProductHiddenElem(
                    "diskon",
                    0,
                    existingRows + 1
                );
                elemCell.appendChild(elemHidden);
                elemCell.innerHTML = `
              <input type="text" class="form-control" name="diskon[]" placeholder="Diskon per item">
            `;
            } else if (index == 7) {
                // subtotal
                elemCell.classList.add("text-right");
                elemHidden = setupProductHiddenElem(
                    "catatan",
                    0,
                    existingRows + 1
                );
                elemCell.appendChild(elemHidden);
                elemCell.innerHTML = `
              <span class="display-subtotal">0</span>
            `;
            } else if (index == 8) {
                // actions
                const elemBtnDeleteRow = document.createElement("button");
                elemBtnDeleteRow.classList.add(
                    "button",
                    "btn",
                    "btn-danger",
                    "btn-delete-row"
                );
                elemBtnDeleteRow.innerHTML = "HAPUS";
                elemCell.classList.add("text-center");
                elemCell.appendChild(elemBtnDeleteRow);
            }

            elemRow.appendChild(elemCell);
        }
        // end for

        let subtotal = hitungSubtotal(elemRow);
        let elemSubtotal = elemRow.querySelector(".display-subtotal");
        elemSubtotal.textContent = subtotal.toLocaleString();

        detailsContainer.appendChild(elemRow);

        // hitung grand total
        const subtotals =
            detailsContainer.querySelectorAll(".display-subtotal");
        let grandTotal = 0;
        subtotals.forEach((item) => {
            grandTotal += parseInt(item.textContent.replace(/[^0-9]/g, ""));
        });
        const displayGrandTotal = document.querySelector('[name="total"]');
        displayGrandTotal.innerHTML = grandTotal.toLocaleString();
    });
    // end click #btnPilih-*

    detailsContainer.addEventListener("click", function (event) {
        const targetButton = event.target.closest("button"); // Elemen yang sebenarnya diklik
        const dataRow = event.target.closest("tr"); // Dapatkan baris tempat tombol berada
        const displayJumlah = dataRow.querySelector(".display-jumlah");
        let angkaJumlah = displayJumlah.textContent.replace(/[^0-9]/g, "");
        angkaJumlah = parseInt(angkaJumlah); // Pastikan ini integer

        // Pastikan yang diklik adalah button
        if (targetButton && targetButton.classList.contains("btn")) {
            // --- Logika untuk Tombol Plus (+) ---
            if (targetButton.classList.contains("btn-plus")) {
                let sisaStok = dataRow.querySelector('.value-stok').value;
                sisaStok = parseInt(sisaStok.replace(/[^0-9]/g, ""));
                if (angkaJumlah < sisaStok) {
                    angkaJumlah++;
                    displayJumlah.textContent = angkaJumlah.toLocaleString();

                    let subtotal = hitungSubtotal(dataRow);
                    let elemSubtotal = dataRow.querySelector(".display-subtotal");
                    elemSubtotal.textContent = subtotal.toLocaleString();
                }
            }
            // --- Logika untuk Tombol Minus (-) ---
            else if (targetButton.classList.contains("btn-minus")) {
                if (angkaJumlah > 0) {
                    // Jangan sampai kurang dari 0
                    angkaJumlah -= 1;
                    displayJumlah.textContent = angkaJumlah;

                    let subtotal = hitungSubtotal(dataRow);
                    let elemSubtotal = dataRow.querySelector(".display-subtotal");
                    elemSubtotal.textContent = subtotal.toLocaleString();
                }
            }
            // --- Logika untuk Tombol Hapus ---
            else if (targetButton.classList.contains("btn-delete-row")) {
                if (dataRow) {
                    const barangID = dataRow.querySelector(
                        '[name^="product_id"]'
                    );
                    const namaBarang =
                        dataRow.querySelector(".display-nama").textContent;
                    console.log("debug", barangID.value, listDetail);
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

                        console.log("Baris dihapus.", listDetail);
                    }
                }
            }

            // hitung grand total
            const subtotals =
                detailsContainer.querySelectorAll(".display-subtotal");
            let grandTotal = 0;
            subtotals.forEach((item) => {
                grandTotal += parseInt(item.textContent.replace(/[^0-9]/g, ""));
            });
            const displayGrandTotal = document.querySelector('[name="total"]');
            displayGrandTotal.innerHTML = grandTotal.toLocaleString();
        }
    });

    // function initListeners(namaElem='')
    // {
    //   if (namaElem === '' || namaElem === 'btnPlus') {
    //     const btnPlus = document.querySelectorAll('.btn-plus');
    //     btnPlus.forEach(button => {
    //       const dataRow = button.closest('tr');
    //       const displayJumlah = dataRow.querySelector('.display-jumlah');
    //       let jumlah = displayJumlah.textContent.replace(/[^0-9]/g, '');
    //       jumlah = parseInt(jumlah) + 1;
    //       displayJumlah.textContent = jumlah;
    //     });
    //   }

    //   if (namaElem === '' || namaElem === 'btnMinus') {
    //     const btnMinus = document.querySelectorAll('.btn-minus');
    //     btnMinus.forEach(button => {
    //       const dataRow = button.closest('tr');
    //       const displayJumlah = dataRow.querySelector('.display-jumlah');
    //       let jumlah = displayJumlah.textContent.replace(/[^0-9]/g, '');
    //       jumlah = parseInt(jumlah) - 1;
    //       displayJumlah.textContent = jumlah;
    //     });
    //   }
    // }
    // function addDeleteRowDetailBtnListeners(btnElem) {
    //   const deleteButtons = document.querySelectorAll('.delete-detail-row');
    //   deleteButtons.foreEach(button => {
    //     button.onclick = function() {
    //       const rowElem = btnElem.closest('tr');
    //       rowElem.remove();
    //     }
    //   });
    // }

    function setupProductHiddenElem(colName, colValue, rowNum) {
        const elemHidden = document.createElement("input");
        let elemName = colName + "[]";
        elemHidden.setAttribute("type", "hidden");
        elemHidden.setAttribute("name", elemName);
        // elemHidden.setAttribute('id', colName+'-'+rowNum.toString());
        elemHidden.setAttribute("value", colValue);

        return elemHidden;
    }

    function hitungSubtotal(elemRow) {
        let jumlah = elemRow.querySelector(".display-jumlah").innerHTML || 0;
        jumlah = jumlah.replace(/[^0-9]/g, "");
        let harga = elemRow.querySelector('[name^="harga"]').value || 0;
        harga = harga.replace(/[^0-9]/g, "");
        let subtotal = jumlah * harga;

        return subtotal;
    }
    return;
    // const invoiceLabel = document.querySelector('#display-kode-invoice');
    // let counter = 1;
    // let kodeInvoice = 'INV'; // INV2025060600001

    // let detailIndex = {{ old('details') ? count(old('details')) : 1 }};

    // const detailsContainer = document.getElementById('details-container');
    // const addDetailButton = document.getElementById('add-detail');
    // const detailItemTemplate = document.getElementById('detail-item-template');

    // function calculateSubtotal(detailItem) {
    //   const inputJumlah = detailItem.find()
    // }
});
