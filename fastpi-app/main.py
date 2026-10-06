from fastapi import FastAPI, HTTPException, status
from fastapi.staticfiles import StaticFiles
from fastapi.responses import FileResponse
from pydantic import BaseModel, Field
from sqlalchemy import create_engine, Column, Integer, String
from sqlalchemy.orm import declarative_base, sessionmaker, Session
from typing import List

# ------------------------------------------------------------------------------
# Configuration de la base de données SQLite
# ------------------------------------------------------------------------------
DATABASE_URL = "sqlite:///./database.db"

engine = create_engine(DATABASE_URL, connect_args={"check_same_thread": False})
SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)
Base = declarative_base()

class FormationDB(Base):
    __tablename__ = "formations"

    id = Column(Integer, primary_key=True, index=True)
    titre = Column(String, nullable=False)
    description = Column(String, nullable=False)
    niveau = Column(String, nullable=False)

Base.metadata.create_all(bind=engine)

# Initialisation du jeu de données si la table est vide
def init_db():
    db = SessionLocal()
    if db.query(FormationDB).count() == 0:
        donnees_initiales = [
            FormationDB(titre="Algorithmique", description="Structures de contrôle, tableaux, complexité.", niveau="L1"),
            FormationDB(titre="Bases de données", description="Modèle relationnel, SQL, normalisation.", niveau="L1"),
            FormationDB(titre="Développement web", description="HTML, CSS, PHP et échanges client-serveur.", niveau="L2"),
            FormationDB(titre="Réseaux", description="Modèle TCP/IP, adressage, services réseau.", niveau="L2"),
            FormationDB(titre="Développement mobile", description="Applications Android, consommation d'API.", niveau="L3"),
            FormationDB(titre="Architecture logicielle", description="Couches, API REST, séparation front / back.", niveau="L3"),
        ]
        db.add_all(donnees_initiales)
        db.commit()
    db.close()

init_db()

# ------------------------------------------------------------------------------
# Schémas Pydantic (Validation DTO)
# ------------------------------------------------------------------------------
class FormationCreate(BaseModel):
    titre: str
    description: str
    niveau: str

class FormationResponse(BaseModel):
    id: int
    titre: str
    description: str
    niveau: str

    class Config:
        from_attributes = True

# ------------------------------------------------------------------------------
# Application FastAPI & Routes API
# ------------------------------------------------------------------------------
app = FastAPI(title="Formations API")

# GET /api/formations - Récupère la liste triée par niveau puis par titre
@app.get("/api/formations", response_model=List[FormationResponse])
def get_formations():
    db: Session = SessionLocal()
    formations = db.query(FormationDB).order_by(FormationDB.niveau.asc(), FormationDB.titre.asc()).all()
    db.close()
    return formations

# GET /api/formations/{id} - Récupère une formation par ID
@app.get("/api/formations/{id}", response_model=FormationResponse)
def get_formation(id: int):
    db: Session = SessionLocal()
    formation = db.query(FormationDB).filter(FormationDB.id == id).first()
    db.close()
    if not formation:
        raise HTTPException(
            status_code=status.HTTP_404_NOT_FOUND, 
            detail=f"Formation {id} introuvable"
        )
    return formation

# POST /api/formations - Ajoute une nouvelle formation
@app.post("/api/formations", response_model=FormationResponse, status_code=status.HTTP_201_CREATED)
def create_formation(formation: FormationCreate):
    titre = formation.titre.strip()
    description = formation.description.strip()
    niveau = formation.niveau.strip()

    # Validation identique aux versions PHP et Spring Boot
    if not titre or not description or niveau not in ["L1", "L2", "L3"]:
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST, 
            detail="Champs invalides ou manquants"
        )

    db: Session = SessionLocal()
    nouvelle_formation = FormationDB(titre=titre, description=description, niveau=niveau)
    db.add(nouvelle_formation)
    db.commit()
    db.refresh(nouvelle_formation)
    db.close()
    return nouvelle_formation

# ------------------------------------------------------------------------------
# Support des fichiers statiques pour le Front-end Vue.js
# ------------------------------------------------------------------------------
@app.get("/")
def serve_index():
    return FileResponse("static/index.html")

app.mount("/", StaticFiles(directory="static"), name="static")