<?php


class Conexion
{

    private $host = "localhost";
    private $database = "osp_sms";
    private $username = "root";    
    private $password = "Osp201$";    
    private $link;
    private $result;
    public $sql;

    function __construct($database = null)
    {
        if (!empty($database)) {
            $this->database = $database;
        }
        $this->link = mysql_connect($this->host, $this->username,
            $this->password);
        mysql_select_db($this->database, $this->link);
        return $this->link;
    }

    function EjecutarSQL($sql)
    {
        if (!empty($sql)) {
            $this->sql = $sql;
            $this->result = mysql_query($sql, $this->link) or die(mysql_error() . 
                "error en EJECUTAR" . $sql);
            return $this->result;
        } else {
            return false;
        }
    }

    function fetch($query = "")
    {
        if (empty($query)) {
            $query = $this->result;
        }
        return mysql_fetch_array($query);
    }
    function fetch_asoc($query = "")
    {
        if (empty($query)) {
            $query = $this->result;
        }
        return mysql_fetch_assoc($query);
    }

    function fetchField($row, $col,$query = null)
    {
        if (empty($query)) {
            $query = $this->result;
        }
        return mysql_result($query, $row, $col);
    }

    function total_filas($query)
    {
        return mysql_num_rows($query);
    }

    function free_result($query)
    {
        mysql_free_result($query);
    }

    function last_id()
    {
        return mysql_insert_id($this->link);
    }

}