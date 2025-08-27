
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-..." crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Alertify CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css" />
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap">

    <style>
        /* General Styles */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            position: relative;
            min-height: 100vh;
            background-color: #f5f5f5;
        }

        /* Enhanced background styling */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('image/bgpics.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: blur(8px) brightness(0.8);
            -webkit-filter: blur(8px) brightness(0.8);
            z-index: -1;
            opacity: 0.4;
        }

        /* Enhanced container styling */
        .container {
            max-width: 900px;
            margin: 100px auto 40px;
            background: rgba(255, 255, 255, 0.98);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
            transition: all 0.3s ease;
        }

        /* Enhanced nav styling */
        nav {
            background-color: #006400;
            padding: 15px 0;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        nav ul {
            list-style: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
            margin: 0;
            max-width: 1200px;
            margin: 0 auto;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            padding: 12px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        nav ul li a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .nav-logo {
            width: 180px;
            height: auto;
            transition: transform 0.3s ease;
        }

        .nav-logo:hover {
            transform: scale(1.05);
        }

        /* Enhanced form styling */
        .form-section {
            margin-bottom: 40px;
            padding: 30px;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .form-section:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 0.95rem;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="date"]:focus,
        select:focus {
            border-color: #006400;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 100, 0, 0.1);
            background-color: #fff;
        }

        /* Enhanced button styling */
        button,
        input[type="submit"],
        .btn-next {
            background-color: #006400;
            color: white;
            border: none;
            padding: 12px 25px;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        button:hover,
        input[type="submit"]:hover,
        .btn-next:hover {
            background-color: #004d00;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Enhanced file input styling */
        input[type="file"] {
            padding: 10px;
            border: 2px dashed #e0e0e0;
            border-radius: 8px;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        input[type="file"]:hover {
            border-color: #006400;
            background-color: #f8f9fa;
        }

        /* Progress indicator */
        .progress-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding: 0 20px;
        }

        .progress-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            flex: 1;
        }

        .progress-step::before {
            content: '';
            position: absolute;
            top: 15px;
            left: -50%;
            width: 100%;
            height: 2px;
            background-color: #e0e0e0;
            z-index: 1;
        }

        .progress-step:first-child::before {
            display: none;
        }

        .step-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #e0e0e0;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 8px;
            position: relative;
            z-index: 2;
        }

        .step-label {
            font-size: 0.9rem;
            color: #666;
            text-align: center;
        }

        .progress-step.active .step-number {
            background-color: #006400;
            color: white;
        }

        .progress-step.active .step-label {
            color: #006400;
            font-weight: 500;
        }

        /* Enhanced validation styling */
        .is-invalid {
            border-color: #dc3545 !important;
            background-color: #fff8f8 !important;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 5px;
            display: none;
        }

        .is-invalid + .invalid-feedback {
            display: block;
        }

        /* Loading spinner */
        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
            margin-left: 8px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Responsive enhancements */
        @media (max-width: 768px) {
            .container {
                margin: 80px 15px 30px;
                padding: 20px;
            }

            .form-section {
                padding: 20px;
            }

            .name-inputs {
                flex-direction: column;
                gap: 15px;
            }

            .progress-indicator {
                flex-wrap: wrap;
                gap: 15px;
            }

            .progress-step {
                flex: 0 0 calc(50% - 15px);
            }
        }

        /* Accessibility enhancements */
        .visually-hidden {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            border: 0;
        }

        /* Focus styles for accessibility */
        *:focus-visible {
            outline: 3px solid #006400;
            outline-offset: 2px;
        }
    </style>
</head>

<body>
    <nav>
        <ul>
            <li>
                <a href="dashboard.php">
                    <img src="image/ease-kolar secondary logo.png" alt="EASE-KOLAR Logo" class="nav-logo">
                </a>
            </li>
            <li class="logout">
                <a href="login.php"><i class="fas fa-sign-out-alt"></i> LOGOUT</a>
            </li>
        </ul>
    </nav>

    <div class="container">
        <div class="logo">
            <img src="image/Logowhite2.png" alt="EASE-KOLAR Logo" width="150">
        </div>
        <div class="instructions">
            <h1>SCHOLARSHIP REGISTRY</h1>
            <p>Instructions: Please fill out the form LEGIBLY in FULL CAPS, COMPLETELY. INCOMPLETE/IMPROPERLY FILLED OUT FORMS WILL NOT BE PROCESSED.</p>
        </div>

        <!-- Add progress indicator -->
        <div class="progress-indicator">
            <div class="progress-step active" data-step="personal-details">
                <div class="step-number">1</div>
                <div class="step-label">Personal Details</div>
            </div>
            <div class="progress-step" data-step="family-background">
                <div class="step-number">2</div>
                <div class="step-label">Family Background</div>
            </div>
            <div class="progress-step" data-step="educational-details">
                <div class="step-number">3</div>
                <div class="step-label">Educational Details</div>
            </div>
            <div class="progress-step" data-step="scholarship-details">
                <div class="step-number">4</div>
                <div class="step-label">Scholarship Details</div>
            </div>
        </div>

        <form id="scholarship-form" action="submission_success.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm(event)">
            <input type="hidden" name="csrf_token" value="a60281b9c1c4cfeee15920d6afbb08a96e596fc69024ce19341951713f045813">
            <div id="personal-details" class="form-section">
                <h3>Personal Details</h3>

                <div class="form-group">
                    <label for="full_name">Full Name:</label>
                    <div class="name-inputs">
                        <input type="text" id="last_name" name="last_name" placeholder="Last Name" required>
                        <input type="text" id="first_name" name="first_name" placeholder="First Name" required>
                        <input type="text" id="middle_name" name="middle_name" placeholder="Middle Name" required>
                        <label for="extension_name">Extension Name:</label>
                        <select id="extension_name" name="extension_name" aria-label="Extension Name">
                            <option value="N/A">N/A</option>
                            <option value="I">I</option>
                            <option value="II">II</option>
                            <option value="III">III</option>
                            <option value="JR">JR</option>
                            <option value="SR">SR</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender" aria-label="Gender" required>
                        <option value="MALE">MALE</option>
                        <option value="FEMALE">FEMALE</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="birthday">Birthday:</label>
                    <input type="date" id="birthday" name="birthday" aria-label="Birthday" required>
                </div>

                <div class="form-group">
                    <label for="citizenship">Citizenship:</label>
                    <select id="citizenship" name="citizenship" aria-label="Citizenship" required>
                        <option value="FILIPINO">FILIPINO</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="civil_status">Civil Status :</label>
                    <select id="civil_status" name="civil_status" aria-label="Civil Status" required>
                        <option value="SINGLE">SINGLE</option>
                        <option value="MARRIED">MARRIED</option>
                        <option value="WIDOWED">WIDOWED</option>
                        <option value="LEGALLY SEPARATED/DIVORCED">LEGALLY SEPARATED/DIVORCED</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           aria-label="Email" 
                           value="dldoesvisuals@gmail.com" 
                           readonly 
                           required>
                </div>

                <div class="form-group">
                    <label for="contact_number">Contact Number:</label>
                    <input type="text" 
                           id="contact_number" 
                           name="contact_number" 
                           aria-label="Contact Number" 
                           placeholder="Enter your contact number" 
                           pattern="[0-9]+" 
                           maxlength="11" 
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                           required>
                </div>

                <div class="form-group">
                    <label for="province">Province:</label>
                    <input type="text" id="province" name="province" placeholder="Enter your province" required>
                </div>

                <div class="form-group">
                    <label for="town_city">Town/City:</label>
                    <input type="text" id="town_city" name="town_city" placeholder="Enter your town/city" required>
                </div>

                <div class="form-group">
                    <label for="barangay">Barangay:</label>
                    <input type="text" id="barangay" name="barangay" placeholder="Enter your barangay" required>
                </div>

                <div class="form-group">
                    <label for="zip_code">Zip Code:</label>
                    <input type="text" 
                           id="zip_code" 
                           name="zip_code" 
                           placeholder="Enter your zip code" 
                           pattern="[0-9]+" 
                           maxlength="4" 
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                           required>
                </div>
                
                <button type="button" class="btn-next" onclick="showSection('family-background')">Next</button>
            </div>

            <div id="family-background" class="form-section">
                <h3>Family Background</h3>

                <div class="form-group">
                    <label for="father_name">Father's Name:</label>
                    <input type="text" id="father_name" name="father_name" placeholder="Enter your father's name" required>
                </div>

                <div class="form-group">
                    <label for="mother_name">Mother's Maiden Name:</label>
                    <input type="text" id="mother_name" name="mother_name" placeholder="NAGAN KEN APILYEDO NI NANANG MO IDI BALASANG" required>
                </div>

                <div class="form-group">
                    <label for="emergency_no">Emergency Contact Number:</label>
                    <input type="text" 
                           id="emergency_no" 
                           name="emergency_no" 
                           placeholder="Enter emergency contact number" 
                           pattern="[0-9]+" 
                           maxlength="11" 
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                           required>
                </div>

                <div class="form-group">
                    <label for="family_income"> Family Income:</label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%);">₱</span>
                        <input type="text" id="family_income_display" placeholder="Enter family income" required style="padding-left: 20px;">
                        <input type="hidden" id="family_income" name="family_income">
                    </div>
                </div>

                <div class="form-group">
                    <label for="hh_num">No. of Household Members:</label>
                    <input type="text" 
                           id="hh_num" 
                           name="hh_num" 
                           placeholder="Enter number of household members (Kung ilan kayo sa bahay niyo!)" 
                           pattern="[0-9]+" 
                           maxlength="2" 
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                           required>
                </div>

                <div class="form-group">
                    <label for="ip_aff">Indigenous People Affiliation:</label>
                    <input type="text" id="ip_aff" name="ip_aff" placeholder="Enter IP affiliation communities (Ex. ITNEG)" required>
                </div>
                
                <div class="form-group">
                    <label for="listahanan_4ps_status">Are you a recipient of any DSWD Program? </label>
                    <select id="listahanan_4ps_status" name="listahanan_4ps_status" aria-label="Listahanan/4Ps Status" required>
                        <option value="NONE">SELECT DSWD Program</option>
                        <option value="NONE">NONE</option>
                        <option value="LISTAHANAN">LISTAHANAN</option>
                        <option value="4PS">4Ps</option>
                    </select>
                </div>
                <button type="button" class="btn-next" onclick="showSection('personal-details')">Back </button>
                <button type="button" class="btn-next" onclick="showSection('educational-details')">Next</button>
            </div>

            <div id="educational-details" class="form-section">
                <h3>Educational Details</h3>
                <div class="form-group">
                    <label for="campus">Campus:</label>
                    <select id="campus" name="campus" aria-label="Campus" required>
                        <option value="SELECT CAMPUS">SELECT CAMPUS</option>
                        <option value="SANTA MARIA">SANTA MARIA</option>
                        <option value="NARVACAN">NARVACAN</option>
                        <option value="CANDON">CANDON</option>
                        <option value="MAIN CAMPUS">MAIN CAMPUS</option>
                        <option value="TAGUDIN">TAGUDIN</option>
                        <option value="CERVANTES">CERVANTES</option>
                        <option value="SANTIAGO">SANTIAGO</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_num">ID Number:</label>
                    <input type="text" id="id_num" name="id_num" placeholder="Enter your ID number" required>
                </div>
                <div class="form-group">
                    <label for="department">Department:</label>
                    <select id="department" name="department" aria-label="Department" required>
                        <option value="">SELECT DEPARTMENT</option>
                        <!-- Options will be dynamically populated -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="course">Course:</label>
                    <select id="course" name="course" aria-label="Course" required>
                        <option value="">SELECT COURSE</option>
                        <!-- Options will be dynamically populated -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="year">Year:</label>
                    <select id="year" name="year" aria-label="Year" required>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="major">Major:</label>
                    <select id="major" name="major" aria-label="Major" required>
                        <option value="">SELECT MAJOR</option>
                        <!-- Options will be dynamically populated -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="total_units">Total Units Enrolled:</label>
                    <input type="text" 
                           id="total_units" 
                           name="total_units" 
                           placeholder="Enter total units (e.g., 12)" 
                           pattern="[0-9]+" 
                           maxlength="2" 
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                           required>
                </div> 
                <div class="form-group">
                    <label for="academic_year">Academic Year:</label>
                    <input type="text" 
                           id="academic_year" 
                           name="academic_year" 
                           placeholder="YYYY-YYYY (e.g., 2024-2025)" 
                           pattern="[0-9]{4}-[0-9]{4}"
                           maxlength="9" 
                           oninput="formatAcademicYear(this)"
                           required>
                </div>
                <div class="form-group">
                    <label for="semester">Semester:</label>
                    <select id="semester" name="semester" aria-label="Semester" required>
                        <option value="">SELECT SEMESTER</option>
                        <option value="1ST SEMESTER">1ST SEMESTER</option>
                        <option value="2ND SEMESTER">2ND SEMESTER</option>
                        <option value="SUMMER/MIDYEAR">SUMMER/MIDYEAR</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="student_status">Student Status:</label>
                    <select id="student_status" name="student_status" aria-label="Student Status" required>
                        <option value="">SELECT STATUS</option>
                        <option value="NEW STUDENT">NEW STUDENT</option>
                        <option value="OLD STUDENT">OLD STUDENT</option>
                    </select>
                </div>

                <button type="button" class="btn-next" onclick="showSection('family-background')">Back</button>
                <button type="button" class="btn-next" onclick="showSection('scholarship-details')">Next</button>
            </div>

            <div id="scholarship-details" class="form-section">
                <h3>Scholarship Details</h3>

                <div class="form-group">
                    <label for="scholarship_name">Scholarship Name:</label>
                    <select id="scholarship_name" name="scholarship_name" aria-label="Scholarship Name" required>
                        <option value="NONE">NONE</option>
                        <option value="ATI-CAR EDUCATIONAL ASSISTANCE FOR THE YOUTH IN AGRICULTURE (EASY-AGRI)">ATI-CAR EDUCATIONAL ASSISTANCE FOR THE YOUTH IN AGRICULTURE (EASY-AGRI)</option>
                        <option value="ANSWERING THE CRY OF THE P0OR (ANCOP)">ANSWERING THE CRY OF THE P0OR (ANCOP)</option>
                        <option value="ASA PHILIPPINES">ASA PHILIPPINES</option>
                        <option value="BACNOTAN COMPREHENSIVE EDUCATIONAL ASSISTANCE PROGRAM">BACNOTAN COMPREHENSIVE EDUCATIONAL ASSISTANCE PROGRAM </option>
                        <option value="BUREAU OF FISHERIES AND AQUATIC RESOURCES">BUREAU OF FISHERIES AND AQUATIC RESOURCES</option>
                        <option value="CANDON CITY SCHOLARSHIP">CANDON CITY SCHOLARSHIP</option>
                        <option value="CANDONIANS OF SOUTHERN CALIFORNIA">CANDONIANS OF SOUTHERN CALIFORNIA</option>
                        <option value="CARITAS NUEVA SEGOVIA">CARITAS NUEVA SEGOVIA</option>
                        <option value="CERVANTES EDUCATIONAL ASSISTANCE PROGRAM">CERVANTES EDUCATIONAL ASSISTANCE PROGRAM</option>
                        <option value="COLLEGE EDUCATIONAL ASSISTANCE PROGRAM (CEAP)">COLLEGE EDUCATIONAL ASSISTANCE PROGRAM (CEAP)</option>
                        <option value="CONGRESMAN ERIC D. SINGSON SCHOLARSHIP GRANT (CEDSSG)">CONGRESMAN ERIC D. SINGSON SCHOLARSHIP GRANT (CEDSSG)</option>
                        <option value="CHAVIT SINGSON SCHOLARSHIP">CHAVIT SINGSON SCHOLARSHIP</option>
                        <option value="CHED-FULL">CHED-FULL</option>
                        <option value="CHED-HALF">CHED-HALF</option>
                        <option value="CHED-SMART">CHED-SMART</option>
                        <option value="CHED-TULONG AGRI PROGRAM (CHED-TAP)">CHED-TULONG AGRI PROGRAM (CHED-TAP)</option>
                        <option value="CHED-TULONG DUNONG PROGRAM (CHED-TDP)">CHED-TULONG DUNONG PROGRAM (CHED-TDP)</option>
                        <option value="CHED-TERTIARY EDUCATION SUBSIDY (CHED-TES)">CHED-TERTIARY EDUCATION SUBSIDY (CHED-TES)</option>
                        <option value="CITIZEN'S BATTLE AGAINST CORRUPTION (CIBAC) SCHOLARSHIP">CITIZEN'S BATTLE AGAINST CORRUPTION (CIBAC) SCHOLARSHIP</option>
                        <option value="DA-AGRICULTURAL COMPETITIVENESS ENHANCEMENT FUND (DA-ACEF)">DA-AGRICULTURAL COMPETITIVENESS ENHANCEMENT FUND (DA-ACEF)</option>
                        <option value="DEPARTMENT OF HEALTH SCHOLARSHIP">DEPARTMENT OF HEALTH SCHOLARSHIP</option>
                        <option value="DEPARTMENT OF SCIENCE AND TECHNOLOGY (DOST)">DEPARTMENT OF SCIENCE AND TECHNOLOGY (DOST)</option>
                        <option value="DEPARTMENT OF SOCIAL WELFARE AND DEVELOPMENT (DSWD)">DEPARTMENT OF SOCIAL WELFARE AND DEVELOPMENT (DSWD)</option>
                        <option value="EDUCATIONAL ASSISTANCE AND SCHOLARSHIP EMERGENCIES (EASE-AGRI)">EDUCATIONAL ASSISTANCE AND SCHOLARSHIP EMERGENCIES (EASE-AGRI)</option>
                        <option value="EVA AND EDUARDSON FOUNDATION INC.">EVA AND EDUARDSON FOUNDATION INC.</option>
                        <option value="ILOCOS SUR AGRICULTURE COLLEGE BATCH 1971 (ISAC BATCH'71)">ILOCOS SUR AGRICULTURE COLLEGE BATCH 1971 (ISAC BATCH'71)</option>
                        <option value="ILOCOS SUR EDUCATIONAL ASSISTANCE AND SCHOLARSHIP PROGRAM (ISEASP)">ILOCOS SUR EDUCATIONAL ASSISTANCE AND SCHOLARSHIP PROGRAM (ISEASP)</option>
                        <option value="ILOCOS SUR ELECTRIC COOPERATIVE (ISECO)">ILOCOS SUR ELECTRIC COOPERATIVE (ISECO)</option>
                        <option value="LA UNION EDUCATIONAL ASSISTANCE">LA UNION EDUCATIONAL ASSISTANCE</option>
                        <option value="LEPANTO EDUCATIONAL ASSISTANCE PROGRAM (LEAP)">LEPANTO EDUCATIONAL ASSISTANCE PROGRAM (LEAP)</option>
                        <option value="MANILA TEACHERS MUTUAL AIDE SYSTEM (MTMAS)">MANILA TEACHERS MUTUAL AIDE SYSTEM (MTMAS)</option>
                        <option value="MSJAB SCHOLARSHIP">MSJAB SCHOLARSHIP</option>
                        <option value="MUNICIPAL SCHOLARS">MUNICIPAL SCHOLARS</option>
                        <option value="NATIONAL COMMISSION IN INDIGENOUS PEOPLE (NCIP)">NATIONAL COMMISSION IN INDIGENOUS PEOPLE (NCIP)</option>
                        <option value="NATIONAL TOBACCO ADMINISTRATION (NTA)">NATIONAL TOBACCO ADMINISTRATION (NTA)</option>
                        <option value="ISPSC TULONG DUNONG PROGRAM">ISPSC TULONG DUNONG PROGRAM</option>
                        <option value="ONE TIME EDUC ATIONAL ASSISTANCE PROGRAM (OTAP)">ONE TIME EDUCATIONAL ASSISTANCE PROGRAM (OTAP)</option>
                        <option value="OVERSEAS WORKERS WELFARE ADMINISTRATION (OWWA)">OVERSEAS WORKERS WELFARE ADMINISTRATION (OWWA)</option>
                        <option value="PHILIPPINE CHARITY SWEEPSTAKE OFFICE STUDENT PROGRAM">PHILIPPINE CHARITY SWEEPSTAKE OFFICE STUDENT PROGRAM</option>
                        <option value="PRIVATE/INDIVIDUAL SCHOLARSHIP">PRIVATE/INDIVIDUAL SCHOLARSHIP</option>
                        <option value="PROVINCIAL GOVERNMENT OF LA UNION (PGLU)">PROVINCIAL GOVERNMENT OF LA UNION (PGLU)</option>
                        <option value="RANIAG SCHOLARSHIP PROGRAM">RANIAG SCHOLARSHIP PROGRAM</option>
                        <option value="SCHOLARSHIP PROGRAM FOR COCONUT FARMERS AND FAMILIES (CoScho)">SCHOLARSHIP PROGRAM FOR COCONUT FARMERS AND FAMILIES (CoScho)</option>
                        <option value="SONS AND DAUGHTERS OF NAGBUKEL">SONS AND DAUGHTERS OF NAGBUKEL</option>
                        <option value="TECHNICAL EDUCATIONAL SKILL DEVELOPMENT AUTHORITY (TESDA)">TECHNICAL EDUCATIONAL SKILL DEVELOPMENT AUTHORITY (TESDA)</option>
                        <option value="UNIVERSAL LEAF PHILIPPINES INC. (ULPI)">UNIVERSAL LEAF PHILIPPINES INC. (ULPI)</option>
                        <option value="U-GO SCHOLARSHIP">U-GO SCHOLARSHIP</option>
                        <option value="VIGAN CITY SCHOLARSHIP">VIGAN CITY SCHOLARSHIP</option>
                        <option value="OTHERS">OTHERS</option>
                    </select>
                </div>
                <div class="form-group" id="other_scholarship_group" style="display: none;">
                    <label for="other_scholarship">Other Scholarship Name:</label>
                    <input type="text" 
                        id="other_scholarship" 
                        name="other_scholarship" 
                        placeholder="Enter other scholarship name"
                        style="text-transform: uppercase;"
                        class="form-control">
                </div>
                <div class="form-group" id="amount_group">
                    <label for="amount">Amount:</label>
                    <div style="position: relative;">
                        <span style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%);">₱</span>
                        <input type="text" id="amount_display" placeholder="Enter amount per semester (if you choose NONE then please put Zero [0])" style="padding-left: 20px;">
                        <input type="hidden" id="amount" name="amount">
                    </div>
                </div>
                
                <button type="button" class="btn-next" onclick="showSection('educational-details')">Back</button>
                <input type="submit" value="Submit" onclick="return validateForm(event)">
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <script>
        const data = {
            "MAIN CAMPUS": {
                departments: {
                    "College of Teacher Education": {
                        courses: [
                            "Bachelor of Secondary Education",
                            "Bachelor of Elementary Education",
                            "Bachelor of Culture and the Arts Education",
                            "Bachelor of Physical Education"
                        ],
                        majors: ["Science", "English", "Mathematics", "Filipino", "Social Studies", "None"]
                    },
                    "College of Arts and Sciences": {
                        courses: [
                            "Bachelor of Arts in English Language",
                            "Bachelor of Arts in Political Science",
                            "Bachelor of Science in Computer Science",
                            "Bachelor of Science in Midwifery",
                            "Bachelor of Science in Nursing"
                        ],
                        majors: ["None"]
                    },
                    "College of Business Education": {
                        courses: [
                            "Bachelor of Science in Business Administration",
                            "Bachelor of Science in Office Administration"
                        ],
                        majors: ["Financial Management", "Human Resources Development Management", "None"]
                    },
                    "School of Criminal Justice Education": {
                        courses: ["Bachelor of Science in Criminology"],
                        majors: ["None"]
                    }
                }
            },
            "SANTA MARIA": {
                departments: {
                    "College of Agriculture, Forestry, Engineering and Development Communication": {
                        courses: [
                            "Bachelor of Science in Agriculture",
                            "Bachelor of Science in Agricultural and Biosystems Engineering",
                            "Bachelor of Science in Forestry",
                            "Bachelor of Science in Agroforestry",
                            "Bachelor in Agricultural Technology",
                            "Bachelor of Science in Development Communication"
                        ],
                        majors: [
                            "Agronomy",
                            "Animal Husbandry",
                            "Horticulture",
                            "Post Harvest Technology",
                            "Agribusiness Management & Entrepreneurship",
                            "None"
                        ]
                    },
                    "College of Teacher Education": {
                        courses: [
                            "Bachelor of Elementary Education",
                            "Bachelor of Secondary Education",
                            "Bachelor of Technology and Livelihood Education"
                        ],
                        majors: ["Science", "English", "Mathematics", "Filipino", "Social Studies", "Home Economics", "Agro-Fishery Arts", "None"]
                    },
                    "College of Computing Studies": {
                        courses: [
                            "Bachelor of Science in Information Technology",
                            "Bachelor of Science in Information Systems",
                        ],
                        majors: ["Web Development and Mobile Track", "Networking and Cybersecurity Track", "Business and Data Analytics", "None"]
                    },
                    "College of Business, Management and Entrepreneurship": {
                        courses: [
                            "Bachelor of Science in Hospitality Management"
                        ],
                        majors: ["None"]
                    }
                }
            },
            "NARVACAN": {
                departments: {
                    "Fisheries Department": {
                        courses: [
                            "Bachelor of Science in Fisheries"
                        ],
                        majors: [
                            "none"
                        ]
                    },
                    "Department of Teacher Education": {
                        courses: [
                            "Bachelor of Technology and Livelihood Education",
                            "Bachelor of Physical Education",
                            "Bachelor of Technical Vocational Teacher Education"
                        ],
                        majors: [
                            "Agri-fishery Arts", "Home Economics", "Fish Processing", "Aquaculture", "None"
                        ]
                    }
                }
            },
            "SANTIAGO": {
                departments: {
                    "Industrial Technology Department": {
                        courses: [
                            "Bachelor of Science in Industrial Technology",
                            "Bachelor of Science in Mechatronics Technology"
                        ],
                        majors: [
                            "Automotive Technology",
                            "Electrical Technology",
                            "Electronics Technology",
                            "Food Technology",
                            "Apparel Technology",
                            "None"
                        ]
                    },
                    "Teacher Education Department": {
                        courses: [
                            "Bachelor in Technical-Vocational Teacher Education"
                        ],
                        majors: [
                            "Automotive Technology",
                            "Electrical Technology",
                            "Electronics Technology",
                            "Food and Service Management",
                            "Garments, Fashion, and Design"
                        ]
                    }
                }
            },
            "TAGUDIN": {
                departments: {
                    "College of Arts and Sciences": {
                        courses: [
                            "Bachelor of Science in Mathematics",
                            "Bachelor of Arts in Psychology",
                            "Bachelor of Arts in Social Science",
                            "Bachelor of Arts in English Language",
                            "Bachelor of Science in Information Technology",
                            "Bachelor of Science in Public Administration"
                        ],
                        majors: ["Web Development and Mobile Track", "None"]
                    },
                    "College of Business, Management and Entrepreneurship": {
                        courses: ["Bachelor of Science in Business Administration", "Bachelor of Science in Entrepreneurship"],
                        majors: ["Human Resource Development Management", "Marketing Management", "Financial Management", "None"]
                    },
                    "College of Teacher Education": {
                        courses: ["Bachelor of Secondary Education", "Bachelor of Elementary Education", "Bachelor of Physical Education"],
                        majors: ["Science", "English", "Mathematics", "Filipino", "Social Studies", "None"]
                    }
                }
            },
            "CANDON": {
                departments: {
                    "College of Business and Hospitality Management": {
                        courses: [
                            "Bachelor of Science in Hospitality Management",
                            "Bachelor of Science in Tourism Management"
                        ],
                        majors: ["None"]
                    },
                    "College of Computing Studies": {
                        courses: ["Bachelor of Science in Information Technology"],
                        majors: ["None"]
                    },
                    "College of Teacher Education": {
                        courses: ["Bachelor of Secondary Education"],
                        majors: ["Filipino"]
                    },
                }
            },
            "CERVANTES": {
                departments: {
                    "College of Arts and Science": {
                        courses: [" Bachelor of Science in Information Technology", "Bachelor of Science in Criminology"],
                        majors: ["Web and Mobile Application", "None"]
                    },
                    "College of Teacher Education": {
                        courses: [
                            "Bachelor of Secondary Education",
                            "Bachelor of Elementary Education",
                            "Bachelor of Technology and Livelihood Education",
                            "Bachelor of Technical-Vocational Teacher Education"
                        ],
                        majors: ["None", "Mathematics", "English", "Science", "Technology and Livelihood Education", "Agri-Fishery Arts", "Home Economics", "Food Service Management", "Agricultural Crops Production"]
                    }
                }
            }
        };

        const campusSelect = document.getElementById('campus');
        const departmentSelect = document.getElementById('department');
        const courseSelect = document.getElementById('course');
        const majorSelect = document.getElementById('major');
        const amountGroup = document.getElementById('amount_group');

        campusSelect.addEventListener('change', function () {
            const selectedCampus = this.value;
            departmentSelect.innerHTML = '<option value="">SELECT DEPARTMENT</option>';
            courseSelect.innerHTML = '<option value="">SELECT COURSE</option>';
            majorSelect.innerHTML = '<option value="">SELECT MAJOR</option>';

            if (data[selectedCampus]) {
                Object.keys(data[selectedCampus].departments).forEach(department => {
                    const option = document.createElement('option');
                    option.value = department;
                    option.textContent = department;
                    departmentSelect.appendChild(option);
                });
            }
        });

        departmentSelect.addEventListener('change', function () {
            const selectedCampus = campusSelect.value;
            const selectedDepartment = this.value;
            courseSelect.innerHTML = '<option value="">SELECT COURSE</option>';
            majorSelect.innerHTML = '<option value="">SELECT MAJOR</option>';

            if (data[selectedCampus] && data[selectedCampus].departments[selectedDepartment]) {
                const { courses, majors } = data[selectedCampus].departments[selectedDepartment];
                courses.forEach(course => {
                    const option = document.createElement('option');
                    option.value = course;
                    option.textContent = course;
                    courseSelect.appendChild(option);
                });
                majors.forEach(major => {
                    const option = document.createElement('option');
                    option.value = major;
                    option.textContent = major;
                    majorSelect.appendChild(option);
                });
            }
        });

        // Initialize form state
        let currentSection = 'personal-details';
        
        function showAlert(message, type) {
            if (type === 'success') {
                alertify.success(message);
            } else if (type === 'error') {
                alertify.error(message);
            }
        }

        function validateSection(sectionId) {
            const section = document.getElementById(sectionId);
            const inputs = section.querySelectorAll('input[required], select[required]');
            let isValid = true;
            let firstInvalidInput = null;

            inputs.forEach(input => {
                // Skip validation for hidden fields
                if (input.style.display === 'none' || 
                    (input.id === 'other_scholarship' && 
                     document.getElementById('other_scholarship_group').style.display === 'none')) {
                    return;
                }

                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('is-invalid');
                    if (!firstInvalidInput) {
                        firstInvalidInput = input;
                    }
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                showAlert('Please fill out all required fields before proceeding.', 'error');
                if (firstInvalidInput) {
                    firstInvalidInput.focus();
                }
            }

            return isValid;
        }

        function showSection(sectionId) {
            // Validate current section before proceeding
            if (sectionId !== currentSection && !validateSection(currentSection)) {
                return;
            }

            // Update current section
            currentSection = sectionId;

            // Hide all sections
            document.querySelectorAll('.form-section').forEach(section => {
                section.style.display = 'none';
            });

            // Show the requested section
            const targetSection = document.getElementById(sectionId);
            targetSection.style.display = 'block';

            // Update progress indicator
            document.querySelectorAll('.progress-step').forEach(step => {
                step.classList.remove('active');
                if (step.dataset.step === sectionId) {
                    step.classList.add('active');
                }
            });
        }

        function validateForm(event) {
            event.preventDefault();
            
            // Validate all sections
            const sections = ['personal-details', 'family-background', 'educational-details', 'scholarship-details'];
            let isValid = true;

            for (const sectionId of sections) {
                if (!validateSection(sectionId)) {
                    isValid = false;
                    showSection(sectionId);
                    break;
                }
            }

            if (isValid) {
                // Add loading state
                const submitButton = document.querySelector('input[type="submit"]');
                const originalText = submitButton.value;
                submitButton.value = 'Submitting...';
                submitButton.disabled = true;

                // Submit the form
                document.getElementById('scholarship-form').submit();
            }
        }

        // Format number inputs
        function formatNumberWithCommas(value) {
            return value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        // Format academic year
        function formatAcademicYear(input) {
            let value = input.value.replace(/[^0-9-]/g, '');
            
            if (value.length >= 4 && !value.includes('-')) {
                value = value.slice(0, 4) + '-' + value.slice(4);
            }
            
            if (value.length === 9) {
                const firstYear = parseInt(value.slice(0, 4));
                const secondYear = parseInt(value.slice(5, 9));
                
                if (secondYear !== firstYear + 1) {
                    value = firstYear + '-' + (firstYear + 1);
                }
            }
            
            input.value = value;
        }

        // Handle scholarship selection
        function handleScholarshipChange() {
            const scholarshipSelect = document.getElementById('scholarship_name');
            const otherScholarshipGroup = document.getElementById('other_scholarship_group');
            
            if (scholarshipSelect.value === 'OTHERS') {
                otherScholarshipGroup.style.display = 'block';
                document.getElementById('other_scholarship').setAttribute('required', 'required');
            } else {
                otherScholarshipGroup.style.display = 'none';
                document.getElementById('other_scholarship').removeAttribute('required');
                document.getElementById('other_scholarship').value = '';
            }
        }

        // Initialize form
        document.addEventListener('DOMContentLoaded', function() {
            // Show initial section
            showSection('personal-details');

            // Set up event listeners
            document.getElementById('scholarship_name').addEventListener('change', handleScholarshipChange);
            
            // Set up number formatting
            document.getElementById('family_income_display').addEventListener('input', function() {
                const displayField = this;
                const hiddenField = document.getElementById('family_income');
                let numericValue = displayField.value.replace(/[^\d]/g, '');
                displayField.value = formatNumberWithCommas(numericValue);
                hiddenField.value = numericValue;
            });

            document.getElementById('amount_display').addEventListener('input', function() {
                const displayField = this;
                const hiddenField = document.getElementById('amount');
                let numericValue = displayField.value.replace(/[^\d]/g, '');
                displayField.value = formatNumberWithCommas(numericValue);
                hiddenField.value = numericValue;
            });

            // Initialize scholarship selection
            handleScholarshipChange();
        });
    </script>
</body>

</html>