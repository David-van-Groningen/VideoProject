## 📌 Projectomschrijving

Dit project is een **eenvoudig video--beheerplatform** waarin gebruikers
YouTube-video's kunnen opslaan per categorie.\
Het is bedoeld voor **gebruikers die snel video's willen groeperen,
openen, aanpassen en delen** binnen een overzichtelijke interface.

Daarnaast kunnen **admins categorieën beheren**, terwijl normale
gebruikers enkel video's kunnen toevoegen, bewerken of verwijderen
binnen hun eigen account.\
Het project lost het probleem op dat YouTube-links vaak overal verspreid
staan: dit platform centraliseert ze en maakt ze beheersbaar.

------------------------------------------------------------------------

## ⭐ Functionaliteiten

-   Inloggen, registreren en uitloggen\
-   Video's toevoegen met titel, YouTube-URL en optionele thumbnail\
-   Video's bewerken en verwijderen (gebruiker of admin)\
-   Categorieën aanmaken (alleen admin)\
-   Overzichtelijke categoriepagina met alle video's\
-   Automatische thumbnail-detectie wanneer geen eigen thumbnail is
    opgegeven\
-   Moderne UI: grid-cards, modals, glassmorphism-design\
-   Carousel banner op categoriepagina\
-   Link-kopieerfunctie en directe video-openknop\
-   AJAX-functionaliteit voor add/edit/delete van video's

------------------------------------------------------------------------

## 🛠 Installatie-instructies

### 1. Vereisten

-   PHP 8+
-   MySQL/MariaDB
-   Een lokale server zoals XAMPP, WAMP, Laragon of MAMP

------------------------------------------------------------------------

### 2. Project installeren

1.  Plaats alle bestanden in je lokale servermap, bijvoorbeeld:\
    `htdocs/video-platform/`
2.  Controleer dat `config.php` de juiste database-inloggegevens bevat.

------------------------------------------------------------------------

### 3. Database importeren

1.  Open **phpMyAdmin**\

2.  Maak een lege database aan met de naam:

        video_platform

3.  Ga naar *Import*\

4.  Selecteer het bestand:\
    **db.sql**\

5.  Voer de import uit.

------------------------------------------------------------------------

### 4. Belangrijke mappen & instellingen

-   `assets/` → bevat CSS, JS en afbeeldingen\
-   `assets/app.js` → functies voor modals, videoacties en carousel\
-   `assets/style.css` → styling voor alle pagina's\
-   Zorg dat sessies werken in PHP\
-   Zorg dat `config.php` correcte databasegegevens bevat

------------------------------------------------------------------------

## 🧰 Technieken die gebruikt zijn

-   **PHP** --- backend, authenticatie, form-afhandeling\
-   **MySQL** --- database voor gebruikers, categorieën en video's\
-   **HTML5 / CSS3** --- interface, responsive layout, glassmorphism\
-   **JavaScript (ES6)** --- modals, AJAX-requests, copy-to-clipboard,
    carousel\
-   **Fetch API** --- asynchronous CRUD-acties voor video's\
-   **PDO** --- veilige database-communicatie met prepared statements
