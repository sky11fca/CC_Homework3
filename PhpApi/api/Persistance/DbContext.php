<?php

namespace Persistance;

use PDO;

class DbContext
{
    private PDO $connection;

    /**
     * The constructor now accepts either a full DSN string (for Cloud SQL)
     * or the individual components (for local Docker). It immediately tries to connect.
     *
     * @param string $host_or_dsn The hostname or the full DSN string.
     * @param string|null $dbname The database name, optional if DSN is provided.
     * @param string|null $user The username, optional if DSN is provided.
     * @param string|null $password The password, optional if DSN is provided.
     */
    public function __construct(string $host_or_dsn, ?string $dbname, ?string $user, ?string $password)
    {
        try {
            // If the first argument contains ':', we assume it's a full DSN string.
            if (str_contains($host_or_dsn, ':')) {
                $dsn = $host_or_dsn;
            } else {
                // Otherwise, we build the DSN for a standard host/port connection.
                $dsn = "mysql:host=$host_or_dsn;dbname=$dbname";
            }

            $this->connection = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false, // Good practice for security
            ]);
        } catch (\PDOException $e) {
            http_response_code(500);
            // Avoid echoing detailed error messages in production. Log them instead.
            echo json_encode(["message" => "Database connection failed."]);
            exit();
        }
    }

    public function connect(): PDO
    {
        return $this->connection;
    }
}