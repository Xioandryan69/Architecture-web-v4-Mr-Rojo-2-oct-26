Voici les commandes à exécuter directement dans votre dossier `fastpi-app` pour créer un environnement virtuel Python, l'activer et installer les dépendances :

### 1. Créer l'environnement virtuel

```bash
python3 -m venv venv

```

> **Note :** Si la commande indique que le paquet `python3-venv` ou `python3-full` manque, installez-le avec :
> `sudo apt update && sudo apt install python3-venv python3-full`

### 2. Activer l'environnement virtuel

```bash
source venv/bin/activate

```

*(Vous verrez `(venv)` apparaître au début du prompt de votre terminal)*

### 3. Installer les dépendances avec `pip`

```bash
pip install -r requirements.txt

```

---

### 4. Lancer le serveur FastAPI

Une fois l'installation terminée :

```bash
uvicorn main:app --reload --port 8000

```

Pour désactiver l'environnement virtuel plus tard, il vous suffira de taper `deactivate`.