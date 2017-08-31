<?php

class DocMySQL{
    private $conSQL;
    private $nameDocTab = 'docmysql';
    private $db;

    public function __construct($serwerName,$userName,$password,$db) {
        $this->conSQL = new mysqli($serwerName,$userName,$password,$db);
        try{
            if($this->conSQL->connect_error)
                throw new Exception("Connection failed: " .$this->conSQL->connect_error);
        } catch (Exception $ex) {
            echo $ex->getMessage();
            exit();
        }
        $this->db = $db;
        if(!$this->issetDocTable()){
            $this->conSQL->query("CREATE  TABLE `$db`.`docmysql` (
                `id` INT NOT NULL AUTO_INCREMENT ,
                `table_name` VARCHAR(255) NULL ,
                `field_name` VARCHAR(255) NULL ,
                `type` VARCHAR(255) NULL ,
                `null` VARCHAR(255) NULL ,
                `key` VARCHAR(255) NULL ,
                `default` VARCHAR(255) NULL ,
                `extra` VARCHAR(255) NULL ,
                `description` TEXT NULL ,
                `status` INT NULL,
                PRIMARY KEY (`id`) )
                ENGINE = MyISAM;");
        }
    }
    
    public function numberOfTables(){
        return $this->conSQL->query('SHOW TABLES')->num_rows - 1;
    }
    
    public function buildDoc(){
        echo '<table>';
        $result = $this->conSQL->query("SHOW TABLES");
        if ($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                //echo $row['Tables_in_'.$this->db]."<br>";
                $result1 = $this->conSQL->query("DESC `".$row['Tables_in_'.$this->db]."`");
                $tab = $row['Tables_in_'.$this->db];
                while($row1 = $result1->fetch_assoc()){
                    echo <<<HTML
                    <tr><td>$tab</td><td>$row1[Field]</td><td>$row1[Type]</td><td></td><td></td><td></td>
                        </tr>
HTML;
                }
            }
        }
    }
    
    private function rebuildDoc(){
        
    }
    
    private function issetDocTable(){
        if($result = $this->conSQL->query("SHOW TABLES LIKE '$this->nameDocTab' ")){
            if($result->num_rows == 1)
                return true;
        }
        else return false;
    }
    
    public function __destruct() {
        $this->conSQL->close();
    }
}

$doc = new DocMySQL('localhost', 'root', 'ala#ma#kota','platforma');
//echo $doc->numberOfTables();
$doc->buildDoc();