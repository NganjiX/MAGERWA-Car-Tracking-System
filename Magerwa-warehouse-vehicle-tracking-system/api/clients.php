<?php
// api/clients.php - Complete with GET single client for editing
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
    // CREATE - Register Client
    $data = json_decode(file_get_contents('php://input'), true);
    
    try {
        $query = "INSERT INTO clients (names, national_id, telephone, address) 
                  VALUES (:names, :national_id, :telephone, :address)";
        $stmt = $db->prepare($query);
        
        $stmt->execute([
            ':names' => $data['names'],
            ':national_id' => $data['national_id'],
            ':telephone' => $data['telephone'],
            ':address' => $data['address']
        ]);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Client registered successfully',
            'id' => $db->lastInsertId()
        ]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Registration failed: ' . $e->getMessage()]);
    }
} elseif ($method === 'PUT' && $id) {
    // UPDATE - Update Client
    $data = json_decode(file_get_contents('php://input'), true);
    
    try {
        $query = "UPDATE clients SET 
                  names = :names, 
                  national_id = :national_id, 
                  telephone = :telephone, 
                  address = :address 
                  WHERE id = :id";
        $stmt = $db->prepare($query);
        
        $stmt->execute([
            ':names' => $data['names'],
            ':national_id' => $data['national_id'],
            ':telephone' => $data['telephone'],
            ':address' => $data['address'],
            ':id' => $id
        ]);
        
        echo json_encode(['success' => true, 'message' => 'Client updated successfully']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Update failed: ' . $e->getMessage()]);
    }
} elseif ($method === 'DELETE' && $id) {
    // DELETE - Delete Client
    try {
        // Check if client has linked vehicles
        $checkQuery = "SELECT COUNT(*) FROM vehicle_links WHERE client_id = :id";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->execute([':id' => $id]);
        $count = $checkStmt->fetchColumn();
        
        if ($count > 0) {
            echo json_encode([
                'success' => false, 
                'message' => 'Cannot delete client with linked vehicles. Unlink vehicles first.'
            ]);
            exit();
        }
        
        $query = "DELETE FROM clients WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->execute([':id' => $id]);
        
        echo json_encode(['success' => true, 'message' => 'Client deleted successfully']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Delete failed: ' . $e->getMessage()]);
    }
} elseif ($method === 'GET' && $id) {
    // GET single client by ID
    try {
        $query = "SELECT * FROM clients WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->execute([':id' => $id]);
        $client = $stmt->fetch();
        
        if ($client) {
            echo json_encode(['success' => true, 'data' => $client]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Client not found']);
        }
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to fetch client: ' . $e->getMessage()]);
    }
} elseif ($method === 'GET') {
    // GET all clients with pagination
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
    $offset = ($page - 1) * $limit;
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    
    try {
        $searchCondition = '';
        $params = [];
        if ($search) {
            $searchCondition = " WHERE names LIKE :search OR national_id LIKE :search OR telephone LIKE :search";
            $params[':search'] = "%$search%";
        }
        
        $countQuery = "SELECT COUNT(*) as total FROM clients" . $searchCondition;
        $countStmt = $db->prepare($countQuery);
        $countStmt->execute($params);
        $total = $countStmt->fetch()['total'];
        
        $query = "SELECT * FROM clients" . $searchCondition . " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        foreach ($params as $key => $value) {
            $stmt->bindParam($key, $value);
        }
        $stmt->execute();
        
        $clients = $stmt->fetchAll();
        
        echo json_encode([
            'success' => true,
            'data' => $clients,
            'pagination' => [
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'total_pages' => ceil($total / $limit)
            ]
        ]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to fetch clients: ' . $e->getMessage()]);
    }
}
?>