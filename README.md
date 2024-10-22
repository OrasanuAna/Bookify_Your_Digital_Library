
# Librărie PHP

### Descriere
**Librărie PHP** este un proiect de aplicație web creată folosind PHP, care gestionează o librărie virtuală. Aplicația permite utilizatorilor să vizualizeze, adauge, editeze și să șteargă cărți din catalogul bibliotecii. Este un sistem simplu de management al cărților, destinat să demonstreze funcționalitățile de bază ale limbajului PHP și interacțiunea cu o bază de date.

### Funcționalități cheie:
- Adăugarea, editarea și ștergerea cărților în baza de date.
- Vizualizarea catalogului complet de cărți.
- Funcționalitate de căutare după titlu, autor sau gen.
- Validare a datelor la adăugarea sau modificarea cărților.

### Tehnologii utilizate:
- **Back-end:** PHP
- **Front-end:** HTML, CSS
- **Bază de date:** MySQL
- **Alte tehnologii:** Bootstrap pentru stilizarea interfeței.

### Instalare
1. Clonează acest repository:
   ```bash
   git clone https://github.com/OrasanuAna/librarie_php.git
   ```
2. Accesează directorul proiectului:
   ```bash
   cd librarie_php
   ```
3. Configurează fișierul `config.php` pentru a se potrivi cu setările tale de bază de date MySQL.
4. Importă fișierul `librarie.sql` în baza ta de date pentru a crea structura necesară:
   ```bash
   mysql -u username -p password librarie < librarie.sql
   ```
5. Rulează aplicația pe un server local (cum ar fi XAMPP, WAMP sau MAMP) și accesează aplicația din browser.

### Utilizare
- Accesează interfața principală și adaugă noi cărți în catalog.
- Navighează printre cărți și utilizează funcția de căutare pentru a filtra titlurile dorite.
- Editează informațiile cărților sau șterge-le atunci când este necesar.
