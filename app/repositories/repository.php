<?php

namespace Repositories;

use PDO;
use PDOException;

class Repository
{

  protected $connection;

  function __construct()
  {

    require __DIR__ . '/../dbconfig.php';

    try {
      $this->connection = new PDO("$type:host=$servername;dbname=$database", $username, $password);

      // set the PDO error mode to exception
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      error_log('Connection failed: ' . $e->getMessage());
      throw new \Exception('Connection failed');
    }
  }

  /**
 * Begins a new transaction
 * @return void
 * @throws \Exception
 * @author Luko Pecotic
 */
  public function beginTransaction()
  {
    $this->connection->beginTransaction();
  }

  /**
   * Commits the current transaction
   * @return void
   * @throws \Exception
   * @author Luko Pecotic
   */
  public function commit()
  {
    $this->connection->commit();
  }

  /**
   * Rolls back the current transaction
   * @return void
   * @throws \Exception
   * @author Luko Pecotic
   */
  public function rollBack()
  {
    $this->connection->rollBack();
  }
}
