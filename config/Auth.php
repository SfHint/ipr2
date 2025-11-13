<?php
// Simple API key auth middleware
class Auth {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function authenticate() {
        $headers = getallheaders();
        if (!isset($headers['X-API-Key']) && !isset($headers['x-api-key'])) {
            $this->unauthorized('API key missing');
        }
        $key = isset($headers['X-API-Key']) ? $headers['X-API-Key'] : $headers['x-api-key'];
        // find hashed key in DB
        $stmt = $this->pdo->prepare('SELECT api_key, is_active FROM api_keys WHERE is_active = 1');
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            if (password_verify($key, $row['api_key'])) {
                return true;
            }
        }
        $this->unauthorized('Invalid API key');
    }

    private function unauthorized($message) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized', 'message' => $message]);
        exit;
    }
}
