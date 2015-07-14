<?php

class Database
{
	private static $instance;
	
    public $last_insert_id;

    private $link = false;

	//tova e t.nar. singleton (design pattern) 
	//ideiata na singleton e da se suzdade edna instantsia na klasa i da se izpolzva vinagi tq, kato se izvikva klasut.
	//tova stava kato se napravi private constructor, method getInstance, koito da izvikva constructura i statichen data member
	private function __construct()
    {
		//tezi promenlivi mogat da se slojat kato constanti v config.php, koito da se izvikva v init.php.
    	$dbHost = "localhost"; 
		$dbName = "online_library";
		$db_user = "root";
		$db_pass = "";
		try {
			//tova pravi vruzkata s bazata danni i zapazva vruzkata v data member-a $link.
			$this->link = new PDO("mysql:dbname={$dbName};host={$dbHost}", $db_user, $db_pass);
			$this->link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
		} catch (PDOException $e) {
			throw $e;
		}
    }
	
    //tozi klas proveriava dali e setnata statichnata promenliva $instance i, ako e setnata, q vrushta.
    //ako ne e setnata izvikva constructura i zapazva novia object v statichnia data member $instance, za da moje da se preizpolzva.
    
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

	//tozi method se vika, kogato select zaivkata triabva da vurne samo edin red.
    function ExecuteSelectRow($sql, $bindParams = array())
    {
        $sth = $this->Execute($sql, $bindParams);
        if ($sth) {
            return $sth->fetch();
        }
        return;
    }
	//tozi method se vika, kogato select zaiavkata vrushta mnogo redove.
    function ExecuteSelectArray($sql, $bindParams = array())
    {
        $sth = $this->Execute($sql, $bindParams);

        if ($sth) {
            return $sth->fetchAll(PDO::FETCH_ASSOC);
        }
        return;
    }
	//tozi method se vika, kogato select zaiavkata tiabva da vurene edna stoinost (po dadeno id da se vurne samo imeto na knigata)
    function getSqlValue($sql, $bindParams = array())
    {
        $sth = $this->Execute($sql, $bindParams);
        if ($sth) {
            return $sth->fetch(PDO::FETCH_COLUMN, 0);
        }
        return;
    }

	//tazi function se izpolza za vsichki zaiavki ot tip insert, update, delete
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
