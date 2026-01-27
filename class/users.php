<?php
include_once 'class/DBManager.php';
class Users
{
    private DBManager $db;
    private string $table = 'users';

    public function __construct()
    {
        $this->db = DBManager::getInstance();
    }

    /* =======================
       CREATE
    ======================= */

    public function create(string $login, string $password, string $email, bool $admin = false): int
    {
        $sql = "
            INSERT INTO {$this->table} (login, pass, email, admin)
            VALUES (:login, :pass, :email, :admin)
        ";

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $this->db->query($sql, [
            'login' => $login,
            'pass'  => $hash,
            'email' => $email,
            'admin' => $admin ? 1 : 0
        ]);

        return (int)$this->db->lastInsertId();
    }

    /* =======================
       READ
    ======================= */

    public function getById(int $id): ?array
    {
        $sql = "
            SELECT id, login, email, pass, admin
            FROM {$this->table}
            WHERE id = :id
        ";

        $stmt = $this->db->query($sql, ['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function getByLogin(string $login): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE login = :login";
        $stmt = $this->db->query($sql, ['login' => $login]);
        return $stmt->fetch() ?: null;
    }

    public function getAll(): array
    {
        $sql = "SELECT id, login, email, admin FROM {$this->table}";
        return $this->db->query($sql)->fetchAll();
    }

    /* =======================
       AUTH
    ======================= */

    public function authenticate(string $login, string $password): ?array
    {
        $user = $this->getByLogin($login);
        if (!$user) {
            return null;
        }

        if ($password != $user['pass']) {
            return null;
        }

        unset($user['pass']);
        return $user;
    }

    /* =======================
       UPDATE
    ======================= */

    public function update(int $id, array $data): bool
    {
        if (empty($data)) {
            throw new InvalidArgumentException('Brak danych do aktualizacji');
        }

        // dozwolone pola
        $allowed = ['login', 'email', 'pass', 'admin'];

        $filtered = array_intersect_key($data, array_flip($allowed));

        if (empty($filtered)) {
            throw new InvalidArgumentException('Brak dozwolonych pól do aktualizacji');
        }

        // if (isset($filtered['pass'])) {
        //     $filtered['pass'] = password_hash($filtered['pass'], PASSWORD_DEFAULT);
        // }

        $fields = [];
        foreach ($filtered as $key => $value) {
            $fields[] = "$key = :$key";
        }

        $sql = "
            UPDATE {$this->table}
            SET " . implode(', ', $fields) . "
            WHERE id = :user_id
        ";

        $filtered['user_id'] = $id;

        $this->db->query($sql, $filtered);
        return true;
    }

    /* =======================
       DELETE
    ======================= */

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $this->db->query($sql, ['id' => $id]);
        return true;
    }

    /* =======================
       RELATIONS
    ======================= */

    public function getComments(int $userId): array
    {
        $komentarze = new Komentarze();
        return $komentarze->getByUser($userId);
    }

    /* =======================
       HELPERS
    ======================= */

    public function isAdmin(int $userId): bool
    {
        $sql = "SELECT admin FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->query($sql, ['id' => $userId]);
        return (bool)($stmt->fetch()['admin'] ?? false);
    }
}
