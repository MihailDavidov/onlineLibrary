<?php

class Database
{
	private static $instance;
	
    public $last_insert_id;

    private $link = false;

	private function __construct()
    {
    	$dbHost = "localhost"; 
		$dbName = "online_library";
		$db_user = "root";
		$db_pass = "";
		try {
			$this->link = new PDO("mysql:dbname={$dbName};host={$dbHost}", $db_user, $db_pass);
			$this->link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
		} catch (PDOException $e) {
			throw $e;
		}
    }

	protected function getInstance()
    {
		if (isset(self::$instance)) {
            $instance = self::$instance;
        } else {
            $instance = new Database();
            self::$instance = $instance;
        }

        return $instance;
    }

    function ExecuteSelectRow($sql, $bindParams = array())
    {
        $sth = $this->Execute($sql, $bindParams);
        if ($sth) {
            return $sth->fetch();
        }
        return;
    }

    function ExecuteSelectArray($sql, $bindParams = array())
    {
        $sth = $this->Execute($sql, $bindParams);

        if ($sth) {
            return $sth->fetchAll(PDO::FETCH_ASSOC);
        }
        return;
    }

    function getSqlValue($sql, $bindParams = array())
    {
        $sth = $this->Execute($sql, $bindParams);
        if ($sth) {
            return $sth->fetch(PDO::FETCH_COLUMN, 0);
        }
        return;
    }

    function Execute($sql, $bindParams = array())
    {
		$sth = $this->link->prepare($sql);


        foreach ($bindParams as $column => $value) {
            if ($value === 'NULL') {
                $bindParams[$column] = null;
            } elseif (empty($value)) {
                $bindParams[$column] = '';
            }
        }
        try {
            $result = $sth->execute($bindParams);
            if(!$result) {
				throw new PDOException();
			}
        } catch (PDOException $e) {
			throw $e;
		}

        if ($this->link->lastInsertId()) {
            $this->last_insert_id = $this->link->lastInsertId();
        }
        return $sth;
    }
}
