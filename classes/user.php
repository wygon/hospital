<?php
class User{
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

    public function login($username, $password) {
        $sql = "SELECT Id, Password, Role, Name, Surname FROM Users WHERE Username = ?";
        $result = $this->db->execute($sql, [$username]);
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if($user['Password'] == $password){
                return $user;
            }
            // if (password_verify($password, $user['Password'])) {
            //     return $user; // Zwracamy dane użytkownika
            // }
        }
        return false;
    }

    public function register($name, $surname, $username, $password, $role, $specialization) {
        $sql = "INSERT INTO users (Name, Surname, Username, Password, Role, Specialization,)
         VALUES (?,?,?,?,?,?)";

        $this->db->execute($sql, [$name, $surname, $username, $password, $role, $specialization]);
        $result = $this->db->lastInsertId();

        if($result != 0)
            return $result;

        return false;
    }


}
?>