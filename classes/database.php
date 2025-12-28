<?php 
class Database{
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $dbname = "hospital";
    public $conn;


    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);

        if ($this->conn->connect_error) {
            die("Połączenie nieudane: " . $this->conn->connect_error);
        }

        $this->conn->set_charset("utf8mb4");
    }

    public function execute($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            die("Błąd zapytania SQL: " . $this->conn->error);
        }

        if (!empty($params)) {
            // Dynamiczne bindowanie typów (s = string, i = integer, d = double, b = blob)
            $types = "";
            foreach ($params as $param) {
                if (is_int($param)) $types .= "i";
                elseif (is_double($param)) $types .= "d";
                else $types .= "s";
            }
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        
        $result = $stmt->get_result();
        
        // Zamykamy statement po wykonaniu
        // Uwaga: jeśli potrzebujesz insert_id, nie zamykaj stmt tutaj, 
        // ale w tym prostym projekcie wystarczy nam pobranie go z obiektu połączenia.
        return $result ? $result : $stmt;
    }

    public function lastInsertId() {
        return $this->conn->insert_id;
    }

    public function querySingle($sql, $params = []) {
        $result = $this->execute($sql, $params);
        return $result->fetch_assoc();
    }

    public function queryAll($sql, $params = []) {
        $result = $this->execute($sql, $params);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

}
?>