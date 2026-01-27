<?php
include_once 'class/DBManager.php';
class Komentarze
{
    private DBManager $db;
    private string $table = 'komentarze';

    public function __construct()
    {
        $this->db = DBManager::getInstance();
    }

    /* =======================
       CREATE
    ======================= */

    public function create(array $data): int
    {
        $sql = "
            INSERT INTO {$this->table} (
                tresc, autor, user_id, firma_lp, ocena
            ) VALUES (
                :tresc, :autor, :user_id, :firma_lp, :ocena
            )
        ";

        $this->db->query($sql, $data);
        return (int)$this->db->lastInsertId();
    }

    /* =======================
       READ
    ======================= */

    public function getById(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->query($sql, ['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function getByFirma(int $firmaLp): array
    {
        $sql = "
            SELECT *
            FROM {$this->table}
            WHERE firma_lp = :firma_lp
            ORDER BY data_utworzenia DESC
        ";

        return $this->db
            ->query($sql, ['firma_lp' => $firmaLp])
            ->fetchAll();
    }

    public function getByUser(int $userId): array
    {
        $sql = "
            SELECT *
            FROM {$this->table}
            WHERE user_id = :user_id
            ORDER BY data_utworzenia DESC
        ";

        return $this->db
            ->query($sql, ['user_id' => $userId])
            ->fetchAll();
    }

    /* =======================
       UPDATE
    ======================= */

    public function update(int $id, array $data): bool
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }

        $sql = "
            UPDATE {$this->table}
            SET " . implode(', ', $fields) . ",
                data_mod = NOW()
            WHERE id = :id
        ";

        $data['id'] = $id;
        $this->db->query($sql, $data);
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
       HELPERS / STATS
    ======================= */

    public function getAverageRatingForFirma(int $firmaLp): float
    {
        $sql = "
            SELECT AVG(ocena) AS avg_rating
            FROM {$this->table}
            WHERE firma_lp = :firma_lp
        ";

        $result = $this->db
            ->query($sql, ['firma_lp' => $firmaLp])
            ->fetch();

        return round((float)$result['avg_rating'], 2);
    }

    public function countForFirma(int $firmaLp): int
    {
        $sql = "
            SELECT COUNT(*) AS cnt
            FROM {$this->table}
            WHERE firma_lp = :firma_lp
        ";

        $result = $this->db
            ->query($sql, ['firma_lp' => $firmaLp])
            ->fetch();

        return (int)$result['cnt'];
    }

    public function userHasCommented(int $firmaLp, int $userId): bool
    {
        $sql = "
            SELECT 1
            FROM {$this->table}
            WHERE firma_lp = :firma_lp
              AND user_id = :user_id
            LIMIT 1
        ";

        return (bool)$this->db->query($sql, [
            'firma_lp' => $firmaLp,
            'user_id'  => $userId
        ])->fetch();
    }
}
