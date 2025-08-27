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
    // $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf = new TCPDF('P', 'mm', 'LETTER', true, 'UTF-8', false);


    // $pdf = new TCPDF('P', 'mm', array(210, 600), true, 'UTF-8', false);
   $pdf->SetMargins(10, 10, 10);   // left, top, right margins in mm
    $pdf->SetAutoPageBreak(TRUE, 10); // auto break with bottom margin of 10mm

    $pdf->AddPage();
    $pdf->SetFont('dejavusans','',9);
 
// ---------- HEADER ----------
$logo = __DIR__ . '/img/ispsc.png'; // absolute path for TCPDF
$html = '
<table border="1" cellpadding="3" cellspacing="0" width="100%">

    <!-- HEADER -->
<tr>
    <td colspan="8" align="center" style="border:none;">
        <table border="0" width="100%" style="border:none;">
            <tr valign="middle">
                <!-- Logo -->
                <td width="15%" align="right" style="border:none; ">
                    <img src="'.$logo.'" width="60"/>
                </td>

                <!-- Text -->
                <td width="65%" align="center" style="border:none; line-height:1.4; vertical-align:middle;">
                    <div style="text-align:center;">
                        <span style="font-size:9pt;">Republic of the Philippines</span><br>
                        <span style="font-size:11pt; font-weight:bold;">ILOCOS SUR POLYTECHNIC STATE COLLEGE</span><br>
                        <span style="font-size:9pt;">Sta. Maria Campus, Sta. Maria, Ilocos Sur</span><br>
                        <span style="font-size:9pt; font-style:italic;">Health Services Unit</span>
                    </div>
                </td>
            </tr>
        </table>
    </td>
</tr>



    <!-- MAIN TITLE -->
   <tr>
        <td colspan="8" align="center" 
            style="background-color:#cfe2f3; font-weight:bold; padding:5px;">
            HEALTH INFORMATION
        </td>
    </tr>


     <!--STUDENT INFO -->
    <tr>
        <td colspan="4"><b>Name:</b> '.$data['last_name'].', '.$data['first_name'].' '.$data['middle_name'].' '.$data['suffix'].'</td>
        <td><b>Age:</b> '.$data['age'].'</td>
        <td colspan="2"><b>Course/Year:</b> '.$data['course'].' - Year '.$data['year'].'</td>
        <td><b>Birth Sex:</b> '.($data['sex']=="Male" ? "☑" : "☐").' Male / '.($data['sex']=="Female" ? "☑" : "☐").' Female</td>
    </tr>
    <tr>
        <td colspan="4"><b>Permanent Address:</b> '.$data['permanent_address'].'</td>
        <td colspan="2"><b>Phone No.:</b> '.$data['phone_number'].'</td>
        <td colspan="2"><b>Civil Status:</b> '.$data['civil_status'].'</td>
    </tr>
    <tr>
        <td colspan="4"><b>Religion:</b> '.$data['religion'].'</td>
        <td colspan="4"><b>Contact Person (Emergency):</b> '.$data['contact_person'].'</td>
    </tr>
    <tr>
        <td colspan="4"><b>Contact Address:</b> '.$data['contact_address'].'</td>
        <td colspan="2"><b>Contact No.:</b> '.$data['contact_no'].'</td>
            <!--<td colspan="2" rowspan="4" align="center">1x1 Picture</td>-->
        
    </tr>
    <tr>
        <td colspan="2"><b>Blood Type:</b> '.$data['blood_type'].'</td>
        <td colspan="3"><b>ALERT AND ALLERGY:</b> '.$data['allergy_alert'].'</td>
        <td><b>Disability:</b> '.$data['disability'].'</td>
    </tr>

    <!-- SECTION: PAST MEDICAL HISTORY -->
    <tr><td colspan="8" style="background-color:#cfe2f3; padding:5px;" align="center"><b>HEALTH INFORMATION</b><br>(Instructions: For items that are not Applicable, LEAVE IT BLANK. Mark with (√) if YES, and Leave it Blank for NO)</td></tr>
    <tr><td colspan="8" align="center" style="background-color:#cfe2f3;  padding:5px;"><b>Past Medical History</b></td></tr>';
    
// Generate Past Medical History rows
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
$col = 0;
foreach ($pmh as $label=>$val) {
    if ($col == 0) $html .= '<tr>';
    $check = $val ? "☑" : "☐";
    $extra = "";
    if ($label=="Cancer" && $val) $extra = " (Type: ".$data['cancer_type'].")";
    if ($label=="Hepatitis" && $val) $extra = " (Type: ".$data['hepatitis_type'].")";
    $html .= '<td colspan="4">'.$check.' '.$label.$extra.'</td>';
    $col++;
    if ($col == 2) { $html .= '</tr>'; $col = 0; }
}
if ($col == 1) $html .= '<td colspan="4"></td></tr>';

