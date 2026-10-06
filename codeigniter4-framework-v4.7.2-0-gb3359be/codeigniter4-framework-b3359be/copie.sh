#!/bin/bash

# Dalusan wenno irigi ti source.txt
> source.txt

# Mapan kadagiti espesipiko a files a naited
FILES_TO_COPY=(
    "app/Config/App.php"
    "app/Config/Routes.php"
    "app/Config/Database.php"
)

# 1. Kopiaen dagiti espesipiko a files manipud app/Config/
for file in "${FILES_TO_COPY[@]}"; do
    if [ -f "$file" ]; then
        echo "=== Contenu de : $file ===" >> source.txt
        cat "$file" >> source.txt
        echo -e "\n\n" >> source.txt
    fi
done

# 2. Kopiaen amin a files kadagiti dadduma a folder ken sub-folder
DIRS_TO_COPY=(
    "app/Controllers"   # Salisalen iti CodeIgniter 4 ket "Controllers" ti eksakto a nagan ti folder
    "app/Database"
    "app/Filters"
    "app/Models"
    "app/Views"
    "public"
)

for dir in "${DIRS_TO_COPY[@]}"; do
    if [ -d "$dir" ]; then
        find "$dir" -type f \
            ! -name "*.db" \
            ! -name "*.sqlite" \
            ! -name "*.ico" \
            ! -name "source.txt" | while read -r file; do
                echo "=== Contenu de : $file ===" >> source.txt
                cat "$file" >> source.txt
                echo -e "\n\n" >> source.txt
        done
    fi
done

echo "Naka-kopia ken nakasagana amin dagiti files iti source.txt!"