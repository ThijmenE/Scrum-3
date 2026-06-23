# Grytsje Suze — Installatiegids

Deze gids legt stap voor stap uit hoe je de website lokaal op je computer aan de praat krijgt. Volg de stappen op volgorde. Sla niets over.

---

## Wat ga je installeren?

Je hebt vier programma's nodig. Die installeer je allemaal in deel 1.

| Programma | Waarvoor |
|-----------|----------|
| XAMPP | Zorgt voor een lokale webserver en database op je computer |
| Composer | Installeert de PHP-bibliotheken waar het project gebruik van maakt |
| Node.js | Installeert de CSS-tools |
| Visual Studio Code | Teksteditor om bestanden te bewerken |

---

## Deel 1 — Programma's installeren

### 1.1 XAMPP installeren

1. Ga naar [apachefriends.org](https://www.apachefriends.org/download.html)
2. Klik op **"Download"** onder de Windows-versie
3. Open het gedownloade bestand en klik steeds op **Next** totdat de installatie klaar is
4. Laat de standaard installatiemap (`C:\xampp`) staan

### 1.2 Composer installeren

1. Ga naar [getcomposer.org/download](https://getcomposer.org/download/)
2. Klik op **"Composer-Setup.exe"** en download het bestand
3. Open het gedownloade bestand
4. Op het scherm **"Settings Check"** zie je een pad naar `php.exe` — dit zou automatisch moeten worden ingevuld als XAMPP al geïnstalleerd is. Staat er niets? Klik op **Browse** en zoek naar:
   ```
   C:\xampp\php\php.exe
   ```
5. Klik steeds op **Next** en daarna op **Install**
6. Klik na de installatie op **Finish**

> **Belangrijk:** Sluit na de installatie alle open terminalvensters en open ze opnieuw. Anders herkent Windows Composer nog niet.

### 1.3 Node.js installeren

1. Ga naar [nodejs.org](https://nodejs.org/)
2. Klik op de grote groene knop (**LTS** versie — de aanbevolen versie)
3. Open het gedownloade bestand en klik steeds op **Next** totdat de installatie klaar is

### 1.4 Visual Studio Code installeren

1. Ga naar [code.visualstudio.com/download](https://code.visualstudio.com/download)
2. Klik op **Windows** en download het installatiebestand
3. Open het bestand en klik steeds op **Next**
4. Vink tijdens de installatie **"Add to PATH"** aan als je die optie ziet — dat maakt het later makkelijker

---

## Deel 2 — Project openen in VSCode

1. Open **Visual Studio Code**
2. Klik op **File** → **Open Folder**
3. Zoek de projectmap op (de map die je hebt uitgepakt) en klik op **"Select Folder"**
4. Je ziet nu links alle bestanden van het project staan

**Terminal openen in VSCode:**
- Klik bovenaan op **Terminal** → **New Terminal**
- Er verschijnt een zwart venster onderaan — dit is de terminal
- Controleer dat het pad in de terminal eindigt op de projectmap (bijv. `C:\Users\...\project_Grytsje_Suze-main`). Zo niet, dan ben je in de verkeerde map.

---

## Deel 3 — XAMPP starten

1. Open **XAMPP Control Panel** (zoek het via de Startknop)
2. Klik op **Start** naast **Apache** — de tekst wordt groen
3. Klik op **Start** naast **MySQL** — de tekst wordt groen

> Als Apache niet wil starten, draait er mogelijk al iets op poort 80 (zoals Skype of IIS). Klik dan op **Config** naast Apache → **httpd.conf**, zoek naar `Listen 80` en verander dat naar `Listen 8080`. Start Apache daarna opnieuw.

---

## Deel 4 — Database aanmaken

### 4.1 phpMyAdmin openen

1. Open je browser
2. Ga naar: `http://localhost/phpmyadmin/`
3. Je ziet een blauw beheerscherm — dit is phpMyAdmin

### 4.2 Nieuwe database aanmaken

1. Klik links in de zijbalk op **"New"**
2. Typ bij **"Database name"**: `grytsje_suze`
3. Kies rechts naast het veld: **utf8mb4_unicode_ci**
4. Klik op **Create**
5. De database `grytsje_suze` verschijnt nu in de linker zijbalk

### 4.3 Databasegebruiker aanmaken

1. Klik bovenaan op het tabblad **"User accounts"**
2. Klik op **"Add user account"**
3. Vul in:
   - **User name:** `grytsje_user`
   - **Host name:** kies `Local` uit het dropdownmenu (vult automatisch `localhost` in)
   - **Password:** verzin een wachtwoord en schrijf het op — je hebt het zo nodig
   - **Re-type:** herhaal het wachtwoord
4. Scroll omlaag naar het kopje **"Database for user account"**
5. Vink aan: **"Grant all privileges on database `grytsje_suze`"**
6. Scroll naar de onderkant van de pagina en klik op **Go**

### 4.4 Tabellen importeren

1. Klik links in de zijbalk op **`grytsje_suze`** om die database te selecteren
2. Klik bovenaan op het tabblad **"Import"**
3. Klik op **"Choose File"**
4. Zoek in de projectmap het bestand **`import.sql`** en selecteer het
5. Scroll naar onderen en klik op **Go**
6. Je ziet een groen succesbericht — de tabellen zijn nu aangemaakt

---

## Deel 5 — .env bestand aanmaken

Het `.env` bestand vertelt de website hoe hij verbinding maakt met de database en e-mails verstuurt. Dit bestand maak je eenmalig aan.

### 5.1 Kopieer het voorbeeldbestand

In de terminal (onderaan in VSCode):
```
copy .env-example .env
```

Druk op Enter. Er staat nu een bestand `.env` in de projectmap.

### 5.2 Bewerk het .env bestand

1. Klik links in VSCode op het bestand **`.env`** om het te openen
2. Pas de databaseregels aan met de gegevens die je in deel 4 hebt gebruikt:

```
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=grytsje_suze
DB_USERNAME=grytsje_user
DB_PASSWORD=het_wachtwoord_dat_je_hebt_gekozen
```

3. Pas daarna de mailregels aan (zie deel 6 voor uitleg):

```
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=jouw_gmail@gmail.com
MAIL_PASSWORD=jouw_app_wachtwoord
MAIL_FROM_NAME=Grytsje Suze
MAIL_TO=het_emailadres_waar_contactberichten_naartoe_moeten
```

4. Sla op met **Ctrl+S**

> **Let op:** zet geen spaties rond het `=` teken, en geen aanhalingstekens om de waarden.

---

## Deel 6 — E-mail instellen (Gmail)

Het contactformulier op de website stuurt e-mails via Gmail. Hiervoor heb je een speciaal "app-wachtwoord" nodig — je gewone Gmail-wachtwoord werkt hier niet.

### 6.1 App-wachtwoord aanmaken

1. Ga naar [myaccount.google.com](https://myaccount.google.com) en log in
2. Klik links op **"Beveiliging"**
3. Zorg dat **"Verificatie in twee stappen"** aanstaat — zo niet, zet het aan
4. Ga naar [myaccount.google.com/apppasswords](https://myaccount.google.com/apppasswords)
5. Kies bij **"App selecteren"**: `E-mail`
6. Kies bij **"Apparaat selecteren"**: `Windows-computer`
7. Klik op **Genereren**
8. Je krijgt een wachtwoord van 16 tekens te zien — kopieer dit

### 6.2 App-wachtwoord in .env plakken

Plak het gekopieerde wachtwoord (zonder spaties) bij `MAIL_PASSWORD` in je `.env` bestand:
```
MAIL_PASSWORD=abcdabcdabcdabcd
```

**Wil je liever niet met Gmail testen?** Gebruik dan [Mailtrap](https://mailtrap.io) — een gratis dienst waarbij e-mails worden opgevangen zonder echt verstuurd te worden. Maak een gratis account aan, ga naar **Email Testing → Inboxes** en kopieer de SMTP-gegevens naar je `.env`.

---

## Deel 7 — PHP-bibliotheken installeren

Typ in de terminal in VSCode:
```
composer install
```

Druk op Enter en wacht totdat het klaar is. Je ziet tekst voorbijkomen en uiteindelijk staat er iets als `Generating autoload files`. Er is nu een map `vendor/` aangemaakt — die heeft de website nodig om te werken.

> Krijg je de foutmelding `'composer' is not recognized`? Dan is Composer niet goed geïnstalleerd of moet je VSCode opnieuw opstarten. Sluit VSCode volledig af, open het opnieuw en probeer het nogmaals.

---

## Deel 8 — CSS bouwen

Je hebt twee terminalvensters nodig. Het eerste venster gebruik je voor de CSS-watcher, het tweede voor de webserver.

**Terminalvenster 1 — CSS watcher starten:**

Typ in de terminal:
```
npm install
```
Wacht totdat dit klaar is. Typ daarna:
```
npm run watch
```

Je ziet nu iets als `Rebuilding...` — laat dit venster open staan. De CSS-watcher draait nu op de achtergrond.

**Nieuw terminalvenster openen:**
- Klik in VSCode op het **+** icoontje rechtsboven in de terminal-balk
- Er opent een tweede terminal naast de eerste

---

## Deel 9 — Website starten

Typ in het **tweede** terminalvenster:
```
cd public
php -S localhost:8000
```

> Krijg je de foutmelding `'php' is not recognized`? Dan staat PHP niet in je PATH. Doe het volgende:
> 1. Zoek via de Startknop naar **"Omgevingsvariabelen bewerken"** en open het
> 2. Klik op **"Omgevingsvariabelen"**
> 3. Klik onder **"Systeemvariabelen"** op de variabele **Path** → **Bewerken**
> 4. Klik op **Nieuw** en voeg toe: `C:\xampp\php`
> 5. Klik op OK en sluit alles
> 6. Sluit VSCode en open het opnieuw
> 7. Probeer `php -S localhost:8000` opnieuw

Open je browser en ga naar:
```
http://localhost:8000/
```

De website staat nu lokaal op je computer. 🎉

---

## Deel 10 — Inloggen op het beheerpaneel

Ga naar `http://localhost:8000/admin/login`

| | |
|---|---|
| **E-mailadres** | `owner@example.com` |
| **Wachtwoord** | `Admin1234!` |

> **Verander dit wachtwoord direct na je eerste inlog.** Ga naar het gebruikersbeheer in het admin-panel om een nieuw account aan te maken, en verwijder daarna het standaardaccount.

---

## Overzicht: wat draait er tegelijk?

Als alles goed is ingesteld, heb je het volgende actief:

| Wat | Waar te zien |
|-----|--------------|
| XAMPP Apache | Groen in XAMPP Control Panel |
| XAMPP MySQL | Groen in XAMPP Control Panel |
| CSS watcher (`npm run watch`) | Terminalvenster 1 in VSCode |
| PHP server (`php -S localhost:8000`) | Terminalvenster 2 in VSCode |

---

## Problemen oplossen

**De pagina laadt niet / "This site can't be reached"**
- Controleer of de PHP server nog draait in terminalvenster 2
- Controleer of je naar `http://localhost:8000` gaat en niet `https://`

**Blanco pagina of foutmelding over de database**
- Controleer of MySQL groen is in XAMPP
- Open `.env` en controleer of `DB_DATABASE`, `DB_USERNAME` en `DB_PASSWORD` exact kloppen met wat je in deel 4 hebt ingevuld
- Controleer of de import van `import.sql` gelukt is (zie deel 4.4)

**"Class not found" of vergelijkbare PHP-fout**
- Je bent waarschijnlijk `composer install` vergeten — typ het opnieuw in de terminal

**CSS ziet er niet goed uit / geen styling**
- Zorg dat `npm run watch` draait in terminalvenster 1
- Of bouw de CSS eenmalig: typ in de terminal `npx tailwindcss -i ./public/css/input.css -o ./public/css/output.css`

**Mails worden niet verstuurd**
- Controleer of je een app-wachtwoord gebruikt bij Gmail, niet je gewone wachtwoord
- Probeer Mailtrap als alternatief (zie deel 6)
