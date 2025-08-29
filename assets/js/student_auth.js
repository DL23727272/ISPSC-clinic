// Login
document.getElementById("loginForm").addEventListener("submit", function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    formData.append("action", "login");

    fetch("student_auth.php", { method: "POST", body: formData })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            // ✅ Store student_id or gmail in sessionStorage
            sessionStorage.setItem("student_id", data.student_id); 
            sessionStorage.setItem("email", data.email);

            Swal.fire({
                icon: "success",
                title: "Success",
                text: data.message,
                timer: 1500,
                showConfirmButton: false
            }).then(() => window.location.href = "student_medical.php");
        } else {
            Swal.fire("Error", data.message, "error");
        }
    });
});

// Register
document.getElementById("registerForm").addEventListener("submit", function(e) {
    e.preventDefault();

    let form = this;
    let submitBtn = document.getElementById("registerBtn"); // use the actual ID
    let originalHTML = submitBtn.innerHTML;

    // Show spinner + disable button
    submitBtn.disabled = true;
    submitBtn.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
        Registering...
    `;

    let formData = new FormData(form);
    formData.append("action", "register");

    fetch("student_auth.php", { method: "POST", body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                Swal.fire("Registered!", data.message, "success");
                document.getElementById("login-tab").click();
                form.reset();
            } else {
                Swal.fire("Error", data.message, "error");
            }
        })
        .catch(() => {
            Swal.fire("Error", "Something went wrong!", "error");
        })
        .finally(() => {
            // Restore button state
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHTML;
        });
});
