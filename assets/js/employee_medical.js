$(document).ready(function(){
  $("#medicalForm").on("submit", function(e){
    e.preventDefault(); // stop default form submission

    // Get employee_id from sessionStorage
    let employeeID = sessionStorage.getItem("employee_id"); // e.g. "A21-00001"

    // Serialize form and append employee_id
    let formData = $(this).serialize() + "&employee_id=" + encodeURIComponent(employeeID);

    $.ajax({
      url: "submit_employee_info.php",
      type: "POST",
      data: formData,
      dataType: "json",  // ✅ important
      success: function(response){
        if(response.status === "success"){   // ✅ check JSON
          Swal.fire({
            icon: "success",
            title: "Saved!",
            text: response.message
          }).then(() => {
            $("#medicalForm")[0].reset(); // clear form
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: response.message
          });
        }
      },
      error: function(xhr, status, error){
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Something went wrong: " + error
        });
      }
    });
  });
});
