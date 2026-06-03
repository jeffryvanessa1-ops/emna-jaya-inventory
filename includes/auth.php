<?php

require_once __DIR__ . '/helpers.php';

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function require_login(): void
{
    if (!current_user()) {
        redirect('login.php');
    }
}

function has_role(array $roles): bool
{
    $user = current_user();

    if (!$user) {
        return false;
    }

    if (
        isset($user['role']) &&
        strtolower(trim($user['role'])) === 'admin'
    ) {
        return true;
    }

    return in_array(
        trim($user['role']),
        $roles,
        true
    );
}

function require_role(array $roles): void
{
    require_login();

    if (!has_role($roles)) {
        http_response_code(403);
        exit('403 Forbidden');
    }
}

function login_user(string $username, string $password): bool
{
    $stmt = db()->prepare(
        'SELECT users.*, roles.name AS role_name
         FROM users
         JOIN roles ON roles.id = users.role_id
         WHERE username = ?
           AND is_active = 1
         LIMIT 1'
    );

    $stmt->execute([$username]);

    $user = $stmt->fetch();

    if (!$user) {
        return false;
    }

    

    session_regenerate_id(true);

    $_SESSION['user'] = [
        'id'       => (int)$user['id'],
        'name'     => $user['name'],
        'username' => $user['username'],
        'role'     => $user['role_name']
    ];

    try {

        db()->prepare(
            'UPDATE users
             SET last_login_at = NOW()
             WHERE id = ?'
        )->execute([$user['id']]);

    } catch (Throwable $e) {
    }

    return true;
}

function logout_user(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {

        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}
?>
