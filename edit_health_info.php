<?php
session_start();
require_once 'db_connection.php';

$error = '';
$success = '';

if(!isset($_GET['id']) || empty($_GET['id'])) {
    die('No record specified.');
}

$id = intval($_GET['id']);

// Fetch record
$sql = "SELECT shi.*, s.student_id, s.first_name, s.last_name, s.campus 
        FROM student_health_info shi
        JOIN students s ON shi.student_id = s.student_id
        WHERE shi.id = $id";

$result = $conn->query($sql);
if($result->num_rows == 0) {
    die('Record not found.');
}

$record = $result->fetch_assoc();

// Prepare checkbox fields
$checkboxes = [
    'chicken_pox','hypertension','thyroid_disease','mumps','diabetes','heart_disease',
    'measles','asthma','blood_transfusion','tuberculosis','peptic_ulcer','cancer',
    'epilepsy','hepatitis','anti_coagulants','bone_fracture','fam_hypertension','fam_thyroid',
    'fam_autoimmune','fam_diabetes','fam_cancer','fam_asthma','fam_heart','smoker','alcohol','illicit_drugs'
];

$checkbox_values = [];
foreach($checkboxes as $field) {
    $checkbox_values[$field] = !empty($record[$field]) && $record[$field]==1 ? true : false;
}

// All other input fields
$fields = [
    'blood_type','allergy_alert','disability','cancer_type','hepatitis_type',
    'hospitalization_date','hospitalization_diagnosis','hospitalization_hospital',
    'surgery','accidents','mmr_date','hepatitis_vaccine_date','flu_vaccine_date',
    'anti_rabies_date','anti_tetanus_date','ppv23_date','covid_1st_dose','covid_1st_date',
    'covid_2nd_dose','covid_2nd_date','covid_1st_booster','covid_1st_booster_date',
    'covid_2nd_booster','covid_2nd_booster_date','sticks_per_day','alcohol_type',
    'drug_type','years_smoking','bottles_per_day','drug_quantity','pack_years',
    'drug_frequency','alcohol_frequency','no_pregnancy','no_alive','no_stillbirth_abortion',
    'lmp','menarche','duration','amount','menstrual_interval','symptoms','gyne_pathology',
    'last_dental_visit','dental_procedure','fam_cancer_form','fam_asthma_form','fam_others'
];


foreach($fields as $field) {
    $$field = isset($record[$field]) ? $record[$field] : '';
}

