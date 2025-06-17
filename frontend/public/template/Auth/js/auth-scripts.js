// Global variables
let currentStep = 0;
const totalSteps = 3;
const stepLabels = ["Informasi Akun", "Data Pribadi", "Peran & Alamat"];

// Form validation functions
const validators = {
    email: (email) => {
        if (!email) return { isValid: false, message: "Email wajib diisi" };
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email))
            return { isValid: false, message: "Format email tidak valid" };
        return { isValid: true, message: "Email valid" };
    },

    password: (password) => {
        if (!password)
            return { isValid: false, message: "Password wajib diisi" };
        if (password.length < 6)
            return { isValid: false, message: "Password minimal 6 karakter" };
        return { isValid: true, message: "Password valid" };
    },

    nama: (nama) => {
        if (!nama) return { isValid: false, message: "Nama wajib diisi" };
        if (nama.length > 255)
            return { isValid: false, message: "Nama maksimal 255 karakter" };
        return { isValid: true, message: "Nama valid" };
    },

    peran: (peran) => {
        if (!peran) return { isValid: false, message: "Peran wajib dipilih" };
        const validRoles = ["admin", "petani", "konsumen", "petugas"];
        if (!validRoles.includes(peran))
            return { isValid: false, message: "Peran tidak valid" };
        return { isValid: true, message: "Peran valid" };
    },

    no_hp: (no_hp) => {
        if (!no_hp) return { isValid: true, message: "" };
        const phoneRegex = /^[0-9+\-\s()]+$/;
        if (!phoneRegex.test(no_hp))
            return { isValid: false, message: "Format nomor HP tidak valid" };
        return { isValid: true, message: "Nomor HP valid" };
    },
};

// Utility functions
function showValidationMessage(fieldId, validation, show = true) {
    const messageEl = document.getElementById(fieldId + "Validation");
    if (!messageEl || !show || !validation.message) {
        if (messageEl) {
            messageEl.classList.remove("show");
        }
        return;
    }

    messageEl.innerHTML = `
        <i class="fas ${
            validation.isValid ? "fa-check-circle" : "fa-exclamation-circle"
        }"></i>
        <span>${validation.message}</span>
    `;
    messageEl.className = `validation-message show ${
        validation.isValid ? "valid" : "invalid"
    }`;
}

function updatePasswordStrength(password) {
    const strengthEl = document.getElementById("passwordStrength");
    const strengthFill = document.getElementById("strengthFill");
    const strengthText = document.getElementById("strengthText");

    if (!password) {
        strengthEl.classList.remove("show");
        return;
    }

    strengthEl.classList.add("show");

    let score = 0;
    let label = "";
    let className = "";

    if (password.length >= 6) score += 1;
    if (password.length >= 8) score += 1;
    if (/[A-Z]/.test(password)) score += 1;
    if (/[0-9]/.test(password)) score += 1;
    if (/[^A-Za-z0-9]/.test(password)) score += 1;

    switch (score) {
        case 0:
        case 1:
            label = "Sangat Lemah";
            className = "strength-very-weak";
            break;
        case 2:
            label = "Lemah";
            className = "strength-weak";
            break;
        case 3:
            label = "Sedang";
            className = "strength-medium";
            break;
        case 4:
            label = "Kuat";
            className = "strength-strong";
            break;
        case 5:
            label = "Sangat Kuat";
            className = "strength-very-strong";
            break;
    }

    strengthText.textContent = label;
    strengthText.className = `strength-text ${className}`;
    strengthFill.className = `strength-fill ${className}`;
    strengthFill.style.width = `${(score / 5) * 100}%`;
}

function updateCharCounter(fieldId, maxLength) {
    const field = document.getElementById(fieldId);
    const counter = document.getElementById(fieldId + "Counter");

    if (field && counter) {
        counter.textContent = field.value.length;

        // Change color based on usage
        const percentage = (field.value.length / maxLength) * 100;
        if (percentage > 90) {
            counter.style.color = "#ef4444";
        } else if (percentage > 75) {
            counter.style.color = "#f97316";
        } else {
            counter.style.color = "#6b7280";
        }
    }
}

