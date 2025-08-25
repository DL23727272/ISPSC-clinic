<?php
include "db_connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Helper functions
    function checkbox_value($name) {
        return isset($_POST[$name]) ? 1 : 0;
    }

    // Get student_id from POST (from sessionStorage)
    $student_number = $_POST['student_id'] ?? '';
    if (!$student_number) {
        echo json_encode(["status" => "error", "message" => "Student ID is missing."]);
        exit;
    }

    // Verify student exists
    $stmt = $conn->prepare("SELECT student_id FROM students WHERE student_id = ?");
    $stmt->bind_param("s", $student_number);
    $stmt->execute();
    $stmt->bind_result($valid_student_id);
    $stmt->fetch();
    $stmt->close();

    if (!$valid_student_id) {
        echo json_encode(["status" => "error", "message" => "Student not found."]);
        exit;
    }

    // Collect POST data
    $blood_type = htmlspecialchars(trim($_POST['blood_type'] ?? ''));
    $allergy_alert = htmlspecialchars(trim($_POST['allergy_alert'] ?? ''));
    $disability = htmlspecialchars(trim($_POST['disability'] ?? ''));
    $chicken_pox = checkbox_value('chicken_pox');
    $hypertension = checkbox_value('hypertension');
    $thyroid_disease = checkbox_value('thyroid_disease');
    $mumps = checkbox_value('mumps');
    $diabetes = checkbox_value('diabetes');
    $heart_disease = checkbox_value('heart_disease');
    $measles = checkbox_value('measles');
    $asthma = checkbox_value('asthma');
    $blood_transfusion = checkbox_value('blood_transfusion');
    $tuberculosis = checkbox_value('tuberculosis');
    $peptic_ulcer = checkbox_value('peptic_ulcer');
    $cancer = checkbox_value('cancer');
    $cancer_type = htmlspecialchars(trim($_POST['cancer_type'] ?? ''));
    $epilepsy = checkbox_value('epilepsy');
    $hepatitis = checkbox_value('hepatitis');
    $hepatitis_type = htmlspecialchars(trim($_POST['hepatitis_type'] ?? ''));
    $anti_coagulants = checkbox_value('anti_coagulants');
    $bone_fracture = checkbox_value('bone_fracture');
    $hospitalization_date = htmlspecialchars(trim($_POST['hospitalization_date'] ?? null));
    $hospitalization_diagnosis = htmlspecialchars(trim($_POST['hospitalization_diagnosis'] ?? ''));
    $hospitalization_hospital = htmlspecialchars(trim($_POST['hospitalization_hospital'] ?? ''));
    $surgery = htmlspecialchars(trim($_POST['surgery'] ?? ''));
    $accidents = htmlspecialchars(trim($_POST['accidents'] ?? ''));

    $fam_hypertension = checkbox_value('fam_hypertension');
    $fam_thyroid = checkbox_value('fam_thyroid');
    $fam_autoimmune = checkbox_value('fam_autoimmune');
    $fam_others = htmlspecialchars(trim($_POST['fam_others'] ?? ''));
    $fam_diabetes = checkbox_value('fam_diabetes');
    $fam_cancer = checkbox_value('fam_cancer');
    $fam_cancer_form = htmlspecialchars(trim($_POST['fam_cancer_form'] ?? ''));
    $fam_asthma = checkbox_value('fam_asthma');
    $fam_asthma_form = htmlspecialchars(trim($_POST['fam_asthma_form'] ?? ''));
    $fam_heart = checkbox_value('fam_heart');

    $mmr_date = htmlspecialchars(trim($_POST['mmr_date'] ?? null));
    $hepatitis_vaccine_date = htmlspecialchars(trim($_POST['hepatitis_vaccine_date'] ?? null));
    $flu_vaccine_date = htmlspecialchars(trim($_POST['flu_vaccine_date'] ?? null));
    $anti_rabies_date = htmlspecialchars(trim($_POST['anti_rabies_date'] ?? null));
    $anti_tetanus_date = htmlspecialchars(trim($_POST['anti_tetanus_date'] ?? null));
    $ppv23_date = htmlspecialchars(trim($_POST['ppv23_date'] ?? null));

    $covid_1st_dose = htmlspecialchars(trim($_POST['covid_1st_dose'] ?? ''));
    $covid_1st_date = htmlspecialchars(trim($_POST['covid_1st_date'] ?? null));
    $covid_2nd_dose = htmlspecialchars(trim($_POST['covid_2nd_dose'] ?? ''));
    $covid_2nd_date = htmlspecialchars(trim($_POST['covid_2nd_date'] ?? null));
    $covid_1st_booster = htmlspecialchars(trim($_POST['covid_1st_booster'] ?? ''));
    $covid_1st_booster_date = htmlspecialchars(trim($_POST['covid_1st_booster_date'] ?? null));
    $covid_2nd_booster = htmlspecialchars(trim($_POST['covid_2nd_booster'] ?? ''));
    $covid_2nd_booster_date = htmlspecialchars(trim($_POST['covid_2nd_booster_date'] ?? null));

    $smoker = checkbox_value('smoker');
    $sticks_per_day = htmlspecialchars(trim($_POST['sticks_per_day'] ?? ''));
    $years_smoking = htmlspecialchars(trim($_POST['years_smoking'] ?? ''));
    $pack_years = htmlspecialchars(trim($_POST['pack_years'] ?? ''));
    $alcohol = checkbox_value('alcohol');
    $alcohol_type = htmlspecialchars(trim($_POST['alcohol_type'] ?? ''));
    $bottles_per_day = htmlspecialchars(trim($_POST['bottles_per_day'] ?? ''));
    $alcohol_frequency = htmlspecialchars(trim($_POST['alcohol_frequency'] ?? ''));
    $illicit_drugs = checkbox_value('illicit_drugs');
    $drug_type = htmlspecialchars(trim($_POST['drug_type'] ?? ''));
    $drug_quantity = htmlspecialchars(trim($_POST['drug_quantity'] ?? ''));
    $drug_frequency = htmlspecialchars(trim($_POST['drug_frequency'] ?? ''));

    $no_pregnancy = htmlspecialchars(trim($_POST['no_pregnancy'] ?? ''));
    $no_alive = htmlspecialchars(trim($_POST['no_alive'] ?? ''));
    $no_stillbirth_abortion = htmlspecialchars(trim($_POST['no_stillbirth_abortion'] ?? ''));
    $lmp = htmlspecialchars(trim($_POST['lmp'] ?? null));
    $menarche = htmlspecialchars(trim($_POST['menarche'] ?? null));
    $duration = htmlspecialchars(trim($_POST['duration'] ?? ''));
    $amount = htmlspecialchars(trim($_POST['amount'] ?? ''));
    $menstrual_interval = htmlspecialchars(trim($_POST['menstrual_interval'] ?? ''));
    $symptoms = htmlspecialchars(trim($_POST['symptoms'] ?? ''));
    $gyne_pathology = htmlspecialchars(trim($_POST['gyne_pathology'] ?? ''));
    $last_dental_visit = htmlspecialchars(trim($_POST['last_dental_visit'] ?? null));
    $dental_procedure = htmlspecialchars(trim($_POST['dental_procedure'] ?? ''));

    // Build fields array, student_id as string
    $fields = [
        $valid_student_id, // <-- store the string ID "A21-00001"
        $blood_type, $allergy_alert, $disability, $chicken_pox, $hypertension, $thyroid_disease,
        $mumps, $diabetes, $heart_disease, $measles, $asthma, $blood_transfusion, $tuberculosis, $peptic_ulcer,
        $cancer, $cancer_type, $epilepsy, $hepatitis, $hepatitis_type, $anti_coagulants, $bone_fracture,
        $hospitalization_date, $hospitalization_diagnosis, $hospitalization_hospital, $surgery, $accidents,
        $fam_hypertension, $fam_thyroid, $fam_autoimmune, $fam_others, $fam_diabetes, $fam_cancer, $fam_cancer_form,
        $fam_asthma, $fam_asthma_form, $fam_heart, $mmr_date, $hepatitis_vaccine_date, $flu_vaccine_date,
        $anti_rabies_date, $anti_tetanus_date, $ppv23_date, $covid_1st_dose, $covid_1st_date, $covid_2nd_dose,
        $covid_2nd_date, $covid_1st_booster, $covid_1st_booster_date, $covid_2nd_booster, $covid_2nd_booster_date,
        $smoker, $sticks_per_day, $years_smoking, $pack_years, $alcohol, $alcohol_type, $bottles_per_day, $alcohol_frequency,
        $illicit_drugs, $drug_type, $drug_quantity, $drug_frequency, $no_pregnancy, $no_alive, $no_stillbirth_abortion,
        $lmp, $menarche, $duration, $amount, $menstrual_interval, $symptoms, $gyne_pathology, $last_dental_visit, $dental_procedure
    ];

    $placeholders = rtrim(str_repeat("?,", count($fields)), ",");

    $sql = "INSERT INTO student_health_info (
        student_id, blood_type, allergy_alert, disability, chicken_pox, hypertension, thyroid_disease,
        mumps, diabetes, heart_disease, measles, asthma, blood_transfusion, tuberculosis, peptic_ulcer,
        cancer, cancer_type, epilepsy, hepatitis, hepatitis_type, anti_coagulants, bone_fracture,
        hospitalization_date, hospitalization_diagnosis, hospitalization_hospital, surgery, accidents,
        fam_hypertension, fam_thyroid, fam_autoimmune, fam_others, fam_diabetes, fam_cancer, fam_cancer_form,
        fam_asthma, fam_asthma_form, fam_heart, mmr_date, hepatitis_vaccine_date, flu_vaccine_date,
        anti_rabies_date, anti_tetanus_date, ppv23_date, covid_1st_dose, covid_1st_date, covid_2nd_dose,
        covid_2nd_date, covid_1st_booster, covid_1st_booster_date, covid_2nd_booster, covid_2nd_booster_date,
        smoker, sticks_per_day, years_smoking, pack_years, alcohol, alcohol_type, bottles_per_day, alcohol_frequency,
        illicit_drugs, drug_type, drug_quantity, drug_frequency, no_pregnancy, no_alive, no_stillbirth_abortion,
        lmp, menarche, duration, amount, menstrual_interval, symptoms, gyne_pathology, last_dental_visit, dental_procedure
    ) VALUES ($placeholders)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // All string types
    $types = str_repeat("s", count($fields));
    $stmt->bind_param($types, ...$fields);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Health information submitted successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
