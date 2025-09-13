function showToast(message, title = "Info", type = "info") {
  let toastEl = document.getElementById("appToast");
  let toastBody = document.getElementById("toastBody");
  let toastTitle = document.getElementById("toastTitle");

  // Update content pas mau tampil
  toastBody.innerText = message;
  toastTitle.innerText = title;

  // Reset & tambahkan warna sesuai type
  let header = toastEl.querySelector(".toast-header");
  header.className = "toast-header";
  if (type === "error") header.classList.add("bg-danger", "text-white");
  else if (type === "success") header.classList.add("bg-success", "text-white");
  else if (type === "warning") header.classList.add("bg-warning");
  else header.classList.add("bg-info", "text-white");

  // Show toast (pakai jQuery karena Bootstrap 4.6 masih butuh jQuery)
  $(toastEl).toast("show");
}
