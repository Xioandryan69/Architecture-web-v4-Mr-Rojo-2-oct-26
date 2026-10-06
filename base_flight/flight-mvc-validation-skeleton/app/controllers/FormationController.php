<?php
class FormationController {

    // Affiche la vue principale HTML[cite: 2]
    public static function index() {
        Flight::render('index');
    }

    // Endpoint API : Récupérer toutes les formations[cite: 2]
    public static function apiGetAll() {
        $repo = new FormationRepository(Flight::db());
        Flight::json($repo->getAll(), 200);
    }

    // Endpoint API : Récupérer une formation par ID
    public static function apiGetOne($id) {
        $repo = new FormationRepository(Flight::db());
        $formation = $repo->getById($id);

        if ($formation) {
            Flight::json($formation, 200);
        } else {
            Flight::json(['erreur' => "Formation $id introuvable"], 404);
        }
    }

    // Endpoint API : Créer une formation[cite: 2]
    public static function apiCreate() {
        $req = Flight::request();
        $data = json_decode($req->getBody(), true);

        $titre       = trim($data['titre'] ?? '');
        $description = trim($data['description'] ?? '');
        $niveau      = trim($data['niveau'] ?? '');

        if (empty($titre) || empty($description) || !in_array($niveau, ['L1', 'L2', 'L3'])) {
            Flight::json(['erreur' => 'Champs invalides ou manquants'], 400);
            return;
        }

        $repo = new FormationRepository(Flight::db());
        $nouvelleFormation = $repo->create($titre, $description, $niveau);

        Flight::json($nouvelleFormation, 201);
    }
}