// Login
document.getElementById("loginForm").addEventListener("submit", function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    formData.append("action", "login");

    fetch("employee_auth.php", { method: "POST", body: formData })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            // ✅ Store employee_id or gmail in sessionStorage
            sessionStorage.setItem("employee_id", data.employee_id); 
            sessionStorage.setItem("email", data.email);

            Swal.fire({
                icon: "success",
                title: "Success",
                text: data.message,
                timer: 1500,
                showConfirmButton: false
            }).then(() => window.location.href = "employee_medical.php");
        } else {
            Swal.fire("Error", data.message, "error");
        }
    });
});

// Register
document.getElementById("registerForm").addEventListener("submit", function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    formData.append("action", "register");

    fetch("employee_auth.php", { method: "POST", body: formData })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            Swal.fire("Registered!", data.message, "success");
            document.getElementById('login-tab').click();
        } else {
            Swal.fire("Error", data.message, "error");
        }
    });
});




