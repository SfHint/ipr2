<?php
class CategoryModel {
    private $pdo;
    private $table = 'categories';
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT id, name, slug, parent_id FROM {$this->table} ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT id, name, slug, parent_id FROM {$this->table} WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (name, slug, parent_id) VALUES (:name, :slug, :parent_id)");
        $stmt->execute([
            ':name' => $data['name'],
            ':slug' => $data['slug'],
            ':parent_id' => $data['parent_id'] ?? null,
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET name = :name, slug = :slug, parent_id = :parent_id WHERE id = :id");
        return $stmt->execute([
            ':name' => $data['name'],
            ':slug' => $data['slug'],
            ':parent_id' => $data['parent_id'] ?? null,
            ':id' => $id,
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
