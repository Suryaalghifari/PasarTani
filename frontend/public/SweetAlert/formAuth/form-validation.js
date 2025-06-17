document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("registerForm");
    const password = document.getElementById("password");

    form.addEventListener("submit", (e) => {
        // Cek semua field required (manual)
        const requiredFields = form.querySelectorAll("input, select, textarea");
        for (let field of requiredFields) {
            // Hanya cek field yang memang wajib (bisa cek atribut data-required atau cek manual field tertentu)
            if (
                (field.id === "email" ||
                    field.id === "nama" ||
                    field.id === "peran" ||
                    field.id === "password") &&
                !field.value.trim()
            ) {
                e.preventDefault();
                if (typeof showWarningMessage === "function") {
                    showWarningMessage("Isi semua form terlebih dahulu");
                } else {
                    alert("Isi semua form terlebih dahulu");
                }
                return;
            }
        }

        // Validasi password minimal 6 karakter
        if (password.value.length < 6) {
            e.preventDefault();
            if (typeof showErrorMessage === "function") {
                showErrorMessage("Password minimal 6 karakter");
            } else {
                alert("Password minimal 6 karakter");
            }
            return;
        }
    });

    // Toggle password visibility
    const passwordToggle = document.getElementById("passwordToggle");
    if (passwordToggle) {
        passwordToggle.addEventListener("click", () => {
            if (password.type === "password") {
                password.type = "text";
                passwordToggle
                    .querySelector("i")
                    .classList.replace("fa-eye", "fa-eye-slash");
            } else {
                password.type = "password";
                passwordToggle
                    .querySelector("i")
                    .classList.replace("fa-eye-slash", "fa-eye");
            }
        });
    }
});

// LOGIN
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("loginForm");
    const email = document.getElementById("email");
    const password = document.getElementById("password");

    form.addEventListener("submit", (e) => {
        if (!email.value.trim() || !password.value.trim()) {
            e.preventDefault();
            if (typeof showWarningMessage === "function") {
                showWarningMessage("Isi semua form terlebih dahulu");
            } else {
                alert("Isi semua form terlebih dahulu");
            }
            return;
        }
    });

    // Toggle password visibility
    const passwordToggle = document.getElementById("passwordToggle");
    if (passwordToggle) {
        passwordToggle.addEventListener("click", () => {
            if (password.type === "password") {
                password.type = "text";
                passwordToggle
                    .querySelector("i")
                    .classList.replace("fa-eye", "fa-eye-slash");
            } else {
                password.type = "password";
                passwordToggle
                    .querySelector("i")
                    .classList.replace("fa-eye-slash", "fa-eye");
            }
        });
    }
});
