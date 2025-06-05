<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

// Validasi input
if (!isset($data['name'], $data['email'], $data['subject'], $data['message'])) {
    echo json_encode(['success' => false, 'message' => 'Semua field harus diisi']);
    exit;
}

$name = htmlspecialchars($data['name']);
$email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
$subject = htmlspecialchars($data['subject']);
$message = htmlspecialchars($data['message']);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Email tidak valid']);
    exit;
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'mariyonolepto5@gmail.com'; // GANTI
    $mail->Password   = 'erik';          // GANTI
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('mariyonolepto5@gmail.com', 'Form Website');
    $mail->addAddress('mariyonolepto5@gmail.com');
    $mail->addReplyTo($email, $name);

    $mail->Subject = $subject;
    $mail->Body    = "Nama: $name\nEmail: $email\n\nPesan:\n$message";

    $mail->send();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => "Mailer Error: {$mail->ErrorInfo}"]);
}
