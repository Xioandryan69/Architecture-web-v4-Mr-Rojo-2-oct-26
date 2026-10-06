package com.example.formations.controller;

import com.example.formations.model.Formation;
import com.example.formations.repository.FormationRepository;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.List;
import java.util.Map;

@RestController
@RequestMapping("/api/formations")
@CrossOrigin(origins = "*") // Permet les requêtes depuis le front Vue.js (port 5173)
public class FormationController {

    private final FormationRepository repository;

    public FormationController(FormationRepository repository) {
        this.repository = repository;
    }

    // GET /api/formations
    @GetMapping
    public List<Formation> getAll() {
        return repository.findAllByOrderByNiveauAscTitreAsc();
    }

    // GET /api/formations/{id}
    @GetMapping("/{id}")
    public ResponseEntity<?> getOne(@PathVariable Long id) {
        return repository.findById(id)
                .<ResponseEntity<?>>map(ResponseEntity::ok)
                .orElseGet(() -> ResponseEntity.status(HttpStatus.NOT_FOUND)
                        .body(Map.of("erreur", "Formation " + id + " introuvable")));
    }

    // POST /api/formations
    @PostMapping
    public ResponseEntity<?> create(@RequestBody Formation formation) {
        String titre = formation.getTitre() != null ? formation.getTitre().trim() : "";
        String description = formation.getDescription() != null ? formation.getDescription().trim() : "";
        String niveau = formation.getNiveau() != null ? formation.getNiveau().trim() : "";

        // Validation identique au PHP
        if (titre.isEmpty() || description.isEmpty() || 
            (!niveau.equals("L1") && !niveau.equals("L2") && !niveau.equals("L3"))) {
            return ResponseEntity.badRequest().body(Map.of("erreur", "Champs invalides ou manquants"));
        }

        formation.setTitre(titre);
        formation.setDescription(description);
        formation.setNiveau(niveau);

        Formation nouvelleFormation = repository.save(formation);
        return ResponseEntity.status(HttpStatus.CREATED).body(nouvelleFormation);
    }
}