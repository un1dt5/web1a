<?php

class Exercises {
    private $conn;
    
    function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = 'SELECT * FROM exercise';
        $stmt = $this->conn->query($query);
        $result = $stmt->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result;
    }

    public function getAllId() {
        $query = 'SELECT id FROM exercise';
        $stmt = $this->conn->query($query);
        $tmp = $stmt->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $result = array();
        foreach($tmp as $v) {
            array_push($result, $v['id']);
        }
        return $result;
    }

    public function getFromId($id) {
        $query = 'SELECT authorId, title, description, url, created, deadline FROM exercise WHERE id=?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($authorId, $title, $desc, $url, $created, $deadline);
        while ($stmt->fetch()) {
            $stmt->close();
            $result = [
                'authorId' => $authorId,
                'title' => $title,
                'desc' => $desc,
                'url' => $url,
                'created' => $created,
                'deadline' => $deadline
            ];
            return $result;
        }
    }

    public function create($data) {
        $query = 'INSERT INTO exercise SET authorId = ?, title = ?, description = ?, url = ?, created = now() , deadline = now() + INTERVAL 7 DAY';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ssss', $data['authorId'], $data['title'], $data['desc'], $data['url']);
        $stmt->execute();
        $done = $stmt->affected_rows;
        $stmt->close();
        return $done;
    }
    
    public function delete($id) {
        unlink(__DIR__.'/..'.$this->getFromId($id)['url']);
        $query = 'DELETE FROM exercise WHERE id=?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s',  $id);
        $stmt->execute();
        $done = $stmt->affected_rows;
        $stmt->close();
        return $done;
    }

    public function removeExistSubmit($userId, $exerId) {
        $query = 'DELETE FROM submit_exercise WHERE userId = ? AND exerciseId = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss',  $userId, $exerId);
        $stmt->execute();
        $stmt->close();
    }

    public function submit($id, $userId, $url) {
        $this->removeExistSubmit($userId, $id);
        $query = 'INSERT INTO submit_exercise SET exerciseId = ?, url = ?, userId = ?, created = now()';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('sss', $id, $url, $userId);
        $stmt->execute();
        $done = $stmt->affected_rows;
        $stmt->close();
        return $done;
    }

    public function getSubmit($userId) {
        $query = 'SELECT exerciseId, url FROM submit_exercise WHERE userId = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $userId);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($exerciseId, $url);
        $result = [];
        while ($stmt->fetch()) {
            array_push($result, [
                'exerciseId' => $exerciseId,
                'url' => $url
            ]);
        }
        $stmt->close();
        return $result;
    }
}