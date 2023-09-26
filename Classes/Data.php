<?php

namespace Classes;

use PDO;
use PDOException;

class Data extends ConnectDB
{
    public function __construct($host, $user, $password)
    {
        parent::__construct($host, $user, $password);
    }

    /**
     * Get data from DB
     *
     * @return array
     */
    public function getData( $sort = null )
    {
        try {
            // Make a connection to DB
            $conn = $this->makeConnection();

            // Build the SQL query with optional sorting
            $sql = "SELECT * FROM zaznamy";

            if ($sort) {
                // Validate the $sort parameter to prevent SQL injection
                $validSortOptions = ["id", "jmeno", "prijmeni", "datum"];
                if (in_array($sort, $validSortOptions)) {
                    $sql .= " ORDER BY $sort";
                } else {
                    // Handle invalid sort options
                    return ["fail", "Invalid sort option"];
                }
            }
            // Prepare and execute the SELECT query
            $stmt = $conn->query($sql);

            // Fetch data as an associative array
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["fail", "Error: " . $e->getMessage()];
        }
    }

    public function deleteData(){
        try {
            // Make a connection to DB
            $conn = $this->makeConnection();
            // Define the table name
            $table = "zaznamy"; // Replace with your table name

            // Delete all data from the table
            $sql = "DELETE FROM $table";
            $conn->exec($sql);

            return ["success", "All data deleted from the table successfully."];
        } catch (PDOException $e) {
            return ["fail", "Error: " . $e->getMessage()];
        }
        // Close the database connection
        $conn = null;
    }
}