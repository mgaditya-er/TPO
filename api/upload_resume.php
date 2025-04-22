<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

session_start();
include 'db.php'; // MySQL DB connection

// POST check
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {

    $enrollment_no = $_POST['enrollment_no'] ?? null;
    $title = $_POST['title'] ?? 'Resume';

    if (!$enrollment_no) {
        echo json_encode(["success" => false, "message" => "Enrollment number is required."]);
        exit();
    }

    // Validate enrollment number
    try {
        $check_stmt = $pdo->prepare("SELECT enrollment_no FROM student_profile WHERE enrollment_no = ?");
        $check_stmt->execute([$enrollment_no]);
        if ($check_stmt->rowCount() == 0) {
            echo json_encode(["success" => false, "message" => "Enrollment number not found in student_profile."]);
            exit();
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
        exit();
    }

    // File Upload Logic
    function uploadFile($file, $upload_dir, $enrollment_no) {
        $allowed_extensions = ['pdf', 'jpg', 'png'];
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($file_ext, $allowed_extensions)) {
            echo json_encode(["success" => false, "message" => "Only PDF, JPG, and PNG files are allowed."]);
            exit();
        }

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = $enrollment_no . '.' . $file_ext;
        $file_path = $upload_dir . $file_name;

        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            return $file_path;
        } else {
            echo json_encode(["success" => false, "message" => "File upload failed."]);
            exit();
        }
    }

    // 1. Upload file locally
    $upload_dir = 'uploads/';
    $file_path = uploadFile($_FILES['file'], $upload_dir, $enrollment_no);
    $file_ext = pathinfo($file_path, PATHINFO_EXTENSION);
    $file_url = "http://localhost/api/uploads/" . $enrollment_no . '.' . $file_ext;

    // 2. Save in MySQL
    try {
        $stmt = $pdo->prepare("INSERT INTO student_uploads (enrollment_no, title, file_type, file_path)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
            title = VALUES(title),
            file_type = VALUES(file_type),
            file_path = VALUES(file_path)");
        $stmt->execute([$enrollment_no, $title, $file_ext, $file_path]);

        echo json_encode(["success" => true, "message" => "File uploaded and saved successfully.", "file_url" => $file_url]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Failed to save file in DB: " . $e->getMessage()]);
        exit();
    }

} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>
