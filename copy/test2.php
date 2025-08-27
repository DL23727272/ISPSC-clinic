<?php
require_once __DIR__ . '/tcpdf/tcpdf.php';   // TCPDF core
require_once __DIR__ . '/vendor/autoload.php'; // Composer autoload

use setasign\Fpdi\Tcpdf\Fpdi;

// DB Connection
$mysqli = new mysqli("localhost", "root", "", "isps_clinica");
if ($mysqli->connect_errno) {
    die("Failed to connect: " . $mysqli->connect_error);
}

// Fetch one student for demo
$student_id = "A21-00001";
$query = $mysqli->prepare("SELECT s.*, h.* 
    FROM students s
    LEFT JOIN student_health_info h ON s.student_id = h.student_id
    WHERE s.student_id = ?");
$query->bind_param("s", $student_id);
$query->execute();
$data = $query->get_result()->fetch_assoc();

// ✅ Use the Composer FPDI class
$pdf = new Fpdi('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Import template
$pageCount = $pdf->setSourceFile(__DIR__ . "/health_form_template.pdf");
$templateId = $pdf->importPage(1);

$pdf->AddPage();
$pdf->useTemplate($templateId, 0, 0, 210); // Fit to A4 width

$pdf->SetFont('dejavusans', '', 9);

// ---------------- STUDENT INFO ----------------
$pdf->SetXY(30, 45);
$pdf->Cell(100, 5, trim($data['last_name'].", ".$data['first_name']." ".$data['middle_name']." ".$data['suffix'], " ,"), 0, 1);

$pdf->SetXY(150, 45);
$pdf->Cell(50, 5, $data['student_id'], 0, 1);

$pdf->SetXY(30, 52);
$pdf->Cell(80, 5, $data['campus'], 0, 1);

$pdf->SetXY(120, 52);
$pdf->Cell(80, 5, $data['department'], 0, 1);

$pdf->SetXY(30, 59);
$pdf->Cell(80, 5, $data['course'] . (!empty($data['year']) ? " / " . $data['year'] : ""), 0, 1);

$pdf->SetXY(120, 59);
$pdf->Cell(80, 5, $data['birthdate'], 0, 1);

$pdf->SetXY(30, 66);
$pdf->Cell(80, 5, $data['sex'], 0, 1);

$pdf->SetXY(120, 66);
$pdf->Cell(80, 5, $data['age'], 0, 1);

// ---------------- HEALTH INFO ----------------
$pdf->SetXY(30, 90);
$pdf->Cell(160, 5, "Blood Type: " . ($data['blood_type'] ?? ''), 0, 1);

$pdf->SetXY(30, 96);
$pdf->Cell(160, 5, "Allergies: " . ($data['allergy_alert'] ?? ''), 0, 1);

// Output PDF
$pdf->Output("student_health_" . $student_id . ".pdf", "I");