// Login page functions
function initializeLogin() {
    const form = document.getElementById("loginForm");
    const emailField = document.getElementById("email");
    const passwordField = document.getElementById("password");
    const passwordToggle = document.getElementById("passwordToggle");
    const submitBtn = document.getElementById("loginBtn");

    // Password toggle functionality
    if (passwordToggle) {
        passwordToggle.addEventListener("click", function () {
            const type =
                passwordField.type === "password" ? "text" : "password";
            passwordField.type = type;

            const icon = this.querySelector("i");
            icon.className =
                type === "password" ? "fas fa-eye" : "fas fa-eye-slash";

            // Add animation
            this.style.transform = "translateY(-50%) scale(0.9)";
            setTimeout(() => {
                this.style.transform = "translateY(-50%) scale(1)";
            }, 150);
        });
    }

    // Real-time validation
    if (emailField) {
        emailField.addEventListener("input", function () {
            const validation = validators.email(this.value);
            showValidationMessage("email", validation, this.value.length > 0);
        });
    }

    if (passwordField) {
        passwordField.addEventListener("input", function () {
            const validation = validators.password(this.value);
            showValidationMessage(
                "password",
                validation,
                this.value.length > 0
            );
        });
    }

    // Form submission
    if (form) {
        form.addEventListener("submit", (e) => {
            e.preventDefault();

            const email = emailField.value;
            const password = passwordField.value;

            // Validate
            const emailValidation = validators.email(email);
            const passwordValidation = validators.password(password);

            showValidationMessage("email", emailValidation, true);
            showValidationMessage("password", passwordValidation, true);

            if (emailValidation.isValid && passwordValidation.isValid) {
                // Show loading state
                submitBtn.classList.add("loading");

                // Simulate API call
                setTimeout(() => {
                    submitBtn.classList.remove("loading");
                    alert("Login berhasil! (Demo)");

                    // In real app, redirect to dashboard
                    console.log("Login data:", { email, password });
                }, 2000);
            }
        });
    }
}

