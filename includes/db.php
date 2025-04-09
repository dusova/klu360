
<?php

require_once 'config.php';

function connectDatabase() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        if (DEBUG_MODE) {
            die("Veritabanı bağlantı hatası: " . $conn->connect_error);
        } else {
            die("Veritabanı bağlantısı kurulamadı. Lütfen daha sonra tekrar deneyin.");
        }
    }
    
    $conn->set_charset("utf8mb4");
    
    return $conn;
}

$conn = connectDatabase();


function closeDatabase($conn) {
    if ($conn) {
        $conn->close();
    }
}


function executeQuery($conn, $query, $params = []) {
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        if (DEBUG_MODE) {
            die("Sorgu hazırlama hatası: " . $conn->error . " - Sorgu: " . $query);
        } else {
            die("Sunucu hatası oluştu. Lütfen daha sonra tekrar deneyin.");
        }
    }
    
    if (!empty($params)) {
        $types = '';
        $bindParams = [];
        
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_float($param)) {
                $types .= 'd';
            } elseif (is_string($param)) {
                $types .= 's';
            } else {
                $types .= 'b';
            }
            
            $bindParams[] = $param;
        }
        
        $bindValues = array($stmt, $types);
        
        for ($i = 0; $i < count($bindParams); $i++) {
            $bindValues[] = &$bindParams[$i];
        }
        
        call_user_func_array('mysqli_stmt_bind_param', $bindValues);
    }
    
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if (!$result) {
        $affectedRows = $stmt->affected_rows;
        $insertId = $stmt->insert_id;
        $stmt->close();
        
        return [
            'affected_rows' => $affectedRows,
            'insert_id' => $insertId
        ];
    }
    
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    
    $stmt->close();
    
    return $rows;
}
?>
