<?php
require_once __DIR__ . '/tcpdf/tcpdf.php';
$mysqli = new mysqli("localhost", "root", "", "isps_clinica");

if ($mysqli->connect_errno) {
    die("Failed to connect: " . $mysqli->connect_error);
}

$student_id = "2025-001"; // change or loop per student
$query = $mysqli->prepare("SELECT * FROM student_health_info WHERE student_id = ?");
$query->bind_param("s", $student_id);
$query->execute();
$result = $query->get_result();
$data = $result->fetch_assoc();

$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('dejavusans','',10);


// ---------- HEADER ----------
$html = '
<div style="text-align:center;">
    <h4>Republic of the Philippines</h4>
    <h4>ILOCOS SUR POLYTECHNIC STATE COLLEGE</h4>
    <h5>Sta. Maria Campus, Sta. Maria, Ilocos Sur</h5>
    <h4>Health Services Unit</h4>
    <h3><u>STUDENT’S HEALTH RECORD FORM</u></h3>
</div>
';

// ---------- STUDENT INFO ----------
$html .= '
<table border="0" cellpadding="4">
<tr><td><b>Student ID:</b> '.$data['student_id'].'</td><td><b>Blood Type:</b> '.$data['blood_type'].'</td></tr>
<tr><td><b>Allergies:</b> '.$data['allergy_alert'].'</td><td><b>Disability:</b> '.$data['disability'].'</td></tr>
</table>
<hr>
';

// ---------- PAST MEDICAL HISTORY ----------
$html .= '<h4>Past Medical History</h4>';
$pmh = [
  "Chicken Pox"=>$data['chicken_pox'],
  "Hypertension"=>$data['hypertension'],
  "Thyroid Disease"=>$data['thyroid_disease'],
  "Mumps"=>$data['mumps'],
  "Diabetes"=>$data['diabetes'],
  "Heart Disease"=>$data['heart_disease'],
  "Measles"=>$data['measles'],
  "Bronchial Asthma"=>$data['asthma'],
  "Previous Blood Transfusion"=>$data['blood_transfusion'],
  "Tuberculosis"=>$data['tuberculosis'],
  "Peptic Ulcer Disease"=>$data['peptic_ulcer'],
  "Cancer"=>$data['cancer'],
  "Epilepsy"=>$data['epilepsy'],
  "Hepatitis"=>$data['hepatitis'],
  "Use of Anti-coagulants"=>$data['anti_coagulants'],
  "Bone Fracture"=>$data['bone_fracture']
];
$html .= '<table border="1" cellpadding="4" cellspacing="0" width="100%">';
$col = 0;
foreach ($pmh as $label=>$val) {
    if ($col == 0) $html .= '<tr>';
    $check = $val ? "&#9745;" : "&#9744;";  // works with dejavusans
    $extra = "";
    if ($label=="Cancer" && $val) $extra = " (".$data['cancer_type'].")";
    if ($label=="Hepatitis" && $val) $extra = " (".$data['hepatitis_type'].")";
    $html .= '<td width="50%">'.$check.' '.$label.$extra.'</td>';
    $col++;
    if ($col == 2) { $html .= '</tr>'; $col = 0; }
}
if ($col == 1) $html .= '<td></td></tr>';
$html .= '</table><br>';

$html .= '
<b>Hospitalization:</b> Date: '.$data['hospitalization_date'].' | Diagnosis: '.$data['hospitalization_diagnosis'].' | Hospital: '.$data['hospitalization_hospital'].'<br>
<b>Surgery:</b> '.$data['surgery'].'<br>
<b>Accidents:</b> '.$data['accidents'].'<br><br>
';

// ---------- FAMILY HISTORY ----------
$html .= '<h4>Family History</h4>';
$fh = [
  "Hypertension"=>$data['fam_hypertension'],
  "Diabetes"=>$data['fam_diabetes'],
  "Cancer"=>$data['fam_cancer'],
  "Asthma"=>$data['fam_asthma'],
  "Heart Disease"=>$data['fam_heart'],
  "Thyroid Disease"=>$data['fam_thyroid'],
  "Autoimmune Disease"=>$data['fam_autoimmune']
];
$html .= '<table border="1" cellpadding="4" cellspacing="0" width="100%">';
$col = 0;
foreach ($fh as $label=>$val) {
    if ($col == 0) $html .= '<tr>';
    $check = $val ? "☑" : "☐";
    $extra = "";
    if ($label=="Cancer" && $val) $extra = " (".$data['fam_cancer_form'].")";
    if ($label=="Asthma" && $val) $extra = " (".$data['fam_asthma_form'].")";
    $html .= '<td width="50%">'.$check.' '.$label.$extra.'</td>';
    $col++;
    if ($col == 2) { $html .= '</tr>'; $col = 0; }
}
if ($col == 1) $html .= '<td></td></tr>';
$html .= '</table><br>';

$html .= '<b>Other Family Conditions:</b> '.$data['fam_others'].'<br><br>';