// Register page functions
function initializeRegister() {
    const form = document.getElementById("registerForm");
    const passwordToggle = document.getElementById("passwordToggle");
    const nextBtn = document.getElementById("nextBtn");
    const prevBtn = document.getElementById("prevBtn");
    const submitBtn = document.getElementById("registerBtn");

    // Initialize form fields
    initializeFormFields();

    // Password toggle functionality
    if (passwordToggle) {
        passwordToggle.addEventListener("click", function () {
            const passwordField = document.getElementById("password");
            const type =
                passwordField.type === "password" ? "text" : "password";
            passwordField.type = type;

            const icon = this.querySelector("i");
            icon.className =
                type === "password" ? "fas fa-eye" : "fas fa-eye-slash";

            // Add animation
            this.style.transform = "translateY(-50%) scale(0.9)";
            setTimeout(() => {
                this.style.transform = "translateY(-50%) scale(1)";
            }, 150);
        });
    }

    // Navigation buttons
    if (nextBtn) {
        nextBtn.addEventListener("click", () => {
            if (canProceedToNextStep()) {
                nextStep();
            }
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener("click", () => {
            prevStep();
        });
    }

    // Form submission
    if (form) {
        form.addEventListener("submit", (e) => {
            e.preventDefault();

            if (validateAllFields()) {
                // Show loading state
                submitBtn.classList.add("loading");

                // Collect form data
                const formData = {
                    nama: document.getElementById("nama").value,
                    email: document.getElementById("email").value,
                    password: document.getElementById("password").value,
                    peran: document.getElementById("peran").value,
                    alamat: document.getElementById("alamat").value || null,
                    no_hp: document.getElementById("no_hp").value || null,
                };

                // Simulate API call
                setTimeout(() => {
                    submitBtn.classList.remove("loading");
                    alert("Registrasi berhasil! (Demo)");

                    // In real app, redirect to login or dashboard
                    console.log("Register data:", formData);
                }, 2000);
            }
        });
    }

    // Initialize step display
    updateStepDisplay();
}

function initializeFormFields() {
    // Email field
    const emailField = document.getElementById("email");
    if (emailField) {
        emailField.addEventListener("input", function () {
            const validation = validators.email(this.value);
            showValidationMessage("email", validation, this.value.length > 0);
            updateNavigationButtons();
        });
    }

    // Password field
    const passwordField = document.getElementById("password");
    if (passwordField) {
        passwordField.addEventListener("input", function () {
            const validation = validators.password(this.value);
            showValidationMessage(
                "password",
                validation,
                this.value.length > 0
            );
            updatePasswordStrength(this.value);
            updateNavigationButtons();
        });
    }

    // Nama field
    const namaField = document.getElementById("nama");
    if (namaField) {
        namaField.addEventListener("input", function () {
            const validation = validators.nama(this.value);
            showValidationMessage("nama", validation, this.value.length > 0);
            updateCharCounter("nama", 255);
            updateNavigationButtons();
        });
    }

    // No HP field
    const noHpField = document.getElementById("no_hp");
    if (noHpField) {
        noHpField.addEventListener("input", function () {
            const validation = validators.no_hp(this.value);
            showValidationMessage("no_hp", validation, this.value.length > 0);
        });
    }

    // Peran field
    const peranField = document.getElementById("peran");
    if (peranField) {
        peranField.addEventListener("change", function () {
            const validation = validators.peran(this.value);
            showValidationMessage("peran", validation, this.value.length > 0);
            updateNavigationButtons();
        });
    }
}

function canProceedToNextStep() {
    switch (currentStep) {
        case 0:
            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;
            const emailValid = validators.email(email).isValid;
            const passwordValid = validators.password(password).isValid;
            return email && password && emailValid && passwordValid;

        case 1:
            const nama = document.getElementById("nama").value;
            const namaValid = validators.nama(nama).isValid;
            return nama && namaValid;

        case 2:
            const peran = document.getElementById("peran").value;
            const peranValid = validators.peran(peran).isValid;
            return peran && peranValid;

        default:
            return false;
    }
}

function validateAllFields() {
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    const nama = document.getElementById("nama").value;
    const peran = document.getElementById("peran").value;

    const emailValidation = validators.email(email);
    const passwordValidation = validators.password(password);
    const namaValidation = validators.nama(nama);
    const peranValidation = validators.peran(peran);

    return (
        emailValidation.isValid &&
        passwordValidation.isValid &&
        namaValidation.isValid &&
        peranValidation.isValid
    );
}

function nextStep() {
    if (currentStep < totalSteps - 1) {
        // Hide current step
        const currentStepEl = document.getElementById(`step${currentStep}`);
        currentStepEl.classList.add("exit");

        setTimeout(() => {
            currentStepEl.classList.remove("active", "exit");

            // Show next step
            currentStep++;
            const nextStepEl = document.getElementById(`step${currentStep}`);
            nextStepEl.classList.add("active");

            updateStepDisplay();
        }, 300);
    }
}

function prevStep() {
    if (currentStep > 0) {
        // Hide current step
        const currentStepEl = document.getElementById(`step${currentStep}`);
        currentStepEl.classList.add("exit");

        setTimeout(() => {
            currentStepEl.classList.remove("active", "exit");

            // Show previous step
            currentStep--;
            const prevStepEl = document.getElementById(`step${currentStep}`);
            prevStepEl.classList.add("active");

            updateStepDisplay();
        }, 300);
    }
}

function updateStepDisplay() {
    // Update step label
    const stepLabel = document.getElementById("stepLabel");
    const progressLabel = document.getElementById("progressLabel");

    if (stepLabel) stepLabel.textContent = stepLabels[currentStep];
    if (progressLabel) progressLabel.textContent = stepLabels[currentStep];

    // Update progress indicator
    updateProgressIndicator();

    // Update navigation buttons
    updateNavigationButtons();
}

function updateProgressIndicator() {
    const steps = document.querySelectorAll(".progress-step");
    const lines = document.querySelectorAll(".progress-line");

    steps.forEach((step, index) => {
        if (index < currentStep) {
            step.classList.add("completed");
            step.classList.remove("active");
        } else if (index === currentStep) {
            step.classList.add("active");
            step.classList.remove("completed");
        } else {
            step.classList.remove("active", "completed");
        }
    });

    lines.forEach((line, index) => {
        if (index < currentStep) {
            line.classList.add("completed");
        } else {
            line.classList.remove("completed");
        }
    });
}

function updateNavigationButtons() {
    const nextBtn = document.getElementById("nextBtn");
    const prevBtn = document.getElementById("prevBtn");
    const submitBtn = document.getElementById("registerBtn");

    // Show/hide previous button
    if (prevBtn) {
        prevBtn.style.display = currentStep > 0 ? "block" : "none";
    }

    // Show/hide next vs submit button
    if (currentStep < totalSteps - 1) {
        if (nextBtn) {
            nextBtn.style.display = "block";
            nextBtn.disabled = !canProceedToNextStep();
        }
        if (submitBtn) {
            submitBtn.style.display = "none";
        }
    } else {
        if (nextBtn) {
            nextBtn.style.display = "none";
        }
        if (submitBtn) {
            submitBtn.style.display = "block";
            submitBtn.disabled = !canProceedToNextStep();
        }
    }
}

// Initialize based on current page
document.addEventListener("DOMContentLoaded", () => {
    // Add entrance animations
    document.body.style.opacity = "0";
    setTimeout(() => {
        document.body.style.opacity = "1";
        document.body.style.transition = "opacity 0.5s ease";
    }, 100);
});
