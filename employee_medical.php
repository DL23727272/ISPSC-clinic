<?php
// Include database connection
include 'db_connection.php'; // Use your actual DB connection file

// Initialize variables for success and error messages
$message = '';
$error = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $data = $_POST;

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO medical_records 
        (lastname, firstname, middlename, age, gender, birthdate, address, phone, civil_status, religion, guardian, 
         asthma, diabetes, hypertension, seizure, heart_disease, allergy, tb, others,
         fam_asthma, fam_diabetes, fam_hypertension, fam_heart,
         bp, hr, rr, temp, height, weight, remarks, management, sign_date)
         VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssssssssssssssssssssssssssss",
        $data['lastname'], $data['firstname'], $data['middlename'], $data['age'], $data['gender'], $data['birthdate'],
        $data['address'], $data['phone'], $data['civil_status'], $data['religion'], $data['guardian'],
        isset($data['asthma']) ? $data['asthma'] : null,
        isset($data['diabetes']) ? $data['diabetes'] : null,
        isset($data['hypertension']) ? $data['hypertension'] : null,
        isset($data['seizure']) ? $data['seizure'] : null,
        isset($data['heart_disease']) ? $data['heart_disease'] : null,
        isset($data['allergy']) ? $data['allergy'] : null,
        isset($data['tb']) ? $data['tb'] : null,
        isset($data['others']) ? $data['others'] : null,
        isset($data['fam_asthma']) ? $data['fam_asthma'] : null,
        isset($data['fam_diabetes']) ? $data['fam_diabetes'] : null,
        isset($data['fam_hypertension']) ? $data['fam_hypertension'] : null,
        isset($data['fam_heart']) ? $data['fam_heart'] : null,
        $data['bp'], $data['hr'], $data['rr'], $data['temp'], $data['height'], $data['weight'],
        $data['remarks'], $data['management'], $data['sign_date']
    );

    // Execute the statement and check for success
    if ($stmt->execute()) {
        $message = "Medical record submitted successfully.";
    } else {
        $error = "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISPSC CLINICA - Employee Medical Form</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #fff; }
        .form-title { text-align: center; font-weight: bold; font-size: 1.2em; margin-bottom: 10px; }
        .subtitle { text-align: center; font-size: 1em; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        td, th { border: 1px solid #333; padding: 4px; font-size: 0.95em; vertical-align: top; }
        .section-header { background: #e6f2ff; font-weight: bold; text-align: left; }
        .checkbox-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 2px; }
        .label-bold { font-weight: bold; }
        input[type="text"], input[type="date"], input[type="number"], select, textarea { width: 98%; padding: 2px; font-size: 0.95em; }
        .no-border { border: none !important; }
        .signature-line { border-bottom: 1px solid #333; width: 200px; display: inline-block; }
        .success-message { color: green; }
        .error-message { color: red; }
    </style>
</head>
<body>
    <div class="form-title">
        Republic of the Philippines<br>
        ILOCOS SUR POLYTECHNIC STATE COLLEGE<br>
        Sta. Maria Campus, Sta. Maria, Ilocos Sur<br>
        <span class="subtitle">OPHCL Health Form</span>
    </div>
    <?php if ($message): ?><div class="success-message"><?php echo $message; ?></div><?php endif; ?>
    <?php if ($error): ?><div class="error-message"><?php echo $error; ?></div><?php endif; ?>
    <form method="post">
        <table>
            <tr>
                <td colspan="2">Name: <input type="text" name="name_full"></td>
                <td>Sex: <select name="sex"><option>Male</option><option>Female</option></select></td>
                <td>Date of Birth: <input type="date" name="dob"></td>
            </tr>
            <tr>
                <td>Permanent Address: <input type="text" name="address"></td>
                <td>Civil Status: <input type="text" name="civil_status"></td>
                <td colspan="2">Contact Person (In case of Emergency): <input type="text" name="emergency_contact"></td>
            </tr>
            <tr>
                <td>Blood Type: <input type="text" name="blood_type"></td>
                <td colspan="3">ALERT AND ALLERGY: <input type="text" name="alert_allergy"></td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="section-header" colspan="4">Past Medical History</td>
            </tr>
            <tr>
                <td>
                    <label><input type="checkbox" name="pmh_chickenpox"> Chicken Pox</label><br>
                    <label><input type="checkbox" name="pmh_measles"> Measles</label><br>
                    <label><input type="checkbox" name="pmh_mumps"> Mumps</label><br>
                    <label><input type="checkbox" name="pmh_rheumatic"> Rheumatic Fever</label>
                </td>
                <td>
                    <label><input type="checkbox" name="pmh_bronchial"> Bronchial Asthma</label><br>
                    <label><input type="checkbox" name="pmh_pneumonia"> Pneumonia</label><br>
                    <label><input type="checkbox" name="pmh_ptb"> PTB</label><br>
                    <label><input type="checkbox" name="pmh_epilepsy"> Epilepsy</label>
                </td>
                <td>
                    <label><input type="checkbox" name="pmh_heart"> Heart Disease</label><br>
                    <label><input type="checkbox" name="pmh_blood"> Blood Transfusion</label><br>
                    <label><input type="checkbox" name="pmh_surgery"> Surgery/Operation</label>
                </td>
                <td>
                    <label><input type="checkbox" name="pmh_anticoagulants"> Anti-coagulants</label><br>
                    <label><input type="checkbox" name="pmh_others"> Others: <input type="text" name="pmh_others_text"></label>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="section-header" colspan="4">Family Medical History</td>
            </tr>
            <tr>
                <td><label><input type="checkbox" name="fmh_hypertension"> Hypertension</label></td>
                <td><label><input type="checkbox" name="fmh_thyroid"> Thyroid Disease</label></td>
                <td><label><input type="checkbox" name="fmh_bronchial"> Bronchial Asthma</label></td>
                <td><label><input type="checkbox" name="fmh_diabetes"> Diabetes</label></td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="section-header" colspan="4">Immunization History</td>
            </tr>
            <tr>
                <td>BCG: <input type="text" name="im_bcg"></td>
                <td>DPT: <input type="text" name="im_dpt"></td>
                <td>Polio: <input type="text" name="im_polio"></td>
                <td>Measles: <input type="text" name="im_measles"></td>
            </tr>
            <tr>
                <td>Hepatitis Vaccine: <input type="text" name="im_hepatitis"></td>
                <td>FLU Vaccine: <input type="text" name="im_flu"></td>
                <td>IPV/OPV: <input type="text" name="im_ipv"></td>
                <td>Others: <input type="text" name="im_others"></td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="section-header" colspan="4">Personal Social History</td>
            </tr>
            <tr>
                <td>Alcohol Drinker: <input type="text" name="psh_alcohol"></td>
                <td>Type of Alcohol: <input type="text" name="psh_alcohol_type"></td>
                <td>Frequency: <input type="text" name="psh_alcohol_freq"></td>
                <td>Habit Drug User: <input type="text" name="psh_drug"></td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="section-header" colspan="4">Maternal and Maternal History (for Females Only)</td>
            </tr>
            <tr>
                <td>No. of Pregnancy: <input type="text" name="mat_pregnancy"></td>
                <td>LMP: <input type="text" name="mat_lmp"></td>
                <td>No. of Abortion: <input type="text" name="mat_abortion"></td>
                <td>Menarche: <input type="text" name="mat_menarche"></td>
            </tr>
            <tr>
                <td colspan="2">Gyne Pathology: <input type="text" name="mat_pathology"></td>
                <td colspan="2">Amount: <input type="text" name="mat_amount"></td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="section-header" colspan="4">Dental History</td>
            </tr>
            <tr>
                <td>Last Dental Visit: <input type="text" name="dental_last_visit"></td>
                <td colspan="3">Procedure Done: <input type="text" name="dental_procedure"></td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="section-header" colspan="4">Physical Examination (to be filled up by Health Provider)</td>
            </tr>
            <tr>
                <td>Height: <input type="text" name="pe_height"></td>
                <td>Weight: <input type="text" name="pe_weight"></td>
                <td>BP: <input type="text" name="pe_bp"></td>
                <td>Pulse Rate: <input type="text" name="pe_pulse"></td>
            </tr>
            <tr>
                <td>Respiratory Rate: <input type="text" name="pe_rr"></td>
                <td>Temperature: <input type="text" name="pe_temp"></td>
                <td colspan="2">Others: <input type="text" name="pe_others"></td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="section-header" colspan="4">Diagnosis</td>
            </tr>
            <tr>
                <td colspan="4"><textarea name="diagnosis" rows="2"></textarea></td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="section-header" colspan="4">Hospitalization / Surgery (if any)</td>
            </tr>
            <tr>
                <td colspan="4"><textarea name="hospitalization" rows="2"></textarea></td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="section-header" colspan="4">Declaration & Data Privacy Consent</td>
            </tr>
            <tr>
                <td colspan="4">
                    I hereby declare that the information above is correct to the best of my knowledge and I consent to the collection and processing of my personal and medical information in accordance with RA 10173 - Data Privacy Act of 2012.<br><br>
                    Signature of Healthcare Provider: <span class="signature-line"></span><br>
                    Date: <input type="date" name="sign_date">
                </td>
            </tr>
        </table>
        <br>
        <input type="submit" value="Submit Form">
    </form>
    <button type="button" onclick="window.history.back();">Back</button>
</body>
</html>