// ---------- IMMUNIZATION ----------
$html .= '<h4>Immunization History</h4>
<table border="1" cellpadding="4" cellspacing="0" width="100%">
<tr><td>MMR</td><td>'.$data['mmr_date'].'</td><td>Hepatitis Vaccine</td><td>'.$data['hepatitis_vaccine_date'].'</td></tr>
<tr><td>Flu Vaccine</td><td>'.$data['flu_vaccine_date'].'</td><td>Anti-Rabies</td><td>'.$data['anti_rabies_date'].'</td></tr>
<tr><td>Anti-Tetanus</td><td>'.$data['anti_tetanus_date'].'</td><td>PPV23</td><td>'.$data['ppv23_date'].'</td></tr>
<tr><td>COVID 1st Dose</td><td>'.$data['covid_1st_dose'].' ('.$data['covid_1st_date'].')</td><td>COVID 2nd Dose</td><td>'.$data['covid_2nd_dose'].' ('.$data['covid_2nd_date'].')</td></tr>
<tr><td>1st Booster</td><td>'.$data['covid_1st_booster'].' ('.$data['covid_1st_booster_date'].')</td><td>2nd Booster</td><td>'.$data['covid_2nd_booster'].' ('.$data['covid_2nd_booster_date'].')</td></tr>
</table><br>';

// ---------- LIFESTYLE ----------
$html .= '<h4>Personal / Social History</h4>';
$html .= '<b>Smoker:</b> '.($data['smoker'] ? "Yes (".$data['sticks_per_day']." sticks/day, ".$data['years_smoking']." yrs, Pack Years: ".$data['pack_years'].")" : "No").'<br>';
$html .= '<b>Alcohol:</b> '.($data['alcohol'] ? "Yes (".$data['alcohol_type'].", ".$data['bottles_per_day']."/".$data['alcohol_frequency'].")" : "No").'<br>';
$html .= '<b>Illicit Drugs:</b> '.($data['illicit_drugs'] ? "Yes (".$data['drug_type'].", ".$data['drug_quantity']."/".$data['drug_frequency'].")" : "No").'<br><br>';

// ---------- MATERNAL & MENSTRUAL ----------
$html .= '<h4>Maternal and Menstrual History (Females Only)</h4>
<table border="1" cellpadding="4" cellspacing="0" width="100%">
<tr><td>No. of Pregnancy</td><td>'.$data['no_pregnancy'].'</td><td>No. Alive</td><td>'.$data['no_alive'].'</td></tr>
<tr><td>No. of Stillbirth/Abortion</td><td>'.$data['no_stillbirth_abortion'].'</td><td>LMP</td><td>'.$data['lmp'].'</td></tr>
<tr><td>Menarche</td><td>'.$data['menarche'].'</td><td>Duration</td><td>'.$data['duration'].'</td></tr>
<tr><td>Amount</td><td>'.$data['amount'].'</td><td>Interval</td><td>'.$data['menstrual_interval'].'</td></tr>
<tr><td>Symptoms</td><td colspan="3">'.$data['symptoms'].'</td></tr>
<tr><td>Gyne Pathology</td><td colspan="3">'.$data['gyne_pathology'].'</td></tr>
</table><br>';

// ---------- DENTAL ----------
$html .= '<h4>Dental History</h4>
<b>Last Dental Visit:</b> '.$data['last_dental_visit'].'<br>
<b>Procedure:</b> '.$data['dental_procedure'].'<br><br>';

// ---------- PHYSICAL EXAM ----------
$html .= '<h4>Physical Examination (by Health Provider)</h4>
<table border="1" cellpadding="4" cellspacing="0" width="100%">
<tr><td>General Survey</td><td>Conscious / Coherent / Afebrile</td><td>Remarks: ___________________</td></tr>
<tr><td>Vital Signs</td><td colspan="2">BP: _____ RR: ____ Temp: ____ PR: ____ | Wt: ____ Ht: ____ | BMI: ____</td></tr>
<tr><td>Integumentary</td><td colspan="2">Pallor / Jaundice / Cyanosis / Warm to touch</td></tr>
<tr><td>HEENT</td><td colspan="2">Symmetric / Alar Flaring / Anicteric / Pale Oral Mucosa / CLAD</td></tr>
<tr><td>Chest/Heart</td><td colspan="2">Symmetrical / Wheezes / Rales / Tachycardic / Murmur</td></tr>
<tr><td>Abdomen</td><td colspan="2">Flat / Scaphoid / Tender / NABS</td></tr>
<tr><td>Extremities</td><td colspan="2">Deformities / Edema / CRT: ___ secs</td></tr>
<tr><td>Visual Acuity</td><td colspan="2">OD: ____ OS: ____ OU: ____</td></tr>
</table><br>';

// ---------- DECLARATION ----------
$html .= '
<h4>Declaration and Data Privacy Consent</h4>
<p style="text-align:justify;">
I hereby declare that the information above is accurate and complete. I understand that withholding any relevant medical information, or any misrepresentation of facts, may be used as grounds for filing cases against me in accordance with the law. 
I voluntarily and freely consent to undergo physical assessment and the collection and processing of the information above to enable the Ilocos Sur Polytechnic State College – Health Services Unit to render necessary health services. 
I was informed about the data collection process, its purpose, and the provisions of Republic Act 10173 (Data Privacy Act of 2012).
</p>
<br><br>
Signature over Printed Name: _____________________________   Date: ___________<br><br>
Healthcare Provider: ______________________________________
';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('student_health_'.$student_id.'.pdf', 'I');
