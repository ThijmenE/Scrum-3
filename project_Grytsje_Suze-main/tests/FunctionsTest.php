<?php
// =============================================================================
// tests/FunctionsTest.php
// =============================================================================
// Voer uit vanuit de root van je project:
//   vendor/bin/phpunit tests/FunctionsTest.php --testdox
//
// Vereisten:
//   1. Maak een aparte testdatabase aan in phpMyAdmin: "grytsje_suze_test"
//   2. Kopieer de tabelstructuur van je echte DB daarheen (zonder data)
//   3. Vul de constanten hieronder in
// =============================================================================

define('ROOT_PATH', dirname(__DIR__));

require_once ROOT_PATH . '/vendor/autoload.php';

use App\Models\Tas;
use App\Models\User;
use PHPUnit\Framework\TestCase;

define('TEST_DB_HOST', 'localhost');
define('TEST_DB_NAME', 'grytsje_suze_test');
define('TEST_DB_USER', 'root');
define('TEST_DB_PASS', '');

class FunctionsTest extends TestCase
{
    private PDO $pdo;

    // Wordt automatisch uitgevoerd vóór elke losse test om de test-database leeg te maken
    protected function setUp(): void
    {
        $this->pdo = new PDO(
            "mysql:host=" . TEST_DB_HOST . ";dbname=" . TEST_DB_NAME . ";charset=utf8mb4",
            TEST_DB_USER,
            TEST_DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        // Zet checks tijdelijk uit om foreign key errors tijdens het leeggooien (truncaten) te voorkomen
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        $this->pdo->exec("TRUNCATE TABLE tassen");
        $this->pdo->exec("TRUNCATE TABLE users");
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    }

    // Hulpfunctie om snel een virtuele test-tas in de database te schieten
    private function maakTestTas(string $naam = 'Testtas'): int
    {
        return Tas::create($this->pdo, $naam, 'Een beschrijving', 'uploads/foto.jpg', '#ff40b4');
    }

    // Hulpfunctie om snel een virtuele test-beheerder in de database te schieten
    private function maakTestUser(string $email = 'admin@test.nl', string $wachtwoord = 'geheim123'): int
    {
        return User::create($email, $wachtwoord, $this->pdo);
    }


    // =========================================================================
    // TASSEN — US1, US4, US6, US7
    // =========================================================================

    /** @test */
    public function it_returns_a_valid_id_when_a_bag_is_created(): void
    {
        // Controleert of de database een correct auto-increment ID (groter dan 0) teruggeeft bij een nieuwe tas
        $id = $this->maakTestTas('Leren Tas');
        $this->assertGreaterThan(0, $id);
    }

    /** @test */
    public function it_stores_all_bag_fields_correctly_when_created(): void
    {
        // Controleert of ALLE ingevoerde data (naam, beschrijving, hex-kleuren) exact zo in de database belanden
        $id = Tas::create($this->pdo, 'Rode Tas', 'Mooie tas', 'uploads/rood.jpg', '#ff0000', '', '#ffffff', '#000000');
        $tas = Tas::getById($id, $this->pdo);

        $this->assertSame('Rode Tas', $tas['naam']);
        $this->assertSame('Mooie tas', $tas['beschrijving']);
        $this->assertSame('uploads/rood.jpg', $tas['afbeelding']);
        $this->assertSame('#ff0000', $tas['kleurcode']);
        $this->assertSame('#ffffff', $tas['tekst_kleur']);
        $this->assertSame('#000000', $tas['titel_kleur']);
    }

    /** @test */
    public function it_returns_null_when_a_bag_does_not_exist(): void
    {
        // Controleert of de functie netjes 'null' teruggeeft als je een ID opvraagt dat niet bestaat (voorkomt PHP crashes)
        $result = Tas::getById(99999, $this->pdo);
        $this->assertNull($result);
    }

    /** @test */
    public function it_returns_all_bags_in_the_overview(): void
    {
        // Voegt drie tassen toe en controleert of getAll() ook daadwerkelijk een count van exact 3 rijen teruggeeft
        $this->maakTestTas('Tas A');
        $this->maakTestTas('Tas B');
        $this->maakTestTas('Tas C');

        $tassen = Tas::getAll($this->pdo);
        $this->assertCount(3, $tassen);
    }

    /** @test */
    public function it_returns_an_empty_overview_when_no_bags_exist(): void
    {
        // Controleert of de applicatie een lege array teruggeeft in plaats van een error als de database nog helemaal leeg is
        $tassen = Tas::getAll($this->pdo);
        $this->assertIsArray($tassen);
        $this->assertEmpty($tassen);
    }

    /** @test */
    public function it_updates_the_bag_name_correctly(): void
    {
        // Maakt een tas aan, wijzigt de naam via de update-functie en checkt of de database daarna de nieuwe naam bevat
        $id = $this->maakTestTas('Oude Naam');
        Tas::update($this->pdo, $id, 'Nieuwe Naam', 'Beschrijving', 'uploads/foto.jpg', '#000000');

        $tas = Tas::getById($id, $this->pdo);
        $this->assertSame('Nieuwe Naam', $tas['naam']);
    }

    /** @test */
    public function it_returns_false_when_updating_a_bag_that_does_not_exist(): void
    {
        // Controleert of de update-functie 'false' teruggeeft als je een niet-bestaand ID probeert te updaten
        $result = Tas::update($this->pdo, 99999, 'Naam', 'Beschrijving', 'uploads/foto.jpg', '#000000');
        $this->assertFalse($result);
    }

    /** @test */
    public function it_deletes_a_bag_and_returns_the_image_path(): void
    {
        // Controleert of bij het verwijderen van een tas het oude afbeeldingspad wordt geretourneerd (zodat je het bestand van de schijf kunt wissen)
        // En controleert of de tas daarna ook echt verdwenen is uit de database
        $id = Tas::create($this->pdo, 'Weg Tas', 'bye', 'uploads/weg.jpg', '#000000');
        $pad = Tas::delete($id, $this->pdo);

        $this->assertSame('uploads/weg.jpg', $pad);
        $this->assertNull(Tas::getById($id, $this->pdo));
    }

    /** @test */
    public function it_returns_null_when_deleting_a_bag_that_does_not_exist(): void
    {
        // Controleert of de delete-functie veilig 'null' teruggeeft als je een tas probeert te wissen die al weg is of nooit heeft bestaan
        $result = Tas::delete(99999, $this->pdo);
        $this->assertNull($result);
    }


    // =========================================================================
    // GEBRUIKERS
    // =========================================================================

    /** @test */
    public function it_stores_the_admin_password_as_a_hash_and_not_as_plaintext(): void
    {
        // Cruciale security-test: Controleert of het wachtwoord NOOIT als platte tekst in de database staat,
        // maar veilig is omgezet via een bcrypt hash met password_verify()
        $id = $this->maakTestUser('test@test.nl', 'geheim123');
        $stmt = $this->pdo->prepare("SELECT wachtwoord FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $hash = $stmt->fetchColumn();

        $this->assertNotSame('geheim123', $hash);
        $this->assertTrue(password_verify('geheim123', $hash));
    }

    /** @test */
    public function it_returns_the_user_when_login_credentials_are_correct(): void
    {
        // Simuleert een succesvolle login: Checkt of de login-functie de juiste gebruikersgegevens teruggeeft bij het juiste wachtwoord
        $this->maakTestUser('login@test.nl', 'wachtwoord123');
        $user = User::findByCredentials('login@test.nl', 'wachtwoord123', $this->pdo);

        $this->assertNotNull($user);
        $this->assertSame('login@test.nl', $user['email']);
    }

    /** @test */
    public function it_returns_null_when_login_credentials_are_incorrect(): void
    {
        // Beveiligingstest: Controleert of de login faalt (null teruggeeft) zodra er een verkeerd wachtwoord wordt ingevoerd
        $this->maakTestUser('login@test.nl', 'juistwachtwoord');
        $result = User::findByCredentials('login@test.nl', 'fout', $this->pdo);

        $this->assertNull($result);
    }

    /** @test */
    public function it_deletes_an_admin_but_protects_the_owner(): void
    {
        // Rechten/Rollen check: Een normale 'admin' mag wel verwijderd worden,
        // maar de hoofdgebruiker met de rol 'owner' moet absoluut beschermd worden tegen verwijdering
        $adminId = $this->maakTestUser('admin@test.nl', 'pass');

        $this->pdo->exec("INSERT INTO users (email, wachtwoord, rol) VALUES ('owner@test.nl', 'x', 'owner')");
        $ownerId = (int) $this->pdo->lastInsertId();

        $this->assertTrue(User::delete($adminId, $this->pdo));
        $this->assertFalse(User::delete($ownerId, $this->pdo));
    }


    // =========================================================================
    // HULPFUNCTIES
    // =========================================================================

    /** @test */
    public function it_returns_white_text_on_dark_background_colors(): void
    {
        // Contrast-berekening test: Controleert of de functie besluit om WITTE tekst te tonen op een donkere achtergrond (#000000, #1a1a2e, #3b0764)
        $this->assertSame('text-white', Tas::getContrastColor('#000000'));
        $this->assertSame('text-white', Tas::getContrastColor('#1a1a2e'));
        $this->assertSame('text-white', Tas::getContrastColor('#3b0764'));
    }

    /** @test */
    public function it_returns_black_text_on_light_background_colors(): void
    {
        // Contrast-berekening test: Controleert of de functie besluit om ZWARTE tekst te tonen op een lichte achtergrond (#ffffff, #f0f0f0, #ffff00)
        $this->assertSame('text-black', Tas::getContrastColor('#ffffff'));
        $this->assertSame('text-black', Tas::getContrastColor('#f0f0f0'));
        $this->assertSame('text-black', Tas::getContrastColor('#ffff00'));
    }

    /** @test */
    public function it_calculates_contrast_color_correctly_without_a_hash_prefix(): void
    {
        // Robuustheidstest: Controleert of de contrast-functie ook vlekkeloos werkt als je teammate per ongeluk een hex-kleurcode zónder '#' invoert
        $this->assertSame('text-black', Tas::getContrastColor('ffffff'));
        $this->assertSame('text-white', Tas::getContrastColor('000000'));
    }
}
