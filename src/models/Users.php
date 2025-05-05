<?php

class Users {
    private $conn;

    function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = 'SELECT * FROM account';
        $stmt = $this->conn->query($query);
        $result = $stmt->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    public function getAllId() {
        $query = 'SELECT id FROM account';
        $stmt = $this->conn->query($query);
        $tmp = $stmt->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $result = array();
        foreach($tmp as $v) {
            array_push($result, $v['id']);
        }
        return $result;
    }
    public function getStudents() {
        $query = 'SELECT id, username, fullname, email, phone FROM account WHERE type="student"';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $username, $fullname, $email, $phone);
        $result = [];
        while ($stmt->fetch()) {
            array_push($result, [
                'id' => $id,
                'username' => $username,
                'fullname' => $fullname,
                'email' => $email,
                'phone' => $phone
            ]);       
        }
        $stmt->close();
        return $result;
    }
    public function getUser($username) {
        $query = 'SELECT id, type, fullname, email, phone, avatar FROM account WHERE username = ? LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $type, $fullname, $email, $phone, $avatar);
        while ($stmt->fetch()) {
            $stmt->close();
            $result = [
                'id' => $id,
                'type' => $type, 
                'fullname' => $fullname,
                'email' => $email,
                'phone' => $phone,
                'avatar' => $avatar
            ];
            return $result;       
        }
    }

    public function update($username, $data) {
        $query = 'SELECT id FROM account WHERE username = ? LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id);
        
        if (!$stmt->fetch()) {
            $stmt->close();
            return false;
        }
        $stmt->close();

        if (empty($data)) {
            return true;
        }

        $query = 'UPDATE account SET ';
        $params = [];
        $types = '';

        $fields = ['username', 'fullname', 'email', 'phone', 'type', 'avatar'];
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $query .= "$field=?, ";
                $params[] = $data[$field];
                $types .= 's';
            }
        }

        if (empty($params)) {
            return true;
        }

        $query = rtrim($query, ', ');
        
        $query .= ' WHERE id=?';
        $params[] = $id;
        $types .= 'i';

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    public function getLastId() {
        $query = 'SELECT id FROM account ORDER BY id DESC LIMIT 1';
        $stmt = $this->conn->query($query);
        $result = $stmt->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result[0]['id'];
    }

    public function insert($data) {
        $data['id'] = (int)$this->getLastId() + 1;
        $query = 'INSERT INTO account (id, username, password, fullname, email, phone, type) VALUES (?, ?, ?, ?, ?, ?, ?)';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('sssssss', $data['id'], $data['username'], $data['password'], $data['fullname'], $data['email'], $data['phone'], $data['type']);
        $stmt->execute();
        $done = $stmt->affected_rows;
        $stmt->close();
        return $done;
    }

    public function getPassword($username) {
        $query = 'SELECT password FROM account WHERE username=? LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($password);
        while($stmt->fetch()) {
            $stmt->close();
            return $password;
        }
    }

    public function changePassword($username, $password) {
        $query = 'UPDATE account SET password=? WHERE username=?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $password, $username);
        $stmt->execute();
        $done = $stmt->affected_rows;
        $stmt->close();
        return $done;
    }

    public function deleteUser($username) {
        $query = 'DELETE FROM account WHERE username=?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s',  $username);
        $stmt->execute();
        $done = $stmt->affected_rows;
        $stmt->close();
        return $done;
    }

    public function getAvatar($id) {
        $query = 'SELECT avatar FROM account WHERE id=? LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($avt);
        while($stmt->fetch()) {
            $stmt->close();
            return $avt;
        }
    }

    public function setAvatar($id, $url) {
        $query = 'UPDATE account SET avatar=? WHERE id=?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('si', $url, $id);
        $stmt->execute();
        $done = $stmt->affected_rows;
        $stmt->close();
        return $done;
    }
}