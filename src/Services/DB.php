<?php
namespace SAmvc\Services;
use SAmvc\Env;

class DB
{

  public static function init()
  {
    return new PDO(Env::get('db.type').':host='.Env::get('db.host').':'.Env::get('db.port').';dbname='.Env::get('db.name').';charset=utf8', Env::get('db.user'), Env::get('db.pass'));
  }

  /**
  * select
  * @param string $sql An SQL string
  * @param array $array Paramters to bind
  * @param constant $fetchMode A PDO Fetch mode
  * @return mixed
  */
  public static function select($sql, $array = array(), $fetchMode = PDO::FETCH_ASSOC)
  {
    $sth = self::init()->prepare($sql);
    foreach ($array as $key => $value) {
      $sth->bindValue("$key", $value);
    }

    $sth->execute();
    return $sth->fetchAll($fetchMode);
  }

  /**
  * select
  * @param string $sql An SQL string
  * @param array $array Paramters to bind
  * @param constant $fetchMode A PDO Fetch mode
  * @return single row or false
  */
  public static function selectSingle($sql, $array = array(), $fetchMode = PDO::FETCH_ASSOC)
  {
    $sth = self::init()->prepare($sql);
    foreach ($array as $key => $value) {
      $sth->bindValue("$key", $value);
    }

    $sth->execute();
    return $sth->fetch($fetchMode);
  }

  /**
  * insert
  * @param string $table A name of table to insert into
  * @param string $data An associative array
  */
  public static function insert($table, $data)
  {
    ksort($data);

    $fieldNames = implode('`, `', array_keys($data));
    $fieldValues = ':' . implode(', :', array_keys($data));

    $sth = self::init()->prepare("INSERT INTO $table (`$fieldNames`) VALUES ($fieldValues)");

    foreach ($data as $key => $value) {
      $sth->bindValue(":$key", $value);
    }

    $sth->execute();
  }

  /**
  * update
  * @param string $table A name of table to insert into
  * @param string $data An associative array
  * @param string $where the WHERE query part
  */
  public static function update($table, $data, $where)
  {
    ksort($data);

    $fieldDetails = NULL;
    foreach($data as $key=> $value) {
      $fieldDetails .= "`$key`=:$key,";
    }
    $fieldDetails = rtrim($fieldDetails, ',');

    $sth = self::init()->prepare("UPDATE $table SET $fieldDetails WHERE $where");

    foreach ($data as $key => $value) {
      $sth->bindValue(":$key", $value);
    }

    $sth->execute();
  }

  /**
  * update
  * @param string $table A name of table to insert into
  * @param string $data An associative array
  * @param string $where the WHERE query part
  */
  public static function updateAll($table, $data)
  {
    ksort($data);

    $fieldDetails = NULL;
    foreach($data as $key=> $value) {
      $fieldDetails .= "`$key`=:$key,";
    }
    $fieldDetails = rtrim($fieldDetails, ',');

    $sth = self::init()->prepare("UPDATE $table SET $fieldDetails");

    foreach ($data as $key => $value) {
      $sth->bindValue(":$key", $value);
    }

    $sth->execute();
  }

  /**
  * delete
  *
  * @param string $table
  * @param string $where
  * @param integer $limit
  * @return integer Affected Rows
  */
  public static function delete($table, $where, $limit = 999999)
  {
    return self::init()->exec("DELETE FROM $table WHERE $where LIMIT $limit");
  }

}
