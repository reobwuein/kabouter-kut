<?php namespace Kutkabouter;

/**
 * Class Db
 *
 * This class is responsible for database queries
 */
class Db
{
    protected $conn;

    public function __construct()
    {
        //$this->conn = new \mysqli('localhost', 'u1004963_kut', '#3@/+?.zWs', 'db1004963_kut');
        $this->conn = new \mysqli('localhost', 'kutkabouter', 'root', 'kutkabouter');
        if (mysqli_connect_errno())
            die(mysqli_connect_error());
    }

    public function query($sql)
    {
        return $this->conn->query($sql);
    }

    public function prepare($sql)
    {
        $stmt = $this->conn->prepare($sql);
        if (! $stmt)
            die(mysqli_error($this->conn));

        return $stmt;
    }

    public function __destruct()
    {
        $this->conn->close();
    }
} 