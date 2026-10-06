package com.example.formations.repository;

import com.example.formations.model.Formation;
import org.springframework.data.jpa.repository.JpaRepository;

import java.util.List;

public interface FormationRepository extends JpaRepository<Formation, Long> {
    // Trie par niveau puis par titre comme dans l'API PHP d'origine
    List<Formation> findAllByOrderByNiveauAscTitreAsc();
}