<?php
// api/links.php - COMPLETE FIXED
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
    // CREATE - Link Vehicle to Client
    $data = json_decode(file_get_contents('php://input'), true);
    
    try {
        $db->beginTransaction();
        
        // Check if vehicle is already linked
        $checkQuery = "SELECT id FROM vehicle_links WHERE vehicle_id = :vehicle_id";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->execute([':vehicle_id' => $data['vehicle_id']]);
        
        if ($checkStmt->rowCount() > 0) {
            echo json_encode(['success' => false, 'message' => 'Vehicle already linked to a client']);
            $db->rollBack();
            exit();
        }
        
        $plateNumber = $data['plate_number'] ?? 'RWA-' . strtoupper(uniqid());
        
        $query = "INSERT INTO vehicle_links (vehicle_id, client_id, plate_number) 
                  VALUES (:vehicle_id, :client_id, :plate_number)";
        $stmt = $db->prepare($query);
        
        $stmt->execute([
            ':vehicle_id' => $data['vehicle_id'],
            ':client_id' => $data['client_id'],
            ':plate_number' => $plateNumber
        ]);
        
        $db->commit();
        
        echo json_encode([
            'success' => true, 
            'message' => 'Vehicle linked successfully', 
            'plate_number' => $plateNumber,
            'id' => $db->lastInsertId()
        ]);
    } catch(PDOException $e) {
        $db->rollBack();
        echo json_encode(['success' => false, 'message' => 'Linkage failed: ' . $e->getMessage()]);
    }
} elseif ($method === 'PUT' && $id) {
    // UPDATE - Update Plate Number
    $data = json_decode(file_get_contents('php://input'), true);
    
    try {
        $query = "UPDATE vehicle_links SET plate_number = :plate_number WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':plate_number' => $data['plate_number'],
            ':id' => $id
        ]);
        
        echo json_encode(['success' => true, 'message' => 'Plate number updated successfully']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Update failed: ' . $e->getMessage()]);
    }
} elseif ($method === 'DELETE' && $id) {
    // DELETE - Unlink Vehicle
    try {
        $query = "DELETE FROM vehicle_links WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->execute([':id' => $id]);
        
        echo json_encode(['success' => true, 'message' => 'Vehicle unlinked successfully']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Unlink failed: ' . $e->getMessage()]);
    }
} elseif ($method === 'GET' && isset($_GET['available'])) {
    // Get available vehicles (not linked)
    try {
        $query = "SELECT * FROM vehicles 
                  WHERE id NOT IN (SELECT vehicle_id FROM vehicle_links)";
        $stmt = $db->query($query);
        $vehicles = $stmt->fetchAll();
        
        echo json_encode(['success' => true, 'data' => $vehicles]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to fetch available vehicles: ' . $e->getMessage()]);
    }
} elseif ($method === 'GET' && isset($_GET['id'])) {
    // Get single link by ID
    try {
        $query = "SELECT * FROM vehicle_links WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->execute([':id' => $id]);
        $link = $stmt->fetch();
        
        echo json_encode(['success' => true, 'data' => $link]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to fetch link: ' . $e->getMessage()]);
    }
} elseif ($method === 'GET') {
    // READ - Get Linked Vehicles with pagination
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
    $offset = ($page - 1) * $limit;
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    
    try {
        $searchCondition = '';
        $params = [];
        if ($search) {
            $searchCondition = " WHERE vl.plate_number LIKE :search OR v.model_name LIKE :search OR c.names LIKE :search";
            $params[':search'] = "%$search%";
        }
        
        $countQuery = "SELECT COUNT(*) as total FROM vehicle_links vl
                       INNER JOIN vehicles v ON vl.vehicle_id = v.id
                       INNER JOIN clients c ON vl.client_id = c.id" . $searchCondition;
        $countStmt = $db->prepare($countQuery);
        $countStmt->execute($params);
        $total = $countStmt->fetch()['total'];
        
        $query = "SELECT 
                    vl.id as link_id,
                    vl.plate_number,
                    vl.linked_at,
                    v.*,
                    c.*
                  FROM vehicle_links vl
                  INNER JOIN vehicles v ON vl.vehicle_id = v.id
                  INNER JOIN clients c ON vl.client_id = c.id" . $searchCondition . "
                  ORDER BY vl.linked_at DESC
                  LIMIT :limit OFFSET :offset";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        foreach ($params as $key => $value) {
            $stmt->bindParam($key, $value);
        }
        $stmt->execute();
        
        $links = $stmt->fetchAll();
        
        echo json_encode([
            'success' => true,
            'data' => $links,
            'pagination' => [
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'total_pages' => ceil($total / $limit)
            ]
        ]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to fetch links: ' . $e->getMessage()]);
    }
}
?>