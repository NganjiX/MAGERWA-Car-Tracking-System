<?php
// api/vehicles.php - Complete with GET single vehicle for editing
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';
require_once '../includes/auth.php';

requireLogin();

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($method === 'POST') {
    // CREATE - Register Vehicle
    $data = json_decode(file_get_contents('php://input'), true);
    
    try {
        $query = "INSERT INTO vehicles (chassis_number, manufacture_company, manufacture_year, price, model_name) 
                  VALUES (:chassis_number, :manufacture_company, :manufacture_year, :price, :model_name)";
        $stmt = $db->prepare($query);
        
        $stmt->execute([
            ':chassis_number' => $data['chassis_number'],
            ':manufacture_company' => $data['manufacture_company'],
            ':manufacture_year' => $data['manufacture_year'],
            ':price' => $data['price'],
            ':model_name' => $data['model_name']
        ]);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Vehicle registered successfully',
            'id' => $db->lastInsertId()
        ]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Registration failed: ' . $e->getMessage()]);
    }
} elseif ($method === 'PUT' && $id) {
    // UPDATE - Update Vehicle
    $data = json_decode(file_get_contents('php://input'), true);
    
    try {
        $query = "UPDATE vehicles SET 
                  chassis_number = :chassis_number,
                  manufacture_company = :manufacture_company,
                  manufacture_year = :manufacture_year,
                  price = :price,
                  model_name = :model_name
                  WHERE id = :id";
        $stmt = $db->prepare($query);
        
        $stmt->execute([
            ':chassis_number' => $data['chassis_number'],
            ':manufacture_company' => $data['manufacture_company'],
            ':manufacture_year' => $data['manufacture_year'],
            ':price' => $data['price'],
            ':model_name' => $data['model_name'],
            ':id' => $id
        ]);
        
        echo json_encode(['success' => true, 'message' => 'Vehicle updated successfully']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Update failed: ' . $e->getMessage()]);
    }
} elseif ($method === 'DELETE' && $id) {
    // DELETE - Delete Vehicle
    try {
        // Check if vehicle is linked
        $checkQuery = "SELECT COUNT(*) FROM vehicle_links WHERE vehicle_id = :id";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->execute([':id' => $id]);
        $count = $checkStmt->fetchColumn();
        
        if ($count > 0) {
            echo json_encode([
                'success' => false, 
                'message' => 'Cannot delete vehicle that is linked to a client. Unlink first.'
            ]);
            exit();
        }
        
        $query = "DELETE FROM vehicles WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->execute([':id' => $id]);
        
        echo json_encode(['success' => true, 'message' => 'Vehicle deleted successfully']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Delete failed: ' . $e->getMessage()]);
    }
} elseif ($method === 'GET' && $id) {
    // GET single vehicle by ID
    try {
        $query = "SELECT * FROM vehicles WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->execute([':id' => $id]);
        $vehicle = $stmt->fetch();
        
        if ($vehicle) {
            echo json_encode(['success' => true, 'data' => $vehicle]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Vehicle not found']);
        }
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to fetch vehicle: ' . $e->getMessage()]);
    }
} elseif ($method === 'GET') {
    // GET all vehicles with pagination
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
    $offset = ($page - 1) * $limit;
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    
    try {
        $searchCondition = '';
        $params = [];
        if ($search) {
            $searchCondition = " WHERE chassis_number LIKE :search OR manufacture_company LIKE :search OR model_name LIKE :search";
            $params[':search'] = "%$search%";
        }
        
        $countQuery = "SELECT COUNT(*) as total FROM vehicles" . $searchCondition;
        $countStmt = $db->prepare($countQuery);
        $countStmt->execute($params);
        $total = $countStmt->fetch()['total'];
        
        $query = "SELECT * FROM vehicles" . $searchCondition . " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        foreach ($params as $key => $value) {
            $stmt->bindParam($key, $value);
        }
        $stmt->execute();
        
        $vehicles = $stmt->fetchAll();
        
        echo json_encode([
            'success' => true,
            'data' => $vehicles,
            'pagination' => [
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'total_pages' => ceil($total / $limit)
            ]
        ]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to fetch vehicles: ' . $e->getMessage()]);
    }
}
?>