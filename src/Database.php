<?php

/**
 * Class Database
 */
class Database
{

    /**
     * @var PDO
     */
    public $connection;

    /**
     * Database constructor.
     */
    public function __construct()
    {
        $this->connection = new PDO(sprintf('sqlite:%s',realpath(__ROOT__ . '/database/db.sqlite3')));
        $this->connection->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @return mixed
     */
    public function listItems()
    {
        $sql = 'SELECT name, age FROM test';

        return $this->connection->query($sql)->fetchAll();
    }

    /**
     * @param $id
     * @param $name
     * @param $age
     */
    public function editItem($id, $name, $age)
    {
        $sql = 'UPDATE test SET name=?, age=? WHERE id=?';
        $statement = $this->connection->prepare($sql);
        $statement->execute([$name, $age, $id]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function viewItem($id)
    {
        $sql = 'SELECT name, age FROM test WHERE id = ? LIMIT 1';
        $statement = $this->connection->prepare($sql);
        $statement->execute([$id]);

        return $statement->fetch();
    }

    /**
     * @param $name
     * @param $age
     */
    public function addItem($name, $age)
    {
        $sql = 'INSERT INTO test SET name=:name, age=:age';
        $q = $this->connection->prepare($sql);
        $q->execute([':name' => $name, ':age' => $age]);
    }

    /**
     * @param $email
     * @return mixed
     */
    public function getUserByEmail($email)
    {
        $sql = 'SELECT id, name, email, password FROM user WHERE email = ? LIMIT 1';
        $statement = $this->connection->prepare($sql);
        $statement->execute([$email]);

        return $statement->fetch();
    }

    /**
     * @param $email
     * @param $passwordHash
     * @param string $name
     * @return bool
     */
    public function registerUser($email, $passwordHash, $name = 'unknown')
    {
        $sql = 'INSERT INTO user (email, password, name) VALUES (:email, :password, :name)';
        try {
            $q = $this->connection->prepare($sql);
            $q->execute([':email' => $email, ':password' => $passwordHash, ':name' => $name]);
        } catch (Exception $e) {
            App::log($e->getMessage());

            return false;
        }

        return true;

    }
}
