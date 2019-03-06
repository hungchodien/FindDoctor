<?php
/**
 * Created Database
 */
class SqlQueryBuilder {
    private $_select = array();
    private $_joins = array();
    private $_from = "";
    private $_limit=null;
    private $_start=0;
    private $_fetch = 0;
    private $_sqlText = "";
    private $_mainTable="";
    private $_where = null;
    private $_orderBy=null;
    private $_groupBy=null;
    private $_debug=false;
    private function regenerateQuery() {
        $this->_select = array();
        $this->_joins = array();
        $this->_from = array();
        $this->_limit = null;
        $this->_start = 0;
        $this->_fetch = 0;
        $this->_sqlText = "";
        $this->_mainTable="";
        $this->_where = null;
        $this->_orderBy = null;
        $this->_groupBy = null;
    }
    public function limits($limits=null) {
        $this->_limit = $limits;
        return $this;
    }
    public function orderBy($orderBy=array()) {
        $this->_orderBy = $orderBy;
        return $this;
    }
    public function groupBy($groupBy=array()) {
        $this->_groupBy = $groupBy;
        return $this;
    }
    public function from($from) {
        $this->_mainTable = $from;
        return $this;
    }
    public function mainTable($mainTable) {
        $this->_mainTable = $mainTable;
        return $this;
    }
    public function where($where) {
        $this->_where = $where;
        return $this;
    }
    public function select($selectArr=array()) {
        $this->regenerateQuery();
        $this->_select = $selectArr;
        return $this;
    }
    public function join($type="", $table="", $on=array()) {
        array_push($this->_joins, array("type"=>$type, "table"=>$table, "on"=>$on));
        return $this;
    }
    public function order($order=array()) {
        $this->order=$order;
        return $this;
    }
    private function conditions($param=array(), $logic="and") {
        if(is_array($param)) {
            $isMultiDimensional = @is_array( $param[0]);
            if($isMultiDimensional) {
                foreach($param as $item) {
                    if($this->_debug) {
                        print_r($item);exit();
                    }
                    $operator="=";
                    if(isset($item["type"])) {
                        if($item["type"]=="subset") {
                            if(isset($item["items"])) {
                                if(is_array($item["items"])) {
                                    $this->_sqlText.=" ".$logic." ( 1<>1 ";
                                    foreach($item["items"] as $subsetItem) {
                                        $this->conditions($subsetItem, "or");
                                    }
                                    $this->_sqlText.=")";
                                    continue;
                                }
                            }
                        }
                    }
                    if(isset($item["operator"])) {
                        $operator = $item["operator"];
                    }
                    if($operator=="in" || $operator=="is") {
                        $this->_sqlText.=" ".$logic." ".$item["column"]." ".$operator." ".$item["value"]." ";
                    }
                    else {
                        $this->_sqlText.=" ".$logic." ".$item["column"].$operator."'".$item["value"]."'";
                    }
                }
            } else {
                if(count($param)) {
                    $operator="=";
                    if(isset($param["operator"])) {
                        $operator = $param["operator"];
                    }
                    if($operator=="in" || $operator=="is") {
                        $this->_sqlText.=" ".$logic." ".$param["column"]." ".$operator." ".$param["value"]." ";
                    }
                    else {
                        $this->_sqlText.=" ".$logic." ".$param["column"].$operator."'".$param["value"]."'";
                    }
                }
            }
        }
    }
    public function build() {
        $this->_sqlText="";
        $this->_sqlText.="select ".implode(" , ", $this->_select);
        $this->_sqlText.=" from ".$this->_mainTable;
        //joins
        foreach($this->_joins as $item) {
            $this->_sqlText.=" ".$item["type"]." join ".$item["table"]." on ".implode(" and ", $item["on"]);
        }
        //where block
        $this->_sqlText.=" where 1=1 ";
        $this->conditions($this->_where);
        //groupBy block
        if($this->_groupBy) {
            $groupStr = " group by ";
            foreach($this->_groupBy as $item) {
                $groupStr.=$item.",";
            }
            $groupStr = substr($groupStr, 0, strlen($groupStr)-1);
            $this->_sqlText.=$groupStr;
        }
        //orderBy block
        if($this->_orderBy) {
            if(!is_array( $this->_orderBy[0])) {
                $this->_orderBy=array($this->_orderBy);
            }
            $orderStr = " order by ";
            foreach($this->_orderBy as $item) {
                $orderStr.=$item["field"]." ".$item["dir"].",";
            }
            $orderStr = substr($orderStr, 0, strlen($orderStr)-1);
            $this->_sqlText.=$orderStr;
        }
        // limits
        if($this->_limit) {
            $this->_sqlText.=" limit ".$this->_limit["start"].",".$this->_limit["limit"];
        }
        return $this->_sqlText;
    }
}
class Database {

    private $host = "";
    private $dbname = "";
    private $username = "";
    private $password = "";
    private $port = "";
    private $connect = null;
    public $check_connect = null;

    public function __construct(){
        $config = $GLOBALS['Config']['ENV'];
        $this->host = $config['host'];
        $this->dbname = $config['dbname'];
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->port = $config['port'];

        try{
            $this->connect = new PDO("mysql:host=$this->host;dbname=$this->dbname;port=$this->port",$this->username,$this->password);
            $this->check_connect = [
                'status' => true,
                'message' => 'connect true'
            ];
        }catch( PDOException $e ){
            $this->connect = null;
            $this->check_connect = [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function query_sql( $query ){
        try{
            $result = [];
            $stmt = $this->connect->query( $query );
            if( $stmt ){
                while ( $row = $stmt->fetch(\PDO::FETCH_ASSOC) ){
                    $result[] = $row;
                }
            }
            $this->close_conn();
            return $result;
        }catch(Exception $e){
            return [];
        }
    }

    private function close_conn(){
        $this->connect = null;
    }
}

/**
 * sql create table
 * CREATE TABLE IF NOT EXISTS DOCTOR (
id INT AUTO_INCREMENT,
title VARCHAR(255) NOT NULL,
start_date DATE,
due_date DATE,
status TINYINT NOT NULL,
priority TINYINT NOT NULL,
description TEXT,
PRIMARY KEY (id)
)  ENGINE=INNODB;
 */