<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Repositories/UserRepository.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role_id = (int) $_POST['role_id'];

    $db = new Database();
    $userRepo = new UserRepository($db->getConnection());

    $created = $userRepo->createUser($username, $email, $password, $role_id);

    echo $created ? "Utilisateur créé avec succès" : "Erreur lors de la création de l'utilisateur";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un Utilisateur</title>
</head>
<body>
<h2>Créer un Utilisateur</h2>
<form method="POST" action="createUser.php">
    <label>Nom d'utilisateur:</label>
    <input type="text" name="username" required><br>

    <label>Email:</label>
    <input type="email" name="email" required><br>

    <label>Mot de passe:</label>
    <input type="password" name="password" required><br>

    <label>Rôle:</label>
    <select name="role_id" required>
        <option value="1">Admin</option>
        <option value="2">Modérateur</option>
        <option value="3">Auteur</option>
    </select><br>

    <input type="submit" value="Créer l'utilisateur">
</form>
</body>
</html>
