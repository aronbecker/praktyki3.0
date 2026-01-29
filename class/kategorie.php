<?php
include_once 'class/DBManager.php';
include_once 'class/firmy.php';

class Kategorie
{
    private DBManager $db;
    private string $table = 'kategorie';

    public function __construct()
    {
        $this->db = DBManager::getInstance();
    }

    /* =======================
       CREATE
    ======================= */

    public function create(string $nazwa, int $id_nad = 0): int
    {
        $sql = "
            INSERT INTO {$this->table} (nazwa, id_nad)
            VALUES (:nazwa, :id_nad)
        ";

        $this->db->query($sql, [
            'nazwa' => $nazwa,
            'id_nad' => $id_nad
        ]);

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

    public function getAll(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY nazwa";
        return $this->db->query($sql)->fetchAll();
    }

    public function getByParent(int $id_nad): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id_nad = :id_nad ORDER BY nazwa";
        return $this->db->query($sql, ['id_nad' => $id_nad])->fetchAll();
    }

    public function getByName(string $nazwa): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE nazwa = :nazwa";
        $stmt = $this->db->query($sql, ['nazwa' => $nazwa]);
        return $stmt->fetch() ?: null;
    }
    public function getByPKD(mixed $pkd): mixed
    {
        $sql = "SELECT nazwa FROM pkd_kategorie WHERE pkd REGEXP :pkd";
        $stmt = $this->db->query($sql, ['pkd' => $pkd]);
        $name = $stmt->fetch() ?: null;
        if ((gettype($name)) == 'string'){
            return $name;
        } else if ((gettype($name)) == 'array') {
            return implode('', $name);
        }else{
            return 'string haha';
        }
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
            SET " . implode(', ', $fields) . "
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
       RELATIONS
    ======================= */

    public function assignToCompany(int $categoryId, int $companyLp): bool
    {
        $sql = "INSERT INTO firma_kategoria (firma_lp, kategoria_id) VALUES (:firma_lp, :kategoria_id)";
        $this->db->query($sql, [
            'firma_lp' => $companyLp,
            'kategoria_id' => $categoryId
        ]);
        return true;
    }

    public function assignToCompanyByName(string $categoryName, int $companyLp): bool
    {
        $category = $this->getByName($categoryName);
        if (!$category) {
            throw new InvalidArgumentException("Kategoria '$categoryName' nie istnieje");
        }
        return $this->assignToCompany($category['id'], $companyLp);
    }
    public function assignToCompanyByPKD(string $nip, string $pkd) {
        $categoryID = $this->getByPKD($pkd);
        $firma = new Firmy();
        $firmaLp = $firma->getLpByNip($nip);
        return $this->assignToCompanyByName($categoryID, $firmaLp);
    }
}