// Handle form submission
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $update_values = [];

    foreach($fields as $field) {
        $val = $conn->real_escape_string($_POST[$field] ?? '');
        $update_values[] = "$field='$val'";
    }

    foreach($checkboxes as $field) {
        $val = isset($_POST[$field]) ? 1 : 0;
        $update_values[] = "$field=$val";
    }

    $update_sql = "UPDATE student_health_info SET ".implode(',', $update_values)." WHERE id=$id";

    if ($conn->query($update_sql)) {
        $_SESSION['swal'] = [
            'icon' => 'success',
            'title' => 'Success',
            'text' => 'Record updated successfully!'
        ];
        header("Location: edit_health_info.php?id=$id");
        exit;
    } else {
        $_SESSION['swal'] = [
            'icon' => 'error',
            'title' => 'Error',
            'text' => 'Error updating record: '.$conn->error
        ];
        header("Location: edit_health_info.php?id=$id");
        exit;
    }

}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | ISPSC CLINICA</title>
        <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --employee-color: #27ae60;
            --admin-color: #e74c3c;
            --warning-color: #f39c12;
            --purple-color: #9b59b6;
            --light-gray: #ecf0f1;
            --dark-gray: #7f8c8d;
            --white: #ffffff;
            --box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            --sidebar-width: 280px;
        }
        * {margin:0;padding:0;box-sizing:border-box;}
        body {font-family:'Roboto',sans-serif;background-color:var(--light-gray);color:var(--primary-color);}
        .dashboard-container {display:flex;min-height:100vh;}
        .header {position:fixed;top:0;left:0;right:0;height:70px;background:var(--white);border-bottom:1px solid #e0e0e0;display:flex;align-items:center;justify-content:space-between;padding:0 2rem;z-index:1000;box-shadow:var(--box-shadow);}
        .header-left {display:flex;align-items:center;gap:1rem;}
        .logo {display:flex;align-items:center;gap:0.5rem;font-size:1.25rem;font-weight:700;color:var(--secondary-color);}
        .logo i {font-size:1.5rem;}
        .header-title {font-size:1.1rem;color:var(--dark-gray);font-weight:400;}
        .header-right {display:flex;align-items:center;gap:1rem;}
        .user-info {display:flex;align-items:center;gap:0.5rem;color:var(--dark-gray);font-size:0.95rem;cursor:pointer;padding:0.5rem 0.75rem;border-radius:6px;transition:all 0.3s ease;border:none;background:transparent;font-family:'Roboto',sans-serif;}
        .user-info:hover {background-color:var(--light-gray);color:var(--primary-color);}
        .logout-btn {color:var(--dark-gray);font-size:1.1rem;cursor:pointer;transition:color 0.3s ease;}
        .logout-btn:hover {color:var(--admin-color);}
        .logout-message {position:fixed;top:90px;right:2rem;background:#27ae60;color:#fff;padding:1rem 1.5rem;border-radius:8px;box-shadow:var(--box-shadow);font-size:0.95rem;font-weight:500;display:none;z-index:1001;animation:slideIn 0.3s ease;}
        .logout-message.show {display:flex;align-items:center;gap:0.5rem;}
        @keyframes slideIn {from{transform:translateX(100%);opacity:0;}to{transform:translateX(0);opacity:1;}}
        .sidebar {width:var(--sidebar-width);background:var(--white);border-right:1px solid #e0e0e0;padding-top:70px;position:fixed;height:100vh;overflow-y:auto;}
        .sidebar-menu {padding:2rem 0;}
        .menu-item {display:flex;align-items:center;gap:1rem;padding:1rem 2rem;color:var(--dark-gray);text-decoration:none;font-size:0.95rem;font-weight:500;transition:all 0.3s ease;border-left:3px solid transparent;}
        .menu-item:hover {background-color:var(--light-gray);color:var(--primary-color);}
        .menu-item.active {background-color:var(--secondary-color);color:#fff;border-left-color:var(--secondary-color);}
        .menu-item i {font-size:1.1rem;width:20px;}
        .main-content {flex:1;margin-left:var(--sidebar-width);padding-top:70px;padding:70px 2rem 2rem 2rem;}
        .info-cards {display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:1.5rem;}
        .info-card {background:#fff;border-radius:12px;padding:1.5rem;box-shadow:var(--box-shadow);display:flex;align-items:center;gap:1rem;}
        .card-icon {width:50px;height:50px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;color:#fff;}
        .card-icon.blue {background-color:var(--secondary-color);}
        .card-icon.green {background-color:var(--employee-color);}
        .card-icon.yellow {background-color:var(--warning-color);}
        .card-icon.purple {background-color:var(--purple-color);}
        .card-icon.red {background-color:var(--admin-color);}
        .card-icon.campus {background-color:#16a085;}
        .card-content h3 {font-size:0.9rem;color:var(--dark-gray);font-weight:500;margin-bottom:0.25rem;}
        .card-content .count {font-size:2rem;font-weight:700;color:var(--primary-color);}
        @media(max-width:768px){.sidebar{transform:translateX(-100%);transition:transform 0.3s ease;}.main-content{margin-left:0;padding:70px 1rem 2rem 1rem;}.header{padding:0 1rem;}.info-cards{grid-template-columns:1fr;}.user-info span{display:none;}.logout-message{right:1rem;left:1rem;}}
    </style>
</head>
<body>

<div class="dashboard-container">
    <header class="header">
        <div class="header-left">
            <div class="logo"><i class="fas fa-chart-bar"></i><span>ISPSC CLINICA</span></div>
            <span class="header-title">Admin Dashboard</span>
        </div>
        <div class="header-right">
            <button class="user-info" id="user-info-btn"><span>Admin User</span></button>
            <i class="fas fa-sign-out-alt logout-btn" id="logout-icon"></i>
        </div>
    </header>

    <div class="logout-message" id="logout-message"><i class="fas fa-check-circle"></i><span>Successfully logged out! Redirecting...</span></div>

  <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
    <nav class="sidebar">
        <div class="sidebar-menu">
            <a href="dashboard.php" class="menu-item <?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>">
                <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
            </a>
            <a href="patients.php" class="menu-item <?= ($currentPage == 'patients.php') ? 'active' : '' ?>">
                <i class="fas fa-users"></i><span>Patient Informations</span>
            </a>
            <a href="health_records.php" class="menu-item <?= ($currentPage == 'health_records.php') ? 'active' : '' ?>">
                <i class="fas fa-clipboard-list"></i><span>Health Informations</span>
            </a>
            <a href="#" class="menu-item <?= ($currentPage == 'reports.php') ? 'active' : '' ?>">
                <i class="fas fa-chart-line"></i><span>Reports & Analytics</span>
            </a>
            <a href="#" class="menu-item <?= ($currentPage == 'settings.php') ? 'active' : '' ?>">
                <i class="fas fa-cog"></i><span>Settings</span>
            </a>
        </div>
    </nav>

    <main class="main-content mt-5">
    <div class="container my-5">
    <h2 class="mb-4 text-center fw-bold">Edit Student Health Information Record</h2>

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
                            <input type="text" class="form-control" name="blood_type" value="<?= htmlspecialchars($blood_type ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Allergy / Alert</label>
                            <input type="text" class="form-control" name="allergy_alert" value="<?= htmlspecialchars($allergy_alert ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Disability (if any)</label>
                            <input type="text" class="form-control" name="disability" value="<?= htmlspecialchars($disability ?? '') ?>">
                        </div>
                    </div>

                    <!-- Past Medical History -->
                    <div class="section-header">Past Medical History</div>

                    <!-- Row 1 -->
                    <div class="row mb-2 striped-row">
                        <div class="col-md-4 form-check">
                        <input type="checkbox" class="form-check-input" name="chicken_pox" <?= ($checkbox_values['chicken_pox'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label">Chicken Pox</label>
                        </div>
                        <div class="col-md-4 form-check">
                            <input type="checkbox" class="form-check-input" name="hypertension" <?= ($checkbox_values['hypertension'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label">Hypertension</label>
                        </div>
                        <div class="col-md-4 form-check">
                            <input type="checkbox" class="form-check-input" name="thyroid_disease" <?= ($checkbox_values['thyroid_disease'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label">Thyroid Disease</label>
                        </div>
                    </div>

                    <!-- Row 2 -->
                    <div class="row mb-2 striped-row">
                        <div class="col-md-4 form-check">
                            <input type="checkbox" class="form-check-input" name="mumps" <?= ($checkbox_values['mumps'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label">Mumps</label>
                        </div>
                        <div class="col-md-4 form-check">
                            <input type="checkbox" class="form-check-input" name="diabetes" <?= ($checkbox_values['diabetes'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label">Diabetes</label>
                        </div>
                        <div class="col-md-4 form-check">
                            <input type="checkbox" class="form-check-input" name="heart_disease" <?= ($checkbox_values['heart_disease'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label">Heart Disease</label>
                        </div>
                    </div>

                    <!-- Row 3 -->
                    <div class="row mb-2 striped-row">
                        <div class="col-md-4 form-check">
                            <input type="checkbox" class="form-check-input" name="measles" <?= ($checkbox_values['measles'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label">Measles</label>
                        </div>
                        <div class="col-md-4 form-check">
                            <input type="checkbox" class="form-check-input" name="asthma" <?= ($checkbox_values['asthma'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label">Bronchial Asthma</label>
                        </div>
                        <div class="col-md-4 form-check">
                            <input type="checkbox" class="form-check-input" name="blood_transfusion" <?= ($checkbox_values['blood_transfusion'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label">Previous Blood Transfusion</label>
                        </div>
                    </div>

                    <!-- Row 4 -->
                    <div class="row mb-2 striped-row">
                        <div class="col-md-4 form-check">
                            <input type="checkbox" class="form-check-input" name="tuberculosis" <?= ($checkbox_values['tuberculosis'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label">Tuberculosis</label>
                        </div>
                        <div class="col-md-4 form-check">
                            <input type="checkbox" class="form-check-input" name="peptic_ulcer" <?= ($checkbox_values['peptic_ulcer'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label">Peptic Ulcer Disease</label>
                        </div>
                        <div class="col-md-4 form-check">
                            <input type="checkbox" class="form-check-input" name="cancer" <?= ($checkbox_values['cancer'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label">Cancer</label>
                            <input type="text" class="form-control mt-1" placeholder="Specify Type" name="cancer_type" value="<?= htmlspecialchars($cancer_type ?? '') ?>">
                        </div>
                    </div>

                    <!-- Row 5 -->
                    <div class="row mb-2 striped-row">
                        <div class="col-md-4 form-check">
                            <input type="checkbox" class="form-check-input" name="epilepsy" <?= ($checkbox_values['epilepsy'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label">Epilepsy</label>
                        </div>
                        <div class="col-md-4 form-check">
                            <input type="checkbox" class="form-check-input" name="hepatitis" <?= ($checkbox_values['hepatitis'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label">Hepatitis</label>
                            <input type="text" class="form-control mt-1" placeholder="Specify Type" name="hepatitis_type" value="<?= htmlspecialchars($hepatitis_type ?? '') ?>">
                        </div>
                        <div class="col-md-4 form-check">
                            <input type="checkbox" class="form-check-input" name="anti_coagulants" <?= ($checkbox_values['anti_coagulants'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label">Use of Anti-coagulants</label>
                        </div>
                    </div>

                    <!-- Row 6 -->
                    <div class="row mb-2 striped-row">
                        <div class="col-md-4 form-check">
                            <input type="checkbox" class="form-check-input" name="bone_fracture" <?= ($checkbox_values['bone_fracture'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label">Bone Fracture</label>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Hospitalizations</label>
                            <div class="row mb-1">
                                <div class="col-md-4"><input type="date" class="form-control" name="hospitalization_date" value="<?= htmlspecialchars($hospitalization_date ?? '') ?>"></div>
                                <div class="col-md-4"><input type="text" class="form-control" name="hospitalization_diagnosis" placeholder="Diagnosis" value="<?= htmlspecialchars($hospitalization_diagnosis ?? '') ?>"></div>
                                <div class="col-md-4"><input type="text" class="form-control" name="hospitalization_hospital" placeholder="Hospital" value="<?= htmlspecialchars($hospitalization_hospital ?? '') ?>"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Row 7 -->
                    <div class="row mb-2 striped-row">
                        <div class="col-md-6">
                            <label class="form-label">Surgery (if any)</label>
                            <input type="text" class="form-control" name="surgery" value="<?= htmlspecialchars($surgery ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Accident/s</label>
                            <input type="text" class="form-control" name="accidents" value="<?= htmlspecialchars($accidents ?? '') ?>">
                        </div>
                    </div>

                    <!-- Family Medical History -->
                    <div class="section-header">Family Medical History</div>

                    <!-- Row 1 -->
                <div class="row mb-2 striped-row">
                        <div class="col-md-3 form-check">
                            <input type="checkbox" class="form-check-input" name="fam_hypertension" <?= ($checkbox_values['fam_hypertension'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label">Hypertension</label>
                        </div>
                        <div class="col-md-3 form-check">
                            <input type="checkbox" class="form-check-input" name="fam_thyroid" <?= ($checkbox_values['fam_thyroid'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label">Thyroid Disease</label>
                        </div>
                        <div class="col-md-3 form-check">
                            <input type="checkbox" class="form-check-input" name="fam_autoimmune" <?= ($checkbox_values['fam_autoimmune'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label">Autoimmune Disease</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" placeholder="Others" name="fam_others" value="<?= htmlspecialchars($fam_others ?? '') ?>">
                        </div>
                    </div>

                    <!-- Row 2 -->
                    <div class="row mb-2 striped-row">
                        <div class="col-md-3 form-check">
                            <input type="checkbox" class="form-check-input" name="fam_diabetes" <?= ($checkbox_values['fam_diabetes'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label">Diabetes</label>
                        </div>
                        <div class="col-md-3 form-check">
                            <input type="checkbox" class="form-check-input" name="fam_cancer" <?= ($checkbox_values['fam_cancer'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label">Cancer</label>
                            <input type="text" class="form-control mt-1" placeholder="Specify Form" name="fam_cancer_form" value="<?= htmlspecialchars($fam_cancer_form ?? '') ?>">
                        </div>
                        <div class="col-md-3 form-check">
                            <input type="checkbox" class="form-check-input" name="fam_asthma" <?= ($checkbox_values['fam_asthma'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label">Bronchial Asthma</label>
                            <input type="text" class="form-control mt-1" placeholder="Specify Form" name="fam_asthma_form" value="<?= htmlspecialchars($fam_asthma_form ?? '') ?>">
                        </div>
                        <div class="col-md-3 form-check">
                            <input type="checkbox" class="form-check-input" name="fam_heart" <?= ($checkbox_values['fam_heart'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label">Heart Disease</label>
                        </div>
                    </div>

                        <!-- Immunization History -->
                    <div class="section-header">Immunization History</div>

                    <!-- Row 1 -->
                    <div class="row mb-2 striped-row">
                        <div class="col-md-4">
                            <label>MMR - Date Completed</label>
                            <input type="date" class="form-control" name="mmr_date" value="<?= htmlspecialchars($mmr_date ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label>Hepatitis Vaccine - Date Completed</label>
                            <input type="date" class="form-control" name="hepatitis_vaccine_date" value="<?= htmlspecialchars($hepatitis_vaccine_date ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label>FLU Vaccine - Date Completed</label>
                            <input type="date" class="form-control" name="flu_vaccine_date" value="<?= htmlspecialchars($flu_vaccine_date ?? '') ?>">
                        </div>
                    </div>

                    <!-- Row 2 -->
                    <div class="row mb-2 striped-row">
                        <div class="col-md-4">
                            <label>Anti-Rabies - Date Completed</label>
                            <input type="date" class="form-control" name="anti_rabies_date" value="<?= htmlspecialchars($anti_rabies_date ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label>Anti-Tetanus - Date Completed</label>
                            <input type="date" class="form-control" name="anti_tetanus_date" value="<?= htmlspecialchars($anti_tetanus_date ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label>PPV23 (Pneumothorax)</label>
                            <input type="date" class="form-control" name="ppv23_date" value="<?= htmlspecialchars($ppv23_date ?? '') ?>">
                        </div>
                    </div>

                    <!-- Row 3: COVID19 Vaccine -->
                    <div class="row mb-2 striped-row">
                        <div class="col-md-12"><label>Anti-COVID19 Vaccine</label></div>
                        <div class="col-md-3"><input type="text" class="form-control" placeholder="1st Dose" name="covid_1st_dose" value="<?= htmlspecialchars($covid_1st_dose ?? '') ?>"></div>
                        <div class="col-md-3"><input type="date" class="form-control" name="covid_1st_date" value="<?= htmlspecialchars($covid_1st_date ?? '') ?>"></div>
                        <div class="col-md-3"><input type="text" class="form-control" placeholder="2nd Dose" name="covid_2nd_dose" value="<?= htmlspecialchars($covid_2nd_dose ?? '') ?>"></div>
                        <div class="col-md-3"><input type="date" class="form-control" name="covid_2nd_date" value="<?= htmlspecialchars($covid_2nd_date ?? '') ?>"></div>
                    </div>

                    <!-- Row 4: Boosters -->
                    <div class="row mb-2 striped-row">
                        <div class="col-md-3"><input type="text" class="form-control" placeholder="1st Booster" name="covid_1st_booster" value="<?= htmlspecialchars($covid_1st_booster ?? '') ?>"></div>
                        <div class="col-md-3"><input type="date" class="form-control" name="covid_1st_booster_date" value="<?= htmlspecialchars($covid_1st_booster_date ?? '') ?>"></div>
                        <div class="col-md-3"><input type="text" class="form-control" placeholder="2nd Booster" name="covid_2nd_booster" value="<?= htmlspecialchars($covid_2nd_booster ?? '') ?>"></div>
                        <div class="col-md-3"><input type="date" class="form-control" name="covid_2nd_booster_date" value="<?= htmlspecialchars($covid_2nd_booster_date ?? '') ?>"></div>
                    </div>

                    <!-- Personal/Social History -->
                    <div class="section-header">Personal/Social History</div>

                    <!-- Row 1: Checkboxes -->
                    <div class="row mb-2 striped-row">
                        <div class="col-md-4 form-check">
                            <input type="checkbox" class="form-check-input" name="smoker" <?= ($checkbox_values['smoker'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label">Smoker</label>
                        </div>
                        <div class="col-md-4 form-check">
                            <input type="checkbox" class="form-check-input" name="alcohol" <?= ($checkbox_values['alcohol'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label">Alcohol Drinker</label>
                        </div>
                        <div class="col-md-4 form-check">
                            <input type="checkbox" class="form-check-input" name="illicit_drugs" <?= ($checkbox_values['illicit_drugs'] ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label">Illicit Drug User</label>
                        </div>
                    </div>

                    <!-- Row 2: Smoking/Alcohol/Drug Details -->
                    <div class="row mb-2 striped-row">
                        <div class="col-md-4">
                            <label>No. of Sticks/Day</label>
                            <input type="text" class="form-control" name="sticks_per_day" value="<?= htmlspecialchars($sticks_per_day ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label>Type of Alcohol</label>
                            <input type="text" class="form-control" name="alcohol_type" value="<?= htmlspecialchars($alcohol_type ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label>Type of Illicit Drug</label>
                            <input type="text" class="form-control" name="drug_type" value="<?= htmlspecialchars($drug_type ?? '') ?>">
                        </div>
                    </div>

                    <!-- Row 3 -->
                    <div class="row mb-2 striped-row">
                        <div class="col-md-4">
                            <label>No. of Years Smoking</label>
                            <input type="text" class="form-control" name="years_smoking" value="<?= htmlspecialchars($years_smoking ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label>No. of Bottles/mL per Bottle</label>
                            <input type="text" class="form-control" name="bottles_per_day" value="<?= htmlspecialchars($bottles_per_day ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label>No. of Bottles/mL per Day (if applicable)</label>
                            <input type="text" class="form-control" name="drug_quantity" value="<?= htmlspecialchars($drug_quantity ?? '') ?>">
                        </div>
                    </div>

                    <!-- Row 4 -->
                    <div class="row mb-2 striped-row">
                        <div class="col-md-4">
                            <label>Pack Years</label>
                            <input type="text" class="form-control" name="pack_years" value="<?= htmlspecialchars($pack_years ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label>Frequency (Illicit Drug)</label>
                            <input type="text" class="form-control" name="drug_frequency" value="<?= htmlspecialchars($drug_frequency ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label>Frequency (Alcohol)</label>
                            <input type="text" class="form-control" name="alcohol_frequency" value="<?= htmlspecialchars($alcohol_frequency ?? '') ?>">
                        </div>
                    </div>

                    <!-- Maternal/Menstrual History -->
                    <div class="section-header">Maternal and Menstrual History (For Female/s Only)</div>

                    <div class="row mb-2 striped-row">
                        <div class="col-md-3">
                            <label>No. of Pregnancies</label>
                            <input type="text" class="form-control" name="no_pregnancy" value="<?= htmlspecialchars($no_pregnancy ?? '') ?>">
                        </div>
                        <div class="col-md-3">
                            <label>No. Alive</label>
                            <input type="text" class="form-control" name="no_alive" value="<?= htmlspecialchars($no_alive ?? '') ?>">
                        </div>
                        <div class="col-md-3">
                            <label>No. of Stillbirth/Abortion</label>
                            <input type="text" class="form-control" name="no_stillbirth_abortion" value="<?= htmlspecialchars($no_stillbirth_abortion ?? '') ?>">
                        </div>
                        <div class="col-md-3">
                            <label>LMP</label>
                            <input type="date" class="form-control" name="lmp" value="<?= htmlspecialchars($lmp ?? '') ?>">
                        </div>
                    </div>

                    <div class="row mb-2 striped-row">
                        <div class="col-md-3">
                            <label>Menarche</label>
                            <input type="date" class="form-control" name="menarche" value="<?= htmlspecialchars($menarche ?? '') ?>">
                        </div>
                        <div class="col-md-3">
                            <label>Duration</label>
                            <input type="text" class="form-control" name="duration" value="<?= htmlspecialchars($duration ?? '') ?>">
                        </div>
                        <div class="col-md-3">
                            <label>Amount</label>
                            <input type="text" class="form-control" name="amount" value="<?= htmlspecialchars($amount ?? '') ?>">
                        </div>
                        <div class="col-md-3">
                            <label>Interval</label>
                            <input type="text" class="form-control" name="menstrual_interval" value="<?= htmlspecialchars($menstrual_interval ?? '') ?>">
                        </div>
                    </div>

                    <div class="row mb-2 striped-row">
                        <div class="col-md-6">
                            <label>Symptom/s</label>
                            <input type="text" class="form-control" name="menstrual_symptoms" value="<?= htmlspecialchars($menstrual_symptoms ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Others</label>
                            <input type="text" class="form-control" name="menstrual_others" value="<?= htmlspecialchars($menstrual_others ?? '') ?>">
                        </div>
                    </div>

                    <!-- Dental History -->
                    <div class="section-header">Dental History</div>

                    <div class="row mb-2 striped-row">
                        <div class="col-md-6">
                            <label>Oral Hygiene</label>
                            <input type="text" class="form-control" name="oral_hygiene" value="<?= htmlspecialchars($oral_hygiene ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Dental Complaints</label>
                            <input type="text" class="form-control" name="dental_complaints" value="<?= htmlspecialchars($dental_complaints ?? '') ?>">
                        </div>
                    </div>
                    

                    <!-- Consent Checkbox -->
                    <div class="row mb-3">
                        <div class="col-md-12 form-check">
                            <input type="checkbox" class="form-check-input" id="consentCheckbox" name="consent" required>
                            <label class="form-check-label" for="consentCheckbox">
                                I hereby certify that the above information is true and correct to the best of my knowledge.
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Submit Form</button>
                        </div>
                    </div>

            </form>




        </div>
    </main>
</div>

    <?php if (isset($_SESSION['swal'])): ?>
    <script>
    Swal.fire({
    icon: '<?= $_SESSION['swal']['icon'] ?>',
    title: '<?= $_SESSION['swal']['title'] ?>',
    text: '<?= $_SESSION['swal']['text'] ?>'
    }).then(()=>{
        <?php if($_SESSION['swal']['icon'] === 'success'): ?>
            window.location = 'health_records.php';
        <?php endif; ?>
    });
    </script>
    <?php unset($_SESSION['swal']); endif; ?>


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
document.querySelectorAll('.menu-item').forEach(item => {
    item.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));
        this.classList.add('active');
        window.location.href = this.href; // navigate manually
    });
});


function handleLogout() {
    const logoutMessage = document.getElementById('logout-message');
    logoutMessage.classList.add('show');
    setTimeout(() => { window.location.href = 'admin_login.php'; }, 2000);
}
document.getElementById('user-info-btn').addEventListener('click', handleLogout);
document.getElementById('logout-icon').addEventListener('click', handleLogout);
</script>
</body>
</html>
