<?php
class User extends DatabaseModel {
    
    private static $instance = null;

    private $fields = [
        'id' => 'id',
        'email' => 'email',
        'api_token' => 'api_token',
    ];
    private $json = [];
    private $conn = [];

    public static function getInstance() {
        if (!isset(self::$instance)) {
                self::$instance = new User();
        }
        return self::$instance;
    }


    public function __construct() {
        parent::__construct();
        $this->conn = $this->getConnection();
        $this->json = new ResponseJSON();
    }

    private function checkPasswords(string $localPassword, string $remotePassword) {
        return password_verify($localPassword, $remotePassword);
    }

    public function findByEmail(string $email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param('s', $email);
        return $this->getStmtAssocOrFalse($stmt);
    }



    public function login(Request $request): string {
        $email = $request->body->email;
        $password = $request->body->password;

        if (!isset($email) || !isset($password)) return ResponseJSON::error(400, 'Bad Request: Email or password not found');

        $userData = $this->findByEmail($email);
        if (!$userData) return ResponseJSON::error(401, 'Unauthorized: Email not found');

        $passwordIsValid = $this->checkPasswords($password, $userData['password']);
        if (!$passwordIsValid) return ResponseJSON::error(401, 'Unauthorized: Password not valid');

        if (!$userData['api_token']) return ResponseJSON::error(401, 'Not available token. Register to use the API :)');
        
        return $this->json->setResponseAndReturn(200, 'Succesful login', 'OK', $userData['api_token'], 'token');
    }



    public function register(Request $request) {
        if (!isset($request->body->email) || !isset($request->body->password)) 
            return ResponseJSON::error(400, 'Bad Request: Credentials are missing');

        $pasword = password_hash($request->body->password, PASSWORD_DEFAULT);
        $passphrase = $request->body->passphrase ?? bin2hex(random_bytes(16));

        $user = $this->findByEmail(trim($request->body->email));
        if (!$this->checkPasswords($request->body->password, $user['password']))
            return ResponseJSON::error(400, 'Bad Request: Incorrect password');
        
        if (!empty($user['api_token'])) return ResponseJSON::error(400, 'Token already exists. Login to use the API :)');
        $token = cut255(generateTokenAPI($passphrase));
        $this->saveTokenToUser($user['id'], $token);

        return $this->json->setResponseAndReturn(200, 'Succesful register', 'OK', $token, 'token');
    }

    private function saveTokenToUser (int $id, string $token) {
        $sql = "UPDATE users SET api_token = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('si', $token, $id);
        return $this->stmt->execute();
    }



    public function getUserTokenIfAny(string $token) {
        $sql = "SELECT id, email, api_token FROM users WHERE api_token = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $token);
        
        return $this->getStmtAssocOrFalse($stmt);
    }


    public function test () {
        return $this->json->setResponseAndReturn(200, 'Succesful', 'OK', 'test');
    }
}