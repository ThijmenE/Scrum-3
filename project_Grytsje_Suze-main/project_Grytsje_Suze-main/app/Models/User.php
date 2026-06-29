<?php
namespace App\Models;

use App\Core\Database;

class User
{
    public static function getAll(?\PDO $pdo = null): array
    {
        $pdo = $pdo ?? Database::getInstance();
        return $pdo->query("SELECT id, email, rol, created_at FROM users ORDER BY rol DESC")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function create(string $email, string $wachtwoord, ?\PDO $pdo = null): int
    {
        $pdo = $pdo ?? Database::getInstance();
        $hash = password_hash($wachtwoord, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (email, wachtwoord, rol) VALUES (?, ?, 'admin')");
        $stmt->execute([$email, $hash]);
        return (int) $pdo->lastInsertId();
    }

    public static function delete(int $id, ?\PDO $pdo = null): bool
    {
        $pdo = $pdo ?? Database::getInstance();
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND rol != 'owner'");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    public static function findByCredentials(string $email, string $wachtwoord, ?\PDO $pdo = null): ?array
    {
        $pdo = $pdo ?? Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($user && password_verify($wachtwoord, $user['wachtwoord'])) {
            return $user;
        }
        return null;
    }
}
