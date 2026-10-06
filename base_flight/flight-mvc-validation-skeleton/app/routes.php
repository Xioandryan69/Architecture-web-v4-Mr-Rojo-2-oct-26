<?php
require_once __DIR__ . '/controllers/FormationController.php';
require_once __DIR__ . '/models/FormationRepository.php';

// Route IHM (Vue HTML)
Flight::route('GET /', ['FormationController', 'index']);

// Routes API REST[cite: 1, 2]
Flight::route('GET /api/formations', ['FormationController', 'apiGetAll']);
Flight::route('GET /api/formations/@id', ['FormationController', 'apiGetOne']);
Flight::route('POST /api/formations', ['FormationController', 'apiCreate']);