$html .= '
    <tr><td colspan="8"><b>Hospitalizations:</b> '.$data['hospitalization_date'].' | '.$data['hospitalization_diagnosis'].' | '.$data['hospitalization_hospital'].'</td></tr>
    <tr><td colspan="8"><b>Surgery:</b> '.$data['surgery'].'</td></tr>
    <tr><td colspan="8"><b>Accidents:</b> '.$data['accidents'].'</td></tr>

    <!-- FAMILY HISTORY -->
    <tr><td colspan="8" align="center" style="background-color:#cfe2f3;  padding:5px;"><b>FAMILY HISTORY</b></td></tr>';
    
$fh = [
    "Hypertension"=>$data['fam_hypertension'],
    "Diabetes"=>$data['fam_diabetes'],
    "Cancer"=>$data['fam_cancer'],
    "Asthma"=>$data['fam_asthma'],
    "Heart Disease"=>$data['fam_heart'],
    "Thyroid Disease"=>$data['fam_thyroid'],
    "Autoimmune Disease"=>$data['fam_autoimmune']
];
$col = 0;
foreach ($fh as $label=>$val) {
    if ($col == 0) $html .= '<tr>';
    $check = $val ? "☑" : "☐";
    $extra = "";
    if ($label=="Cancer" && $val) $extra = " (Form: ".$data['fam_cancer_form'].")";
    if ($label=="Asthma" && $val) $extra = " (Form: ".$data['fam_asthma_form'].")";
    $html .= '<td colspan="4">'.$check.' '.$label.$extra.'</td>';
    $col++;
    if ($col == 2) { $html .= '</tr>'; $col = 0; }
}
if ($col == 1) $html .= '<td colspan="4"></td></tr>';
$html .= '<tr><td colspan="8"><b>Others:</b> '.$data['fam_others'].'</td></tr>

    <!-- IMMUNIZATION -->
    <tr><td colspan="8" align="center" style="background-color:#cfe2f3;  padding:5px;"><b>IMMUNIZATION HISTORY</b></td></tr>
    <tr><td colspan="2">MMR:</td><td colspan="2">'.$data['mmr_date'].'</td><td colspan="2">Hepatitis Vaccine:</td><td colspan="2">'.$data['hepatitis_vaccine_date'].'</td></tr>
    <tr><td colspan="2">Flu Vaccine:</td><td colspan="2">'.$data['flu_vaccine_date'].'</td><td colspan="2">Anti-Rabies:</td><td colspan="2">'.$data['anti_rabies_date'].'</td></tr>
    <tr><td colspan="2">Anti-Tetanus:</td><td colspan="2">'.$data['anti_tetanus_date'].'</td><td colspan="2">PPV23:</td><td colspan="2">'.$data['ppv23_date'].'</td></tr>
    <tr><td colspan="2">COVID 1st Dose:</td><td colspan="2">'.$data['covid_1st_dose'].' ('.$data['covid_1st_date'].')</td><td colspan="2">COVID 2nd Dose:</td><td colspan="2">'.$data['covid_2nd_dose'].' ('.$data['covid_2nd_date'].')</td></tr>
    <tr><td colspan="2">1st Booster:</td><td colspan="2">'.$data['covid_1st_booster'].' ('.$data['covid_1st_booster_date'].')</td><td colspan="2">2nd Booster:</td><td colspan="2">'.$data['covid_2nd_booster'].' ('.$data['covid_2nd_booster_date'].')</td></tr>

    <!-- PERSONAL HISTORY -->
    <tr><td colspan="8" align="center" style="background-color:#cfe2f3;  padding:5px;"><b>PERSONAL / SOCIAL HISTORY</b></td></tr>
    <tr><td colspan="8"><b>Smoker:</b> '.($data['smoker'] ? "Yes (".$data['sticks_per_day']." sticks/day, ".$data['years_smoking']." yrs, Pack Years: ".$data['pack_years'].")" : "No").'</td></tr>
    <tr><td colspan="8"><b>Alcohol Drinker:</b> '.($data['alcohol'] ? "Yes (".$data['alcohol_type'].", ".$data['bottles_per_day']."/".$data['alcohol_frequency'].")" : "No").'</td></tr>
    <tr><td colspan="8"><b>Illicit Drug User:</b> '.($data['illicit_drugs'] ? "Yes (".$data['drug_type'].", ".$data['drug_quantity']."/".$data['drug_frequency'].")" : "No").'</td></tr>

    <!-- MATERNAL -->
    <tr><td colspan="8" align="center" style="background-color:#cfe2f3;  padding:5px;"><b>MATERNAL AND MENSTRUAL HISTORY (Females Only)</b></td></tr>
    <tr><td colspan="2">No. of Pregnancy</td><td colspan="2">'.$data['no_pregnancy'].'</td><td colspan="2">No. Alive</td><td colspan="2">'.$data['no_alive'].'</td></tr>
    <tr><td colspan="2">Stillbirth/Abortion</td><td colspan="2">'.$data['no_stillbirth_abortion'].'</td><td colspan="2">LMP</td><td colspan="2">'.$data['lmp'].'</td></tr>
    <tr><td colspan="2">Menarche</td><td colspan="2">'.$data['menarche'].'</td><td colspan="2">Duration</td><td colspan="2">'.$data['duration'].'</td></tr>
    <tr><td colspan="2">Amount</td><td colspan="2">'.$data['amount'].'</td><td colspan="2">Interval</td><td colspan="2">'.$data['menstrual_interval'].'</td></tr>
    <tr><td colspan="2">Symptoms</td><td colspan="6">'.$data['symptoms'].'</td></tr>
    <tr><td colspan="2">Gyne Pathology</td><td colspan="6">'.$data['gyne_pathology'].'</td></tr>

    <!-- DENTAL -->
    <tr><td colspan="8" align="center" style="background-color:#cfe2f3;  padding:5px;"><b>DENTAL HISTORY</b></td></tr>
    <tr><td colspan="8"><b>Last Dental Visit:</b> '.$data['last_dental_visit'].'</td></tr>
    <tr><td colspan="8"><b>Procedure:</b> '.$data['dental_procedure'].'</td></tr>

    <!-- PHYSICAL EXAM -->
    <tr><td colspan="8" align="center" style="background-color:#cfe2f3;  padding:5px;"><b>PHYSICAL EXAMINATION (by Health Provider)</b></td></tr>
    <tr><td colspan="2">General Survey</td><td colspan="3">Conscious / Afebrile / Coherent / Febrile</td><td colspan="3">Remarks: _________</td></tr>
    <tr><td colspan="2">Vital Signs</td><td colspan="6">BP: ___ RR: ___ Temp: ___ PR: ___ | Wt: ___ Ht: ___ | BMI: ___</td></tr>
    <tr><td colspan="2">Integumentary</td><td colspan="6">Pallor / Jaundice / Cyanosis / Warm to touch</td></tr>
    <tr><td colspan="2">HEENT</td><td colspan="6">Symmetric / Alar Flaring / Anicteric / Pale Oral Mucosa / CLAD</td></tr>
    <tr><td colspan="2">Chest/Heart</td><td colspan="6">Symmetrical / Wheezes / Rales / Tachycardic / Murmur</td></tr>
    <tr><td colspan="2">Abdomen</td><td colspan="6">Flat / Scaphoid / Tender / NABS</td></tr>
    <tr><td colspan="2">Extremities</td><td colspan="6">Deformities / Edema / CRT: ___ secs</td></tr>
    <tr><td colspan="2">Visual Acuity</td><td colspan="6">OD: ___ OS: ___ OU: ___</td></tr>

    <!-- DECLARATION -->
    <tr><td colspan="8" align="center" style="background-color:#cfe2f3;  padding:5px;"><b>DECLARATION AND DATA PRIVACY CONSENT</b></td></tr>
    <tr><td colspan="8">
        I hereby declare that the information above is accurate and complete. I understand that withholding any relevant medical information, any misrepresentation of facts
        or misleading information given by me may be used as a ground for the filing of cases against me in accordance with the law.<br><br>
        I voluntarily and freely consent to undergo physical assessment and the collection and processing of the information above to enable the Ilocos Sur Polytechnic State College – Health Services Unit
        to render necessary Health services to all its clients. I also declare that I was excellently informed on the process of data collection, purpose of this medical
        information, and the Provisions of Republic Act 10173, Data Privacy Act of 2012.<br><br>
        Signature over Printed Name: _____________________________   Date: ___________<br><br>
        Healthcare Provider: ______________________________________
    </td></tr>

</table>';


    // Add to PDF
    $pdf->writeHTML($html, true, false, true, false, '');
    // Save each file
  $pdf->Output("student_health_".$student_id.".pdf", "I"); // "I" = inline view in browser
exit;

}
