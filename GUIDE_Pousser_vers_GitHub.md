## Guide pour publier le projet `sifec` sur GitHub

1. **Initialiser Git (si nécessaire)**  
   `git init`

2. **Configurer ton identité Git (à faire une seule fois par machine)**  
   ```
   git config user.name "TonNom"
   git config user.email "ton.email@example.com"
   ```

3. **Créer un `.gitignore` adapté à Laravel**  
   - Télécharge le modèle officiel : https://github.com/github/gitignore/blob/main/Laravel.gitignore  
   - Sauvegarde-le à la racine du projet sous le nom `.gitignore`.

4. **Ajouter les fichiers suivis**  
   `git add .`

5. **Créer le premier commit**  
   `git commit -m "Initial commit"`

6. **Créer un dépôt vide sur GitHub**  
   - Va sur https://github.com/new  
   - Donne-lui un nom (ex. `sifec`).  
   - Ne coche ni README, ni licence, ni `.gitignore`.

7. **Déclarer la télécommande GitHub dans ton dépôt local**  
   - Via SSH : `git remote add origin git@github.com:TonCompte/sifec.git`  
   - ou HTTPS : `git remote add origin https://github.com/TonCompte/sifec.git`

8. **Pousser la branche principale**  
   ```
   git branch -M main
   git push -u origin main
   ```

9. **Vérifier sur GitHub**  
   - Ouvre le dépôt sur GitHub pour confirmer que les fichiers sont bien présents.

10. **Flux de travail quotidien**  
    ```
    git add <fichiers_modifiés>
    git commit -m "Message clair"
    git push
    ```

### Bonnes pratiques supplémentaires
- Ne versionne pas ton fichier `.env`. Crée une copie `\.env.example` avec des valeurs neutres.  
- Avant de committer, vérifie l’état du dépôt : `git status`.  
- Utilise les commandes de nettoyage (`php artisan config:clear`, `php artisan cache:clear`, etc.) uniquement quand c’est pertinent, et évite de committer les fichiers générés automatiquement.  
- En cas d’erreur (authentification, conflits, etc.), note le message exact pour pouvoir le résoudre rapidement.

