<?php
error_reporting(E_ALL);  // Włącz wyświetlanie wszystkich błędów
ini_set('display_errors', 1);  // Ustawienie wyświetlania błędów


class CategoryManager {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // Dodawanie nowej kategorii
    public function addCategory($name, $parent_id = 0) {
        $stmt = $this->db->prepare("INSERT INTO categories (name, parent_id) VALUES (?, ?)");
        $stmt->bind_param("si", $name, $parent_id);
        $stmt->execute();
    }

    // Usuwanie kategorii
    public function deleteCategory($id) {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    // Edycja kategorii
    public function editCategory($id, $newName) {
        $stmt = $this->db->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $newName, $id);
        $stmt->execute();
    }

    // Wyświetlanie kategorii z podkategoriami (rekurencja)
    public function displayCategories($parent_id = 0, $level = 0) {
        $stmt = $this->db->prepare("SELECT id, name FROM categories WHERE parent_id = ? ORDER BY name ASC");
        $stmt->bind_param("i", $parent_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo str_repeat("--", $level) . $row['name'] . "<br>";
            $this->displayCategories($row['id'], $level + 1);  // Rekurencyjne wywołanie dla podkategorii
        }
    }
}

?>
