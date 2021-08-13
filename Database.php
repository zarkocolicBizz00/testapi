<?php

class Database{

    private $hostname = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname;
    private $dblink;
    private $results;
    private $affected;
    private $records;

    //konstruktor
    function __construct($dbname){
        $this ->dbname = $dbname;
        $this->Connect();
    }

    //konakcija sa bazom
    
    function Connect(){
        $this -> dblink = new mysqli($this->hostname,$this->username,$this->password,$this->dbname);
        //errno fja koja vraca true ili false u zavisnosti od uspesnosti konekcije sa bazom
        if($this->dblink->connect_errno){
            printf("Konekcija je neuspesna %s\n", $this->dblink->connec_error);
            exit();
        }
        $this->dblink->set_charset("utf8");
    }

    //funkcija za izvrsavanje kverija tj upita

    function ExecuteQuery($query){
        $this->result = $this->dblink->query($query);
        if($this->result){
            if(isset($this->result->num_rows)){
                $this->records = $this->result->num_rows;
            }
            if(isset($this->result->affected_rows)){
                $this->affected = $this->result->affected_rows;
            }
        }
    }

    function getResult(){
        return $this->result;
    }

    //SELECT fja

    function select($table = "novosti", $rows = "*", $join_table="kategorije", $join_key1 = "kategorija_id", $join_key2="id", $where = null, $order=null){
        $q = 'SELECT '.$rows.' FROM '.$table;
        //SELECT * FROM novosti

        if($join_table!=null){
            $q.=' JOIN '.$join_table.' ON '.$table.'.'.$join_key1.'='.$join_table.'.'.$join_key2;
            //SELECT * FROM novosti JOIN kategorije ON novosti.kategorija_id = kategorije.id
        }
        if($where!=null){
            $q.=' WHERE '.$where;
        }
        if($order!=null){
            $q.=' ORDER BY '.$order;
        }

        $this->ExecuteQuery($q);

    }

    function insert($table = "novosti", $rows = "naslov, tekst, kategorija_id, datumvreme", $values){
        $query_values = implode(',',$values);
        $q.= 'INSERT INTO '.$table;
        if($rows!=null){
            $q.='('.$rows.')';
            //INSERT INTO novosti(naslov, tekst, kategorija_id, datumvreme);
        }

        $q.= 'VALUES ('.$query_values.')';

        if($this->ExecuteQuery($q)){
            return true;
        }else{
            return false;
        }

    }




}

?>