<?php

namespace Models;

use PDO;
use Exception;
use PDOException;
use Models\FlashMessageTrait;

class Import extends ConnectDB
{
    use FlashMessageTrait;

    private $data;
    private PDO $connect;
    private string $table = 'zaznamy';

    private string $json = 'https://www.3it.cz/test/data/json';

    public function __construct($connect)
    {
        $this->connect = $connect;
    }

    public function getJson(): ?array
    {
        try {
            $jsonData = file_get_contents($this->json);

            if ($jsonData !== false) {

                $this->data = json_decode($jsonData, true);

                if ($this->data !== null) {
                    self::setFlash('success', 'JSON data decoded');
                    return $this->data;
                } else {
                    self::setFlash('fail', 'Failed to decode JSON data');
                    return null;
                }
            }

            self::setFlash('fail', 'Failed to retrieve data from the URL');
            return null;
        } catch (Exception $e) {
            self::setFlash('ERROR', $e->getMessage());
            return null;
        }
    }

    public function saveToDb()
    {
        try {
            $dataArray    = $this->getJson();

            if (empty($dataArray)) {
                self::setFlash('info', 'No data to insert.');
                return;
            }

            $query        = "INSERT INTO zaznamy (id, jmeno, prijmeni, datum) VALUES ";

            foreach ($dataArray as $data) {

                $id       = htmlspecialchars($data['id']);
                $jmeno    = htmlspecialchars($data['jmeno']);
                $prijmeni = htmlspecialchars($data['prijmeni']);
                $datum    = htmlspecialchars($data['date']);

                $query .= "('$id', '$jmeno', '$prijmeni', '$datum'), ";
            }

            $query = rtrim($query, ', ');
            $stmt = $this->connect->prepare($query);
            $stmt->execute();

            self::setFlash('success', 'Data inserted to table \'zaznamy\' successfully.');
        } catch (PDOException $e) {
            self::setFlash('fail', $e->getMessage());
            return null;
        }
    }
}
