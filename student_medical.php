<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
      integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <script src="
    https://cdn.jsdelivr.net/npm/sweetalert2@11.22.4/dist/sweetalert2.all.min.js
    "></script>
    <link href="
    https://cdn.jsdelivr.net/npm/sweetalert2@11.22.4/dist/sweetalert2.min.css
    " rel="stylesheet">
    <link href="styles.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="img/logo.ico" />
    <style>
        /* *{
            border-radius: 5px;
            border: 1px solid black;
        } */
            /* Striped rows */
        .striped-row:nth-child(even) {
            background-color: #f8f9fa; /* light gray */
        }
        .striped-row:nth-child(odd) {
            background-color: #ffffff; /* white */
        }

        /* Section Headers */
        .section-header {
            background-color: #cce5ff;
            padding: 8px 12px;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        /* Sub-Headers */
        .sub-header {
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 5px;
            color: #0056b3;
        }

        /* Instructions */
        .instructions {
            font-size: 0.9rem;
            margin-bottom: 15px;
            color: #555;
        }
    </style>
  </head>
  <body>


    <header class="header">
      <div class="container">
        <div
          class="d-flex flex-column align-items-center justify-content-center text-center"
        >
          <div>
            <img
              src="img/ispsc.png"
              alt="ISPSC Logo"
              width="100"
              height="100"
              class="me-3"
            />
            <img
              class="bagong-pilipinas"
              src="img/bagong-pilipinas.png"
              alt="ISPSC Logo"
              width="120"
              height="120"
              class="me-3"
            />
          </div>
          <div>
            <h1 class="ispsc-logo mb-0">REPUBLIC OF THE PHILIPPINES</h1>
            <hr class="my-2 border-white" />
            <h1 class="ispsc-logo mb-0">
              ILOCOS SUR POLYTECHNIC STATE COLLEGE
            </h1>
            <h2 class="ispsc-logo mb-0">ILOCOS SUR, PHILIPPINES</h2>
          </div>
        </div>
      </div>
    </header>
    
    <nav class="navbar navbar-expand-lg sticky-top">
      <div class="container">
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <i class="navbar-toggler-icon" id="menu"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <!-- Left side -->
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" style="color: yellow" aria-current="page" href="student_medical.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link"  href="student_edit.php">Edit Health Info</a>
            </li>
            <li class="nav-item">
              <a class="nav-link"  href="student_info.php">Edit Personal Info</a>
            </li>
          </ul>

          <!-- Right side (Logout) -->
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="index.php">
                <i class="fa-solid fa-power-off"></i> Logout
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>


  <div class="container my-5">
    
    <h2 class="mb-4 text-center fw-bold">Student Health Information Form</h2>

        <div class="mb-3 p-3 rounded" style="background-color: #d1ecf1;">
            <p class="small mt-2 mb-0">
                Instructions: For items that are not Applicable, LEAVE IT BLANK. 
                Mark with (√) if YES, and Leave it Blank for NO
            </p>
        </div>

        <div class="container my-4">
           <form id="medicalForm" method="POST">

                <!-- Basic Info -->
                <div class="section-header">HEALTH INFORMATION</div>
                <div class="row mb-3 p-3 rounded striped-row">
                    <div class="col-md-4">
                        <label class="form-label">Blood Type</label>
                        <input type="text" class="form-control" name="blood_type">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Allergy / Alert</label>
                        <input type="text" class="form-control" name="allergy_alert">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Disability (if any)</label>
                        <input type="text" class="form-control" name="disability">
                    </div>
                </div>

                <!-- Past Medical History -->
                <div class="section-header">Past Medical History</div>

                <!-- Row 1 -->
                <div class="row mb-2 striped-row">
                    <div class="col-md-4 form-check">
                        <input type="checkbox" class="form-check-input" name="chicken_pox">
                        <label class="form-check-label">Chicken Pox</label>
                    </div>
                    <div class="col-md-4 form-check">
                        <input type="checkbox" class="form-check-input" name="hypertension">
                        <label class="form-check-label">Hypertension</label>
                    </div>
                    <div class="col-md-4 form-check">
                        <input type="checkbox" class="form-check-input" name="thyroid_disease">
                        <label class="form-check-label">Thyroid Disease</label>
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="row mb-2 striped-row">
                    <div class="col-md-4 form-check">
                        <input type="checkbox" class="form-check-input" name="mumps">
                        <label class="form-check-label">Mumps</label>
                    </div>
                    <div class="col-md-4 form-check">
                        <input type="checkbox" class="form-check-input" name="diabetes">
                        <label class="form-check-label">Diabetes</label>
                    </div>
                    <div class="col-md-4 form-check">
                        <input type="checkbox" class="form-check-input" name="heart_disease">
                        <label class="form-check-label">Heart Disease</label>
                    </div>
                </div>

                <!-- Row 3 -->
                <div class="row mb-2 striped-row">
                    <div class="col-md-4 form-check">
                        <input type="checkbox" class="form-check-input" name="measles">
                        <label class="form-check-label">Measles</label>
                    </div>
                    <div class="col-md-4 form-check">
                        <input type="checkbox" class="form-check-input" name="asthma">
                        <label class="form-check-label">Bronchial Asthma</label>
                    </div>
                    <div class="col-md-4 form-check">
                        <input type="checkbox" class="form-check-input" name="blood_transfusion">
                        <label class="form-check-label">Previous Blood Transfusion</label>
                    </div>
                </div>

                <!-- Row 4 -->
                <div class="row mb-2 striped-row">
                    <div class="col-md-4 form-check">
                        <input type="checkbox" class="form-check-input" name="tuberculosis">
                        <label class="form-check-label">Tuberculosis</label>
                    </div>
                    <div class="col-md-4 form-check">
                        <input type="checkbox" class="form-check-input" name="peptic_ulcer">
                        <label class="form-check-label">Peptic Ulcer Disease</label>
                    </div>
                    <div class="col-md-4 form-check">
                        <input type="checkbox" class="form-check-input" name="cancer">
                        <label class="form-check-label">Cancer</label>
                        <input type="text" class="form-control mt-1" placeholder="Specify Type" name="cancer_type">
                    </div>
                </div>

                <!-- Row 5 -->
                <div class="row mb-2 striped-row">
                    <div class="col-md-4 form-check">
                        <input type="checkbox" class="form-check-input" name="epilepsy">
                        <label class="form-check-label">Epilepsy</label>
                    </div>
                    <div class="col-md-4 form-check">
                        <input type="checkbox" class="form-check-input" name="hepatitis">
                        <label class="form-check-label">Hepatitis</label>
                        <input type="text" class="form-control mt-1" placeholder="Specify Type" name="hepatitis_type">
                    </div>
                    <div class="col-md-4 form-check">
                        <input type="checkbox" class="form-check-input" name="anti_coagulants">
                        <label class="form-check-label">Use of Anti-coagulants</label>
                    </div>
                </div>

                <!-- Row 6 -->
                <div class="row mb-2 striped-row">
                    <div class="col-md-4 form-check">
                        <input type="checkbox" class="form-check-input" name="bone_fracture">
                        <label class="form-check-label">Bone Fracture</label>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Hospitalizations</label>
                        <div class="row mb-1">
                            <div class="col-md-4"><input type="date" class="form-control" name="hospitalization_date"></div>
                            <div class="col-md-4"><input type="text" class="form-control" name="hospitalization_diagnosis" placeholder="Diagnosis"></div>
                            <div class="col-md-4"><input type="text" class="form-control" name="hospitalization_hospital" placeholder="Hospital"></div>
                        </div>
                    </div>
                </div>

                <!-- Row 7 -->
                <div class="row mb-2 striped-row">
                    <div class="col-md-6">
                        <label class="form-label">Surgery (if any)</label>
                        <input type="text" class="form-control" name="surgery">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Accident/s</label>
                        <input type="text" class="form-control" name="accidents">
                    </div>
                </div>


                <!-- Family Medical History -->
                <div class="section-header">Family Medical History</div>

                <!-- Row 1 -->
                <div class="row mb-2 striped-row">
                    <div class="col-md-3 form-check">
                        <input type="checkbox" class="form-check-input" name="fam_hypertension">
                        <label class="form-check-label">Hypertension</label>
                    </div>
                    <div class="col-md-3 form-check">
                        <input type="checkbox" class="form-check-input" name="fam_thyroid">
                        <label class="form-check-label">Thyroid Disease</label>
                    </div>
                    <div class="col-md-3 form-check">
                        <input type="checkbox" class="form-check-input" name="fam_autoimmune">
                        <label class="form-check-label">Autoimmune Disease</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" placeholder="Others" name="fam_others">
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="row mb-2 striped-row">
                    <div class="col-md-3 form-check">
                        <input type="checkbox" class="form-check-input" name="fam_diabetes">
                        <label class="form-check-label">Diabetes</label>
                    </div>
                    <div class="col-md-3 form-check">
                        <input type="checkbox" class="form-check-input" name="fam_cancer">
                        <label class="form-check-label">Cancer</label>
                        <input type="text" class="form-control mt-1" placeholder="Specify Form" name="fam_cancer_form">
                    </div>
                    <div class="col-md-3 form-check">
                        <input type="checkbox" class="form-check-input" name="fam_asthma">
                        <label class="form-check-label">Bronchial Asthma</label>
                        <input type="text" class="form-control mt-1" placeholder="Specify Form" name="fam_asthma_form">
                    </div>
                    <div class="col-md-3 form-check">
                        <input type="checkbox" class="form-check-input" name="fam_heart">
                        <label class="form-check-label">Heart Disease</label>
                    </div>
                </div>

                <!-- Immunization History -->
                <div class="section-header">Immunization History</div>

                <!-- Row 1 -->
                <div class="row mb-2 striped-row">
                    <div class="col-md-4">
                        <label>MMR - Date Completed</label>
                        <input type="date" class="form-control" name="mmr_date">
                    </div>
                    <div class="col-md-4">
                        <label>Hepatitis Vaccine - Date Completed</label>
                        <input type="date" class="form-control" name="hepatitis_vaccine_date">
                    </div>
                    <div class="col-md-4">
                        <label>FLU Vaccine - Date Completed</label>
                        <input type="date" class="form-control" name="flu_vaccine_date">
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="row mb-2 striped-row">
                    <div class="col-md-4">
                        <label>Anti-Rabies - Date Completed</label>
                        <input type="date" class="form-control" name="anti_rabies_date">
                    </div>
                    <div class="col-md-4">
                        <label>Anti-Tetanus - Date Completed</label>
                        <input type="date" class="form-control" name="anti_tetanus_date">
                    </div>
                    <div class="col-md-4">
                        <label>PPV23 (Pneumothorax)</label>
                        <input type="date" class="form-control" name="ppv23_date">
                    </div>
                </div>

                <!-- Row 3: COVID19 Vaccine -->
                <div class="row mb-2 striped-row">
                    <div class="col-md-12"><label>Anti-COVID19 Vaccine</label></div>
                    <div class="col-md-3"><input type="text" class="form-control" placeholder="1st Dose" name="covid_1st_dose"></div>
                    <div class="col-md-3"><input type="date" class="form-control" name="covid_1st_date"></div>
                    <div class="col-md-3"><input type="text" class="form-control" placeholder="2nd Dose" name="covid_2nd_dose"></div>
                    <div class="col-md-3"><input type="date" class="form-control" name="covid_2nd_date"></div>
                </div>

                <!-- Row 4: Boosters -->
                <div class="row mb-2 striped-row">
                    <div class="col-md-3"><input type="text" class="form-control" placeholder="1st Booster" name="covid_1st_booster"></div>
                    <div class="col-md-3"><input type="date" class="form-control" name="covid_1st_booster_date"></div>
                    <div class="col-md-3"><input type="text" class="form-control" placeholder="2nd Booster" name="covid_2nd_booster"></div>
                    <div class="col-md-3"><input type="date" class="form-control" name="covid_2nd_booster_date"></div>
                </div>


                <!-- Personal/Social History -->
                <div class="section-header">Personal/Social History</div>

                <!-- Row 1: Checkboxes -->
                <div class="row mb-2 striped-row">
                    <div class="col-md-4 form-check">
                        <input type="checkbox" class="form-check-input" name="smoker">
                        <label class="form-check-label">Smoker</label>
                    </div>
                    <div class="col-md-4 form-check">
                        <input type="checkbox" class="form-check-input" name="alcohol">
                        <label class="form-check-label">Alcohol Drinker</label>
                    </div>
                    <div class="col-md-4 form-check">
                        <input type="checkbox" class="form-check-input" name="illicit_drugs">
                        <label class="form-check-label">Illicit Drug User</label>
                    </div>
                </div>

                <!-- Row 2: Smoking Details -->
                <div class="row mb-2 striped-row">
                    <div class="col-md-4">
                        <label>No. of Sticks/Day</label>
                        <input type="text" class="form-control" name="sticks_per_day">
                    </div>
                   <div class="col-md-4">
                        <label>Type of Alcohol</label>
                        <input type="text" class="form-control" name="alcohol_type">
                    </div>
                     <div class="col-md-4">
                        <label>Type of Illicit Drug</label>
                        <input type="text" class="form-control" name="drug_type">
                    </div>
                </div>

                <!-- Row 3: Alcohol Details -->
                <div class="row mb-2 striped-row">
                    <div class="col-md-4">
                        <label>No. of Years</label>
                        <input type="text" class="form-control" name="years_smoking">
                    </div>
                    
                    <div class="col-md-4">
                        <label>No. of Bottles/mL per Bottle</label>
                        <input type="text" class="form-control" name="bottles_per_day">
                    </div>
                      <div class="col-md-4">
                        <label>No. of Bottles/mL per Day (if applicable)</label>
                        <input type="text" class="form-control" name="drug_quantity">
                    </div>
                   
                </div>

                <!-- Row 4: Illicit Drug Details -->
                <div class="row mb-2 striped-row">
                    <div class="col-md-4">
                        <label>Pack Years</label>
                        <input type="text" class="form-control" name="pack_years">
                    </div>
                   
                  
                    <div class="col-md-4">
                        <label>Frequency</label>
                        <input type="text" class="form-control" name="drug_frequency">
                    </div> <div class="col-md-4">
                        <label>Frequency</label>
                        <input type="text" class="form-control" name="alcohol_frequency">
                    </div>
                </div>


            <!-- Maternal/Menstrual History -->
                <div class="section-header">Maternal and Menstrual History (For Female/s Only)</div>

                <div class="row mb-2 striped-row">
                    <div class="col-md-3">
                        <label>No. of Pregnancies</label>
                        <input type="text" class="form-control" name="no_pregnancy">
                    </div>
                    <div class="col-md-3">
                        <label>No. Alive</label>
                        <input type="text" class="form-control" name="no_alive">
                    </div>
                    <div class="col-md-3">
                        <label>No. of Stillbirth/Abortion</label>
                        <input type="text" class="form-control" name="no_stillbirth_abortion">
                    </div>
                    <div class="col-md-3">
                        <label>LMP</label>
                        <input type="date" class="form-control" name="lmp">
                    </div>
                </div>

                <div class="row mb-2 striped-row">
                    <div class="col-md-3">
                        <label>Menarche</label>
                        <input type="date" class="form-control" name="menarche">
                    </div>
                    <div class="col-md-3">
                        <label>Duration</label>
                        <input type="text" class="form-control" name="duration">
                    </div>
                    <div class="col-md-3">
                        <label>Amount</label>
                        <input type="text" class="form-control" name="amount">
                    </div>
                    <div class="col-md-3">
                        <label>Interval</label>
                        <input type="text" class="form-control" name="menstrual_interval">
                    </div>
                </div>

                <div class="row mb-2 striped-row">
                    <div class="col-md-6">
                        <label>Symptom/s</label>
                        <input type="text" class="form-control" name="symptoms">
                    </div>
                    <div class="col-md-6">
                        <label>Gyne Pathology</label>
                        <input type="text" class="form-control" name="gyne_pathology">
                    </div>
                </div>


                <!-- Dental History -->
                <div class="section-header">Dental History</div>
                <div class="row mb-2 striped-row">
                    <div class="col-md-6"><label>Last Dental Visit</label><input type="date" class="form-control" name="last_dental_visit"></div>
                    <div class="col-md-6"><label>Procedure Done</label><input type="text" class="form-control" name="dental_procedure"></div>
                </div>

                <!-- Button to Open Modal -->
                <div class="form-check mt-4">
                    <input type="checkbox" class="form-check-input" id="consentCheckbox">
                    <label class="form-check-label" for="consentCheckbox">
                        I have read and agree to the Declaration and Data Privacy Consent
                    </label>
                </div>

                <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#declarationModal" id="openDeclarationModal">
                    View Declaration & Consent
                </button>

                <!-- Modal -->
                <div class="modal fade" id="declarationModal" tabindex="-1" aria-labelledby="declarationModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="declarationModalLabel">Declaration and Data Privacy Consent</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>
                            I hereby declare that the information above is accurate and complete. I understand that withholding any relevant medical information,
                        any misrepresentation of facts or misleading information given by me may be used as a ground for the filing of cases against me in 
                        accordance with the law. I voluntarily and freely consent to undergo physical assessment and the collection and processing of the information
                        above to enable the Ilocos Sur Polytechnic State College – Health Services Unit to render necessary health services to all its clients.
                        I also declare that I was excellently informed on the process of data collection, purpose of this medical information, 
                        and the Provisions of Republic Act 10173, Data Privacy Act of 2012.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
                </div>


                <button type="submit" class="btn btn-success w-100 mt-4">Submit Health Information</button>
            </form>

        </div>


    </div>




    <?php include "footer.php"?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    const consentCheckbox = document.getElementById('consentCheckbox');
    const submitButton = document.querySelector('form button[type="submit"]');

    consentCheckbox.addEventListener('change', () => {
        submitButton.disabled = !consentCheckbox.checked;
    });

    // Initialize submit button as disabled
    submitButton.disabled = true;
    </script>
    <script src="assets/js/student_medical.js"></script>
    <script>
        // Example: get student_id from sessionStorage
        let student_id = sessionStorage.getItem("student_id");
        console.log("Student ID from sessionStorage:", student_id);

        
    </script>
   

  </body>
</html>
