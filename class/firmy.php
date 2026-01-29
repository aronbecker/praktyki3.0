<?php

include_once 'class/DBManager.php';
include_once 'class/komentarze.php';

class Firmy
{
    private DBManager $db;
    private string $table = 'firmy';

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
                Nip, Regon, NazwaPodmiotu, Nazwisko, Imie,
                Telefon, Email, AdresWWW, KodPocztowy,
                Powiat, Gmina, Miejscowosc, Ulica,
                NrBudynku, NrLokalu
            ) VALUES (
                :Nip, :Regon, :NazwaPodmiotu, :Nazwisko, :Imie,
                :Telefon, :Email, :AdresWWW, :KodPocztowy,
                :Powiat, :Gmina, :Miejscowosc, :Ulica,
                :NrBudynku, :NrLokalu
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
        $sql = "SELECT * FROM {$this->table} WHERE Lp = :id";
        $stmt = $this->db->query($sql, ['id' => $id]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY Lp";
        return $this->db->query($sql)->fetchAll();
    }

    public function getAllWithCategories(): array
    {
        $sql = "
            SELECT f.*, COALESCE(k.nazwa, 'Brak kategorii') AS kategoria
            FROM {$this->table} f
            LEFT JOIN firma_kategoria fk ON f.lp = fk.firma_lp
            LEFT JOIN kategorie k ON fk.kategoria_id = k.id
            ORDER BY f.NazwaPodmiotu
        ";
        return $this->db->query($sql)->fetchAll();
    }

    public function getByNip(string $nip): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE Nip = :nip";
        $stmt = $this->db->query($sql, ['nip' => $nip]);
        return $stmt->fetch() ?: null;
    }
    public function getLpByNip(string $nip): string
    {
        $sql = "SELECT Lp FROM {$this->table} WHERE Nip = :nip";
        $stmt = $this->db->query($sql, ['nip' => $nip]);
        $out = $stmt->fetch() ?: null;
        return implode('', $out);
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
            WHERE Lp = :id
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
        $sql = "DELETE FROM {$this->table} WHERE Lp = :id";
        $this->db->query($sql, ['id' => $id]);
        return true;
    }

    /* =======================
       HELPERS
    ======================= */

    public function existsByNip(string $nip): bool
    {
        $sql = "SELECT 1 FROM {$this->table} WHERE Nip = :nip LIMIT 1";
        return (bool)$this->db->query($sql, ['nip' => $nip])->fetch();
    }

    public function getWithStats(int $id): ?array
    {
        $firma = $this->getById($id);
        if (!$firma) {
            return null;
        }

        $komentarze = new Komentarze();
        $firma['srednia_ocena'] = $komentarze->getAverageRatingForFirma($id);
        $firma['ilosc_komentarzy'] = $komentarze->countForFirma($id);

        return $firma;
    }

}
