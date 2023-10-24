<?php

namespace Models;

use PDO;
use PDOException;
use Models\FlashMessageTrait;

class Data
{
    use FlashMessageTrait;

    private PDO $connect;
    private string $table = 'zaznamy';

    public function __construct($connect)
    {
        $this->connect = $connect;
    }

    public function getData(): ?array
    {
        $sql = "SELECT * FROM $this->table";

        try {
            $data = $this->connect->query($sql)->fetchAll();

            if (empty($data)) {
                self::setFlash('info', 'table is empty');
                return null;
            }
            self::setFlash('success', 'data imported');
            return $data;
        } catch (PDOException $e) {
            self::setFlash('error', $e->getMessage());
            return null;
        }
    }

    public function deleteData(): void
    {
        try {
            $sql = "DELETE FROM $this->table";
            $this->connect->exec($sql);
            self::setFlash('success', 'All data deleted from the table.');
        } catch (PDOException $e) {
            self::setFlash('error', $e->getMessage());
        }
    }
}
