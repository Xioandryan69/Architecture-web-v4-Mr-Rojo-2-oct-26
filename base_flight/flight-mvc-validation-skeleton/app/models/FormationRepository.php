<?php
class FormationRepository {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query('SELECT * FROM formations ORDER BY niveau, titre');
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM formations WHERE id = ?');
        $stmt->execute([(int)$id]);
        return $stmt->fetch();
    }

    public function create($titre, $description, $niveau) {
        $stmt = $this->pdo->prepare('INSERT INTO formations (titre, description, niveau) VALUES (?, ?, ?)');
        $stmt->execute([$titre, $description, $niveau]);

        return [
            'id'          => (int)$this->pdo->lastInsertId(),
            'titre'       => $titre,
            'description' => $description,
            'niveau'      => $niveau
        ];
    }
}