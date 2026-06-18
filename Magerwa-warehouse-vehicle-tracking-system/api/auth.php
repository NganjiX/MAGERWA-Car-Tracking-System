<?php
// api/auth.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';
require_once '../includes/auth.php';

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if ($action === 'signup') {
        // Admin Signup
        try {
            $query = "INSERT INTO admins (names, email, phone, national_id, password) 
                      VALUES (:names, :email, :phone, :national_id, :password)";
            $stmt = $db->prepare($query);
            
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            
            $stmt->execute([
                ':names' => $data['names'],
                ':email' => $data['email'],
                ':phone' => $data['phone'],
                ':national_id' => $data['national_id'],
                ':password' => $hashedPassword
            ]);
            
            echo json_encode(['success' => true, 'message' => 'Admin registered successfully']);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Registration failed: ' . $e->getMessage()]);
        }
    } elseif ($action === 'login') {
        // Admin Login
        try {
            $query = "SELECT * FROM admins WHERE email = :email";
            $stmt = $db->prepare($query);
            $stmt->execute([':email' => $data['email']]);
            
            $admin = $stmt->fetch();
            
            if ($admin && password_verify($data['password'], $admin['password'])) {
                login($admin['id'], $admin['email'], $admin['names']);
                echo json_encode(['success' => true, 'message' => 'Login successful']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
            }
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Login failed: ' . $e->getMessage()]);
        }
    }
}
?>