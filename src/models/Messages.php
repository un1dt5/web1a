<?php

class Messages {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getMessagesForUser($username) {
        $stmt = $this->db->prepare("
            SELECT m.id, m.from_username, m.content, m.created_at 
            FROM messages m 
            WHERE m.to_username = ? 
            ORDER BY m.created_at DESC
        ");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function createMessage($from_username, $to_username, $content) {
        $stmt = $this->db->prepare("
            INSERT INTO messages (from_username, to_username, content) 
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param('sss', $from_username, $to_username, $content);
        return $stmt->execute();
    }
    
    public function updateMessage($id, $content, $from_username) {
        $stmt = $this->db->prepare("
            UPDATE messages 
            SET content = ? 
            WHERE id = ? AND from_username = ?
        ");
        $stmt->bind_param('sis', $content, $id, $from_username);
        return $stmt->execute();
    }
    
    public function deleteMessage($id, $from_username) {
        $stmt = $this->db->prepare("
            DELETE FROM messages 
            WHERE id = ? AND from_username = ?
        ");
        $stmt->bind_param('is', $id, $from_username);
        return $stmt->execute();
    }
    
    public function getMessage($id) {
        $stmt = $this->db->prepare("
            SELECT * FROM messages WHERE id = ?
        ");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}