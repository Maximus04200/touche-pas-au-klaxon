# Touche pas au klaxon

Application intranet de covoiturage inter-sites : diffusion des trajets planifies
entre les implantations de l'entreprise, pour favoriser le partage des vehicules.

## Fonctionnalites

- **Accueil (public)** : liste des trajets a venir avec places disponibles,
  triee par date de depart croissante.
- **Employe connecte** : details d'un trajet (contact, telephone, email,
  places totales), creation d'un trajet, modification/suppression de ses
  propres trajets.
- **Administrateur** : consultation des utilisateurs, gestion complete des
  agences (creation/modification/suppression), consultation et suppression
  de tous les trajets.

## Stack technique

- PHP 8.3, architecture MVC maison (pas de framework applicatif)
- Routeur [bramus/router](https://github.com/bramus/router)
- MySQL / MariaDB (acces via PDO, requetes preparees)
- Bootstrap 5 + Sass (variables Bootstrap surchargees), compile via Dart Sass
- PHPUnit 11 (tests), PHPStan niveau 8 (analyse statique)

## Prerequis

- PHP >= 8.3 avec l'extension `pdo_mysql`
- Composer 2
- Node.js / npm (pour compiler les assets Bootstrap/Sass)
- MySQL 8+ ou MariaDB 10.4+

## Installation

### 1. Dependances

```bash
composer install
npm install
npm run build     # compile scss/app.scss -> public/assets/css/app.css
                   # et copie bootstrap.bundle.min.js -> public/assets/js/
```

### 2. Base de donnees

Creer la base (avec un compte disposant du privilege `CREATE`, par ex. root) :

```bash
mysql -u root -p < database/create_database.sql
```

Creer un utilisateur applicatif dedie (recommande, ne pas utiliser root dans
l'application) :

```sql
CREATE USER 'klaxon_app'@'localhost' IDENTIFIED BY 'un_mot_de_passe_fort';
GRANT ALL PRIVILEGES ON touche_pas_au_klaxon.* TO 'klaxon_app'@'localhost';
FLUSH PRIVILEGES;
```

Charger le schema puis le jeu d'essai :

```bash
mysql -u klaxon_app -p touche_pas_au_klaxon < database/schema.sql
mysql -u klaxon_app -p touche_pas_au_klaxon < database/seed.sql
```

> **Note** : les agences et les employes de `database/seed.sql` sont repris
> de l'export RH fourni en annexe du cahier des charges. Seuls les mots de
> passe (absents de cet export) et les trajets sont des donnees de
> demonstration.

### 3. Configuration

```bash
cp .env.example .env
```

Renseigner dans `.env` les identifiants de connexion a la base
(`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

### 4. Lancer l'application

```bash
php -S 127.0.0.1:8000 -t public
```

Puis ouvrir <http://127.0.0.1:8000>.

## Comptes de demonstration

| Role     | Email                          | Mot de passe   |
|----------|----------------------------------|----------------|
| Admin    | `admin@klaxon.local`             | `Admin#2026`   |
| Employe  | `alexandre.martin@email.fr`      | `Employe#2026` |

Le compte admin est un compte applicatif dedie (hors export RH). Les 20
employes importes de l'annexe partagent tous le mot de passe `Employe#2026`
(l'export RH ne fournit pas de mots de passe).

## Tests automatises

Les tests couvrent les operations d'ecriture en base (creation, modification,
suppression de trajets et d'agences), la protection par contrainte
d'integrite (suppression d'une agence encore utilisee) et les regles de
validation. Ils s'executent dans une base separee, a l'interieur d'une
transaction systematiquement annulee : ils ne modifient jamais les donnees
de developpement.

Creer la base de test (memes identifiants que `.env`) :

```bash
mysql -u root -p -e "CREATE DATABASE touche_pas_au_klaxon_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci; GRANT ALL PRIVILEGES ON touche_pas_au_klaxon_test.* TO 'klaxon_app'@'localhost';"
mysql -u klaxon_app -p touche_pas_au_klaxon_test < database/schema.sql
```

Puis lancer la suite :

```bash
composer test
```

## Qualite de code

```bash
composer stan    # PHPStan niveau 8 sur src/, config/, public/
composer check   # PHPStan + PHPUnit
```

## Structure du projet

```
public/            Front controller (index.php) et assets compiles
config/            Configuration (.env) et declaration des routes
src/Core/          Brique MVC (routeur/DB/Auth/CSRF/Validator/FlashMessage)
src/Controllers/   Controleurs (Home, Auth, Trajet, Admin)
src/Models/        Acces aux donnees (Agence, Utilisateur, Trajet)
views/             Vues PHP (layout + partials + pages par module)
scss/              Source Sass (variables Bootstrap personnalisees)
database/          Scripts de creation de base, de schema et de jeu d'essai
docs/              MCD (mcd.png) et MLD (mld.txt)
tests/Unit/        Tests PHPUnit
```

## Documentation des donnees

- MCD : [`docs/mcd.png`](docs/mcd.png)
- MLD : [`docs/mld.txt`](docs/mld.txt)
- Script de creation de la base : [`database/create_database.sql`](database/create_database.sql)
- Script de creation des tables : [`database/schema.sql`](database/schema.sql)
- Jeu d'essai : [`database/seed.sql`](database/seed.sql)

## Limites connues

- Les visuels fournis en annexe sont des maquettes fonctionnelles en niveaux
  de gris (pas de palette de couleurs imposee) : l'interface suit fidelement
  leur structure (bandeau arrondi, tableaux, icones d'action, message flash
  neutre) avec une palette Bootstrap sobre.
- Les trajets du jeu d'essai sont des donnees de demonstration ; les agences
  et les employes proviennent de l'annexe fournie avec le devoir.
