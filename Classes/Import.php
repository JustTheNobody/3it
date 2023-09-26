<?php

namespace Classes;

use PDO;
use PDOException;

class Import extends ConnectDB
{
    private $data;

    public function __construct($host, $user, $password)
    {
        parent::__construct($host, $user, $password);
    }

    /**
     * Gt json data from external URL
     *
     * @param [type] $url
     * @return array
     */
    public function getJson( $url )
    {
        //fetch the JSON data from URL
        $jsonData = file_get_contents($url);

        if ($jsonData !== false) {
            // Now $jsonData contains the JSON data from the URL
            $this->data = json_decode($jsonData, true); // Decode JSON into array
            if ($this->data !== null) {
                return $this->data;
            } else {
                return [ "fail", "Failed to decode JSON data"];
            }
        } else {
            return [ "fail", "Failed to retrieve data from the URL"];
        }
    }

    /**
     * Get json data from external URL
     *
     * @param [type] $url
     * @return array
     */
    public function saveToDb($url)
    {
        try {
            //make connection to DB
            $conn = $this->makeConnection();

            //get the data
            $dataArray = $this->getJson( $url );

            // Prepare the SQL
            $stmt = $conn->prepare("INSERT INTO zaznamy (jmeno, prijmeni, datum) VALUES (:jmeno, :prijmeni, :datum)");

            foreach ($dataArray as $data) {
                // Bind parameters
                $stmt->bindParam(':jmeno', $data['jmeno']);
                $stmt->bindParam(':prijmeni', $data['prijmeni']);
                $stmt->bindParam(':datum', $data['date']); // Change ':date' to ':datum'

                // Execute the query
                $stmt->execute();
            }

           return ["success", "Data inserted to table 'zaznamy' successfully."];
        } catch (PDOException $e) {
            return ["fail", "Error: " . $e->getMessage()];
        }
        // Close the database connection
        $conn = null;
    }
}