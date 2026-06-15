# Installatie-instructies

## 1. Voorbereiding

1. Unzip deze map
2. Download [Visual Studio Code](https://code.visualstudio.com/download)
3. Download [XAMPP Control Panel](https://www.apachefriends.org/download.html)
4. Open XAMPP Control Panel
5. Klik **"Start"** naast **Apache**
6. Klik **"Start"** naast **MySQL**

---

## 2. Database gebruiker aanmaken in phpMyAdmin

1. **Open phpMyAdmin**
   - Open uw browser
   - Ga naar `http://localhost/phpmyadmin/`
   - Log in met gebruiker: `root` (geen wachtwoord nodig bij XAMPP)

2. **Maak een nieuwe gebruiker aan**
   - Klik bovenaan op het tabblad **"User accounts"**
   - Klik op **"Add user account"**
   - Vul het volgende in:
     - **Login name:** `tassen_user` (of een andere naam naar keuze)
     - **Host name:** `localhost`
     - **Password:** Kies een sterk wachtwoord (onthoud dit!)
     - **Re-type:** Herhaal het wachtwoord
   - Scroll naar beneden en vink **"Create database with same name and grant all privileges"** aan
   - Klik op **"Go"** om de gebruiker aan te maken

3. **Bevestiging**
   - U ziet een succesbericht
   - De database `tassen_user` is aangemaakt met alle rechten voor deze gebruiker

---

## 3. .env bestand aanmaken en instellen

4. **Kopieer `.env.example` naar `.env`**

   **Via Windows Verkenner:**
   - Zoek het bestand `.env.example` in de projectmap
   - Klik er met rechts op → **"Copy"**
   - Plak het in dezelfde map en hernoem het naar `.env`

   **Via terminal (VSCode):**
   - Open de terminal met **Ctrl+\`**
   - Type: `copy .env.example .env` (Windows) of `cp .env.example .env` (Mac/Linux)

5. **Bewerk het `.env` bestand**
   - Open `.env` in VSCode (dubbelklik of sleep het naar VSCode)
   - Pas de volgende regels aan:

   ```
   DB_HOST=localhost
   DB_USER=tassen_user
   DB_PASSWORD=uw_gekozen_wachtwoord
   DB_NAME=tassen_user
   ```

   | Variabele     | Waarde                                              |
   |---------------|-----------------------------------------------------|
   | `DB_HOST`     | Laat op `localhost` staan                           |
   | `DB_USER`     | De gebruikersnaam die u zojuist aanmaakte           |
   | `DB_PASSWORD` | Het wachtwoord dat u bij stap 2 hebt ingesteld      |
   | `DB_NAME`     | De databasenaam (meestal dezelfde als `DB_USER`)    |

6. **Sla het bestand op** met **Ctrl+S**

---

## 4. E-mail instellingen aanpassen in .env

7. **Zoek de mailconfiguratie** in uw `.env` bestand:

   ```
   MAIL_HOST=smtp.mailtrap.io
   MAIL_PORT=587
   MAIL_USERNAME=your_username
   MAIL_PASSWORD=your_password
   MAIL_FROM_ADDRESS=noreply@example.com
   MAIL_FROM_NAME=YourAppName
   ```

8. **Kies een e-mailprovider:**

   **Optie A — Gmail**
   | Variabele            | Waarde                                   |
   |----------------------|------------------------------------------|
   | `MAIL_HOST`          | `smtp.gmail.com`                         |
   | `MAIL_PORT`          | `587`                                    |
   | `MAIL_USERNAME`      | `uw_gmail_adres@gmail.com`               |
   | `MAIL_PASSWORD`      | Een App Password (zie hieronder)         |
   | `MAIL_FROM_ADDRESS`  | `uw_gmail_adres@gmail.com`               |
   | `MAIL_FROM_NAME`     | `Tassen`                                 |

   > Genereer een App Password via [myaccount.google.com/apppasswords](https://myaccount.google.com/apppasswords) — gebruik **niet** uw normale Gmail-wachtwoord.

   **Optie B — Mailtrap (gratis, voor development)**
   | Variabele            | Waarde                      |
   |----------------------|-----------------------------|
   | `MAIL_HOST`          | `smtp.mailtrap.io`          |
   | `MAIL_PORT`          | `587` (of `465` voor SSL)   |
   | `MAIL_USERNAME`      | Uw Mailtrap username        |
   | `MAIL_PASSWORD`      | Uw Mailtrap password        |
   | `MAIL_FROM_ADDRESS`  | `noreply@example.com`       |
   | `MAIL_FROM_NAME`     | `Tassen`                    |

   > Registreer gratis op [mailtrap.io](https://mailtrap.io) en kopieer de inloggegevens.

9. **Sla het bestand op** met **Ctrl+S**

10. **Controleer de instellingen**
    - Geen spaties rond `=` tekens
    - Waarden staan niet tussen aanhalingstekens (tenzij ze spaties bevatten)

---

## 5. Applicatie starten

1. Open **Visual Studio Code**
2. Ga naar **File → Open Folder** en selecteer deze map
3. Open de terminal via **Terminal → New Terminal**
4. Type in de terminal:
   ```
   npm run watch
   ```
5. Klik op het **"+"** icoon in de terminal om een nieuwe tab te openen
6. Type in de nieuwe terminal:
   ```
   cd public
   php -S localhost:8000
   ```
7. Open uw browser en ga naar:
   ```
   http://localhost:8000/
   ```

Gefeliciteerd, de applicatie draait!
