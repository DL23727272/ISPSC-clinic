<?php
require_once 'db_connection.php';
$employee_id = $_GET['employee_id'] ?? '';
if(!$employee_id) exit('Invalid Employee ID');

$stmt = $conn->prepare("SELECT * FROM employees WHERE employee_id=?");
$stmt->bind_param("s", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();
$stmt->close();

// Dropdown options
$sex_options = ["Male","Female"];
$civil_status_options = ["Single","Married","Divorced","Widowed"];
$religion_options = ["Roman Catholic","Christian","Islam","Iglesia ni Cristo","Others"];
$suffix_options = ["N/A","Jr.","Sr.","I","II","III","IV","V","VI"];
?>

<form method="POST" id="editEmployeeForm">

    <div class="row mb-3">
        <div class="col-md-4">
            <label>Employee ID</label>
            <input type="text" name="employee_id_display" value="<?php echo htmlspecialchars($employee['employee_id']); ?>" class="form-control" disabled>
            <input type="hidden" name="employee_id" value="<?php echo htmlspecialchars($employee['employee_id']); ?>">
        </div>
        <div class="col-md-4">
            <label>Campus</label>
            <select class="form-select" name="campus" required>
                <option value="">SELECT CAMPUS</option>
                <option value="SANTA MARIA" <?php if($employee['campus']=="SANTA MARIA") echo 'selected'; ?>>SANTA MARIA</option>
                <option value="NARVACAN" <?php if($employee['campus']=="NARVACAN") echo 'selected'; ?>>NARVACAN</option>
                <option value="CANDON" <?php if($employee['campus']=="CANDON") echo 'selected'; ?>>CANDON</option>
                <option value="MAIN CAMPUS" <?php if($employee['campus']=="MAIN CAMPUS") echo 'selected'; ?>>MAIN CAMPUS</option>
                <option value="TAGUDIN" <?php if($employee['campus']=="TAGUDIN") echo 'selected'; ?>>TAGUDIN</option>
                <option value="CERVANTES" <?php if($employee['campus']=="CERVANTES") echo 'selected'; ?>>CERVANTES</option>
                <option value="SANTIAGO" <?php if($employee['campus']=="SANTIAGO") echo 'selected'; ?>>SANTIAGO</option>
            </select>
        </div>
    </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label>Last Name</label>
                <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($employee['last_name']); ?>" required>
            </div>
            <div class="col-md-4">
                <label>First Name</label>
                <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($employee['first_name']); ?>" required>
            </div>
            <div class="col-md-2">
                <label>Middle Name</label>
                <input type="text" class="form-control" name="middle_name" value="<?php echo htmlspecialchars($employee['middle_name']); ?>">
            </div>
            <div class="col-md-2">
                <label>Suffix</label>
                <select class="form-select" name="suffix">
                    <?php foreach($suffix_options as $suf): ?>
                        <option value="<?php echo $suf; ?>" <?php if($employee['suffix']==$suf) echo 'selected'; ?>><?php echo $suf; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <label>Age</label>
            <input type="number" class="form-control" name="age" value="<?php echo htmlspecialchars($employee['age']); ?>">
        </div>
        <div class="col-md-4">
            <label>Birthdate</label>
            <input type="date" class="form-control" name="birthdate" value="<?php echo htmlspecialchars($employee['birthdate']); ?>">
        </div>
        <div class="col-md-4">
            <label>Sex</label>
            <select class="form-select" name="sex">
                <option value="">-- Select --</option>
                <?php foreach($sex_options as $sex): ?>
                    <option value="<?php echo $sex; ?>" <?php if($employee['sex']==$sex) echo 'selected'; ?>><?php echo $sex; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label>Civil Status</label>
            <select class="form-select" name="civil_status">
                <option value="">-- Select --</option>
                <?php foreach($civil_status_options as $cs): ?>
                    <option value="<?php echo $cs; ?>" <?php if($employee['civil_status']==$cs) echo 'selected'; ?>><?php echo $cs; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label>Religion</label>
            <select class="form-select" name="religion">
                <option value="">-- Select --</option>
                <?php foreach($religion_options as $rel): ?>
                    <option value="<?php echo $rel; ?>" <?php if($employee['religion']==$rel) echo 'selected'; ?>><?php echo $rel; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- Address & Contact -->
    <div class="mb-3">
        <label>Permanent Address</label>
        <input type="text" class="form-control" name="permanent_address" value="<?php echo htmlspecialchars($employee['permanent_address']); ?>">
    </div>
    <div class="mb-3">
        <label>Phone Number</label>
        <input type="text" class="form-control" name="phone_number" value="<?php echo htmlspecialchars($employee['phone_number']); ?>">
    </div>

    <hr>
    <h5>Contact Person (Emergency)</h5>
    <div class="row mb-3">
        <div class="col-md-4">
            <label>Full Name</label>
            <input type="text" class="form-control" name="contact_person" value="<?php echo htmlspecialchars($employee['contact_person']); ?>">
        </div>
        <div class="col-md-4">
            <label>Address</label>
            <input type="text" class="form-control" name="contact_address" value="<?php echo htmlspecialchars($employee['contact_address']); ?>">
        </div>
        <div class="col-md-4">
            <label>Contact Number</label>
            <input type="text" class="form-control" name="contact_number" value="<?php echo htmlspecialchars($employee['contact_no']); ?>">
        </div>
    </div>

    <hr>
    <div class="row mb-3">
        <div class="col-md-6">
            <label>Email</label>
            <input type="email" class="form-control" name="register_email" value="<?php echo htmlspecialchars($employee['email']); ?>" required>
        </div>
        <div class="col-md-6">
            <label>Password</label>
            <input type="password" class="form-control" name="register_password" placeholder="Leave blank to keep current">
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100">Update Employee</button>
</form>
