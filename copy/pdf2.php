<?php
require_once __DIR__ . '/tcpdf/tcpdf.php';
$mysqli = new mysqli("localhost", "root", "", "isps_clinica");

if ($mysqli->connect_errno) {
    die("Failed to connect: " . $mysqli->connect_error);
}

// Fetch all students with their health info
$result = $mysqli->query("
    SELECT s.*, h.*
    FROM students s
    LEFT JOIN student_health_info h ON s.student_id = h.student_id
");

$exportDir = __DIR__ . "/exports";
if (!file_exists($exportDir)) {
    mkdir($exportDir, 0777, true);
}

while ($data = $result->fetch_assoc()) {
    $student_id = $data['student_id'];

    // Create PDF
    $pdf = new TCPDF('P', 'mm', array(210, 600), true, 'UTF-8', false);
    $pdf->SetAutoPageBreak(TRUE, 15);
    $pdf->AddPage();
    $pdf->SetFont('dejavusans','',9);
    $pdf->setPrintHeader(false); // removes default line on top
    $pdf->setPrintFooter(false); // optional: remove footer line too

// ---------- HEADER ----------
$logo = __DIR__ . '/img/ispsc.png'; // absolute path for TCPDF

$html = '
<div style="text-align:center;">
  <table border="0" cellspacing="0" cellpadding="1" align="center" width="80%" style="border:none;">
    <tr style="border:none;">
        <!-- Logo -->
        <td width="20%" align="right" valign="middle" 
            style="border:none; padding-right:25px;">
            <img src="'.$logo.'" width="60">
        </td>


        <!-- Text -->
        <td width="80%" align="center" valign="middle" 
            style="line-height:1.2; vertical-align:middle; padding-top:5px; border:none;">
            <span style="font-size:9pt;">Republic of the Philippines</span><br>
            <span style="font-size:11pt; font-weight:bold;">ILOCOS SUR POLYTECHNIC STATE COLLEGE</span><br>
            <span style="font-size:9pt;">Sta. Maria Campus, Sta. Maria, Ilocos Sur</span><br>
            <span style="font-size:9pt; font-style:italic;">Health Services Unit</span>
        </td>
    </tr>
  </table>
</div>

<br>


<table border="1" cellpadding="3" cellspacing="0" width="100%">
    <tr>
        <td colspan="8" align="center"><b>STUDENT’S HEALTH RECORD FORM</b></td>
    </tr>
    <tr>
        <td colspan="4"><b>Name:</b> '.(($data['last_name']!="N/A")?$data['last_name']:"").', '.(($data['first_name']!="N/A")?$data['first_name']:"").' '.(($data['middle_name']!="N/A")?$data['middle_name']:"").' '.(($data['suffix']!="N/A")?$data['suffix']:"").'</td>
        <td><b>Age:</b> '.($data['age']!="N/A" ? $data['age'] : "").'</td>
        <td colspan="2"><b>Course/Year:</b> '.(($data['course']!="N/A")?$data['course']:"").' '.(($data['year']!="N/A")?"- Year ".$data['year']:"").'</td>
        <td><b>Birth Sex:</b> '.($data['sex']=="Male" ? "√" : "").' Male / '.($data['sex']=="Female" ? "√" : "").' Female</td>
    </tr>
    <tr>
        <td colspan="4"><b>Permanent Address:</b> '.($data['permanent_address']!="N/A" ? $data['permanent_address'] : "").'</td>
        <td colspan="2"><b>Phone No.:</b> '.($data['phone_number']!="N/A" ? $data['phone_number'] : "").'</td>
        <td colspan="2"><b>Civil Status:</b> '.($data['civil_status']!="N/A" ? $data['civil_status'] : "").'</td>
    </tr>
    <tr>
        <td colspan="4"><b>Religion:</b> '.($data['religion']!="N/A" ? $data['religion'] : "").'</td>
        <td colspan="4"><b>Contact Person (Emergency):</b> '.($data['contact_person']!="N/A" ? $data['contact_person'] : "").'</td>
    </tr>
    <tr>
        <td colspan="4"><b>Contact Address:</b> '.($data['contact_address']!="N/A" ? $data['contact_address'] : "").'</td>
        <td colspan="2"><b>Contact No.:</b> '.($data['contact_no']!="N/A" ? $data['contact_no'] : "").'</td>
        <td colspan="2" rowspan="3" align="center">1x1 Picture</td>
    </tr>
    <tr>
        <td colspan="2"><b>Blood Type:</b> '.($data['blood_type']!="N/A" ? $data['blood_type'] : "").'</td>
        <td colspan="3"><b>ALERT AND ALLERGY:</b> '.($data['allergy_alert']!="N/A" ? $data['allergy_alert'] : "").'</td>
        <td colspan="1"><b>Disability:</b> '.($data['disability']!="N/A" ? $data['disability'] : "").'</td>
    </tr>
</table>
<br>
<h4>HEALTH INFORMATION</h4>
';


    // ---------- PAST MEDICAL HISTORY ----------
    $html .= '<b>Past Medical History (√ = Yes, blank = No)</b><br>';
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
        $check = $val ? "☑" : "☐";
        $extra = "";
       if ($label=="Cancer" && $val) $extra = " (Type: ".$data['cancer_type'].")";
        if ($label=="Hepatitis" && $val) $extra = " (Type: ".$data['hepatitis_type'].")";
        if ($label=="Hepatitis" && $val) $extra = " (Type: ".$data['hepatitis_type'].")";
        $html .= '<td width="50%">'.$check.' '.$label.$extra.'</td>';
        $col++;
        if ($col == 2) { $html .= '</tr>'; $col = 0; }
    }
    if ($col == 1) $html .= '<td></td></tr>';
    $html .= '</table><br>';

    $html .= '<b>Hospitalizations:</b> Date: '.$data['hospitalization_date'].' | Diagnosis: '.$data['hospitalization_diagnosis'].' | Hospital: '.$data['hospitalization_hospital'].'<br>';
    $html .= '<b>Surgery (if any):</b> '.$data['surgery'].'<br>';
    $html .= '<b>Accidents:</b> '.$data['accidents'].'<br><br>';

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
        if ($label=="Cancer" && $val) $extra = " (Form: ".$data['fam_cancer_form'].")";
        if ($label=="Asthma" && $val) $extra = " (Form: ".$data['fam_asthma_form'].")";
        $html .= '<td width="50%">'.$check.' '.$label.$extra.'</td>';
        $col++;
        if ($col == 2) { $html .= '</tr>'; $col = 0; }
    }
    if ($col == 1) $html .= '<td></td></tr>';
    $html .= '</table><br>';
    $html .= '<b>Others:</b> '.$data['fam_others'].'<br><br>';

    // ---------- IMMUNIZATION ----------
    $html .= '<h4>Immunization History</h4>
    <table border="1" cellpadding="4" cellspacing="0" width="100%">
        <tr><td>MMR - Date Completed:</td><td>'.$data['mmr_date'].'</td>
            <td>Hepatitis Vaccine:</td><td>'.$data['hepatitis_vaccine_date'].'</td></tr>
        <tr><td>Flu Vaccine:</td><td>'.$data['flu_vaccine_date'].'</td>
            <td>Anti-Rabies:</td><td>'.$data['anti_rabies_date'].'</td></tr>
        <tr><td>Anti-Tetanus:</td><td>'.$data['anti_tetanus_date'].'</td>
            <td>PPV23:</td><td>'.$data['ppv23_date'].'</td></tr>
        <tr><td>COVID 1st Dose:</td><td>'.$data['covid_1st_dose'].' ('.$data['covid_1st_date'].')</td>
            <td>COVID 2nd Dose:</td><td>'.$data['covid_2nd_dose'].' ('.$data['covid_2nd_date'].')</td></tr>
        <tr><td>1st Booster:</td><td>'.$data['covid_1st_booster'].' ('.$data['covid_1st_booster_date'].')</td>
            <td>2nd Booster:</td><td>'.$data['covid_2nd_booster'].' ('.$data['covid_2nd_booster_date'].')</td></tr>
    </table><br>';

    // ---------- PERSONAL HISTORY ----------
    $html .= '<h4>Personal / Social History</h4>';
    $html .= '<b>Smoker:</b> '.($data['smoker'] ? "Yes (".$data['sticks_per_day']." sticks/day, ".$data['years_smoking']." yrs, Pack Years: ".$data['pack_years'].")" : "No").'<br>';
    $html .= '<b>Alcohol Drinker:</b> '.($data['alcohol'] ? "Yes (".$data['alcohol_type'].", ".$data['bottles_per_day']."/".$data['alcohol_frequency'].")" : "No").'<br>';
    $html .= '<b>Illicit Drug User:</b> '.($data['illicit_drugs'] ? "Yes (".$data['drug_type'].", ".$data['drug_quantity']."/".$data['drug_frequency'].")" : "No").'<br><br>';

    // ---------- MATERNAL ----------
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
        <tr><td>General Survey</td><td>Conscious / Afebrile / Coherent / Febrile</td><td>Remarks: ___________________</td></tr>
        <tr><td>Vital Signs</td><td colspan="2">BP: _____ RR: ____ Temp: ____ PR: ____ | Wt: ____ Ht: ____ | BMI: ____</td></tr>
        <tr><td>Integumentary</td><td colspan="2">Pallor / Jaundice / Cyanosis / Warm to touch</td></tr>
        <tr><td>HEENT</td><td colspan="2">Symmetric / Alar Flaring / Anicteric / Pale Oral Mucosa / CLAD</td></tr>
        <tr><td>Chest/Heart</td><td colspan="2">Symmetrical / Wheezes / Rales / Tachycardic / Murmur</td></tr>
        <tr><td>Abdomen</td><td colspan="2">Flat / Scaphoid / Tender / NABS</td></tr>
        <tr><td>Extremities</td><td colspan="2">Deformities / Edema / CRT: ___ secs</td></tr>
        <tr><td>Visual Acuity</td><td colspan="2">OD: ____ OS: ____ OU: ____</td></tr>
    </table><br>';

    // ---------- DECLARATION ----------
    $html .= '<h4>Declaration and Data Privacy Consent</h4>
    <p style="text-align:justify;">
    I hereby declare that the information above is accurate and complete. I understand that withholding any relevant medical information, any misrepresentation of facts
    or misleading information given by me may be used as a ground for the filing of cases against me in accordance with the law.
    I voluntarily and freely consent to undergo physical assessment and the collection and processing of the information above to enable the Ilocos Sur Polytechnic State College – Health Services Unit
    to render necessary Health services to all its clients. I also declare that I was excellently informed on the process of data collection, purpose of this medical
    information, and the Provisions of Republic Act 10173, Data Privacy Act of 2012.
    </p>
    <br><br>
    Signature over Printed Name: _____________________________   Date: ___________<br><br>
    Healthcare Provider: ______________________________________
    ';

    // Add to PDF
    $pdf->writeHTML($html, true, false, true, false, '');
    // Save each file
  $pdf->Output("student_health_".$student_id.".pdf", "I"); // "I" = inline view in browser
exit;

}
