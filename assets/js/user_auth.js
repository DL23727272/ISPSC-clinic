document.addEventListener("DOMContentLoaded", function () {
    const employeeId = sessionStorage.getItem("employee_id");
    const studentId = sessionStorage.getItem("student_id");

    if (!employeeId && !studentId) {
        Swal.fire({
            title: "Unauthorized Access",
            text: "You must select an employee or student first.",
            icon: "error",
            allowOutsideClick: false,   // disable clicking outside
            allowEscapeKey: false,      // disable ESC key
            showConfirmButton: true,    // force user to click OK
            confirmButtonText: "Go Back"
        }).then(() => {
            window.location.href = "index.php"; // redirect after confirm
        });
    }
});