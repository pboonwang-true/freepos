<?php
/*
 * Class to create database connection...
 * @item_price: Suraj Roy
 * @Date:   30 Aug 2016
 * @Source url: https://jsonworld.com
 * @Topic : CRUD operations using Angularjs, PHP & mysql.
 */        


class DATABASE {
    private $dbHost     = 'localhost';
    private $dbUsername = 'root';
    private $dbPassword = '12345678';
    private $dbName     = 'freepos';
    public $db;
    
    
    /*
     * Db connection...
     */
    public function __construct(){
        $this->conn = mysqli_connect($this->dbHost,$this->dbUsername,$this->dbPassword,$this->dbName);
        $this->conn->set_charset("utf8");
        
        if (mysqli_connect_errno()){
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }                
    }
    
    /*
     * @fetching data from db books table
     */
    public function getRows($table,$conditions = array()){
        $rows = [];
        $sql = "SELECT * FROM  $table";
        $result=mysqli_query($this->conn,$sql) or die(mysqli_error($this->conn));
        while($row=mysqli_fetch_object($result)){
            $rows[] = $row; 
        }              
        return !empty($rows)?$rows:false;
    }
    
    /*
     * @fetching data from table
     */
    public function qryRows($qry){
        $rows = [];
        //$sql = "SELECT * FROM  $table  $conditions";
        $result=mysqli_query($this->conn,$qry) or die(mysqli_error($this->conn));
        while($row=mysqli_fetch_object($result)){
            $rows[] = $row; 
        }              
        return !empty($rows)?$rows:false;
    }

    /*
     * read Row from table
     */
    public function readRows($table,$conditions){
        $rows = [];
        $sql = "SELECT  *  FROM  $table  $conditions";
        $result=mysqli_query($this->conn,$sql) or die(mysqli_error($this->conn));        
        $num = $result->num_rows;                   
        return $num;
    }

        /*
     * read Row from table
     */
    public function readQryRows($qry){            
        $result=mysqli_query($this->conn,$qry) or die(mysqli_error($this->conn));        
        //$num = $result->num_rows;                      
        while($row=mysqli_fetch_object($result)){
            $rows[] = $row; 
        }  
        //return $num;
        return !empty($rows)?$rows:false;
    }

    /*
     * Insert data into the database
     */
    public function add_item($item_code,$item_name,$item_price){
        if(!empty($item_name)){

            $sql = "INSERT INTO  item SET item_code='$item_code',item_name='$item_name',item_price='$item_price'";
            $res = mysqli_query($this->conn,$sql) or die(mysqli_error($this->conn));
            
            if($res){
                $item_code = mysqli_insert_id($this->conn);
                return $item_code;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    
    /*
     * Update data into the database...
     */
    public function update($item_code,$item_name,$item_price,$id){
        if(!empty($id)){
            $sql = "UPDATE item SET item_code='$item_code',item_name='$item_name',item_price='$item_price' WHERE item_id='$id'"; //,,email='$email',
            $query = mysqli_query($this->conn,$sql);
            $row = mysqli_affected_rows($this->conn);
            return $query?$row:false;
        }else{
            return false;
        }
    }
    
    /*
     * Delete data from the database
     * @param string name of the table
     * @param array where condition on deleting data
     */
    public function delete($id){
        $sql = "DELETE FROM item WHERE item_id='$id'";
        $del=mysqli_query($this->conn,$sql) or die(mysqli_error($this->conn));        
        return $del?$del:false;
    }
}
