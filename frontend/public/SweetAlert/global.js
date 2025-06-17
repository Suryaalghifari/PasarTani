function showSuccessMessage(message) {
    Swal.fire({
        icon: "success",
        title: "Sukses!",
        text: message,
        timer: 2500,
        showConfirmButton: false,
    });
}

// SweetAlert Gagal
function showErrorMessage(message) {
    Swal.fire({
        icon: "error",
        title: "Gagal!",
        text: message,
        timer: 2500,
        showConfirmButton: false,
    });
}

// SweetAlert Peringatan
function showWarningMessage(message) {
    Swal.fire({
        icon: "warning",
        title: "Peringatan!",
        text: message,
        timer: 2500,
        showConfirmButton: false,
    });
}

// SweetAlert Informasi
function showInfoMessage(message) {
    Swal.fire({
        icon: "info",
        title: "Informasi",
        text: message,
        timer: 2500,
        showConfirmButton: false,
    });
}

// SweetAlert Upload Berhasil
function showFileUploadSuccess() {
    Swal.fire({
        icon: "success",
        title: "Berhasil",
        text: "File berhasil diunggah!",
        timer: 2000,
        showConfirmButton: false,
    });
}

// SweetAlert Konfirmasi
function showConfirmDialog(message, confirmCallback, cancelCallback = null) {
    Swal.fire({
        title: "Konfirmasi",
        text: message,
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed && typeof confirmCallback === "function") {
            confirmCallback();
        } else if (cancelCallback && typeof cancelCallback === "function") {
            cancelCallback();
        }
    });
}

// SweetAlert Logout
function confirmLogout() {
    Swal.fire({
        title: "Yakin ingin logout?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Logout",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = base_url + "/auth/logout";
        }
    });
}

window.addEventListener("load", function () {
    const loader = document.getElementById("globalLoader");
    if (loader) {
        loader.style.opacity = 0;
        setTimeout(() => {
            loader.style.display = "none";
        }, 500);
    }
});
