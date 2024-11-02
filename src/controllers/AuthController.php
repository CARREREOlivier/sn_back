<?php
declare(strict_types=1);

require_once __DIR__ . '/../Repositories/UserRepository.php';

class AuthController {
    private $userRepository;

    public function __construct() {
        $db = new Database();
        $this->userRepository = new UserRepository($db->getConnection());
    }

    public function login(string $username, string $password, bool $rememberMe): array {
        try {
            // Recherche l'utilisateur par son nom
            $user = $this->userRepository->findByUsername($username);

            // Vérifie si l'utilisateur existe et si le mot de passe est correct
            if (!$user || !password_verify($password, $user['password'])) {
                return ['status' => 'error', 'message' => 'Nom d\'utilisateur ou mot de passe incorrect'];
            }

            // Enregistrement de l'ID de l'utilisateur dans la session
            session_start();
            $_SESSION['user_id'] = $user['id'];

            // Gestion du "Se souvenir de moi"
            if ($rememberMe) {
                setcookie('user_id', $user['id'], time() + (86400 * 30), "/");
            }

            return ['status' => 'success', 'message' => 'Connexion réussie'];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => 'Erreur lors de la connexion : ' . $e->getMessage()];
        }
    }

    public function logout(): void {
        session_start();
        session_unset();
        session_destroy();
        setcookie(session_name(), '', time() - 3600, '/');
        echo json_encode(['status' => 'success', 'message' => 'Déconnexion réussie']);
        exit;
    }
}
?>
