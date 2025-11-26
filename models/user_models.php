<?php
    class User {
        private $mysqli;

        public function __construct($mysqli) {
            $this->mysqli = $mysqli;
        }

        public function getByEmail($email) {
            $stmt = $this->mysqli->prepare("SELECT * FROM user WHERE email=?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }

        public function update($name, $about, $address, $imagepath, $email) {
            $stmt = $this->mysqli->prepare("UPDATE user SET name=?, aboutme=?, address=?, imagepath=? WHERE email=?");
            $stmt->bind_param("sssss", $name, $about, $address, $imagepath, $email);
            return $stmt->execute();
        }

        public function deleteProducts($user_id) {
            $stmt = $this->mysqli->prepare("DELETE FROM products WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            return $stmt->execute();
        }

        public function deleteUser($email) {
            $stmt = $this->mysqli->prepare("DELETE FROM user WHERE email=?");
            $stmt->bind_param("s", $email);
            return $stmt->execute();
        }
    }
?>