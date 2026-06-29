<?php
namespace App\Models;

use App\Core\Database;

class Tas
{
    public static function getAll(?\PDO $pdo = null): array
    {
        $pdo = $pdo ?? Database::getInstance();
        return $pdo->query("SELECT * FROM tassen ORDER BY created_at DESC")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getById(int $id, ?\PDO $pdo = null): ?array
    {
        $pdo = $pdo ?? Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM tassen WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    public static function create(
        ?\PDO  $pdo,
        string $naam,
        string $beschrijving,
        string $afbeelding,
        string $kleurcode,
        string $model3d    = '',
        string $tekstKleur = '#000000',
        string $titelKleur = '#000000'
    ): int {
        $pdo = $pdo ?? Database::getInstance();
        $stmt = $pdo->prepare("
            INSERT INTO tassen (naam, beschrijving, afbeelding, kleurcode, model_3d, tekst_kleur, titel_kleur)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$naam, $beschrijving, $afbeelding, $kleurcode, $model3d, $tekstKleur, $titelKleur]);
        return (int) $pdo->lastInsertId();
    }

    public static function update(
        ?\PDO  $pdo,
        int    $id,
        string $naam,
        string $beschrijving,
        string $afbeelding,
        string $kleurcode,
        string $model3d    = '',
        string $tekstKleur = '#000000',
        string $titelKleur = '#000000'
    ): bool {
        $pdo = $pdo ?? Database::getInstance();
        $stmt = $pdo->prepare("
            UPDATE tassen
            SET naam=?, beschrijving=?, afbeelding=?, kleurcode=?, model_3d=?, tekst_kleur=?, titel_kleur=?
            WHERE id=?
        ");
        $stmt->execute([$naam, $beschrijving, $afbeelding, $kleurcode, $model3d, $tekstKleur, $titelKleur, $id]);
        return $stmt->rowCount() > 0;
    }

    public static function delete(int $id, ?\PDO $pdo = null): ?string
    {
        $pdo = $pdo ?? Database::getInstance();
        $stmt = $pdo->prepare("SELECT afbeelding FROM tassen WHERE id = ?");
        $stmt->execute([$id]);
        $tas = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$tas) {
            return null;
        }
        $pdo->prepare("DELETE FROM tassen WHERE id = ?")->execute([$id]);
        return $tas['afbeelding'];
    }

    public static function getContrastColor(string $hexColor): string
    {
        $hex = ltrim($hexColor, '#');
        $r   = hexdec(substr($hex, 0, 2));
        $g   = hexdec(substr($hex, 2, 2));
        $b   = hexdec(substr($hex, 4, 2));
        $yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
        return ($yiq >= 128) ? 'text-black' : 'text-white';
    }
}
