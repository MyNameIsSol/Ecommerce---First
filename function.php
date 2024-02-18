<?php
class Createdb {
   public $servername;
   public $username;
   public $password;
   public $dbname;
   public $tbname;
   public $cols;
   public $condb;
   public $message;

   public function __construct(
   $dbname='crochet_andhooks',
   $tbname = 'tbname',
   $cols='id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
   column1 VARCHAR(225) NOT NULL,
   column2 VARCHAR(225) NOT NULL,
   column3 FLOAT(22) NOT NULL,
   column4 VARCHAR(22) NOT NULL',
   $servername='localhost',
   $username='root',
   $password='')
   {
    $this->servername = $servername; 
    $this->username = $username;
    $this->password = $password;
    $this->dbname = $dbname;
    $this->tbname = $tbname;
    $this->cols = $cols;
    

    $this->condb = mysqli_connect($servername,$username,$password);
    if(!$this->condb){
        die('Error:connection failed '.mysqli_connect_error());
    }
    $sql = "CREATE DATABASE IF NOT EXIST $dbname";
        if (mysqli_query($this->condb, $sql) || mysqli_select_db($this->condb,$dbname)) {
            $this->condb = mysqli_connect($servername, $username, $password, $dbname);
            if ($this->condb) {
                $Sql = "CREATE TABLE IF NOT EXISTS $tbname($cols);";
                 $query = "SELECT * FROM $tbname";
                if (!mysqli_query($this->condb, $Sql) || !mysqli_query($this->condb,$query)) {
                    echo "Error creating table:" . mysqli_error($this->condb);
                }
            }
        } else {
            echo  "something went wrong! <br>";
        }
   }

   public function inSert($table,$cols,$values){
           $insert = "INSERT INTO $table ($cols)VALUES($values)";
       if(mysqli_query($this->condb, $insert)){
           return true;
       }else{
           return false;
       }
   }
   public function deLete($table1, $table2,$col, $where,$limit){
        // $delete1 = "DELETE FROM $table1 WHERE $where";
        $check = "SELECT $col FROM $table1 WHERE $where LIMIT $limit";
        if ($checked = mysqli_query($this->condb, $check)) { 
            if(mysqli_num_rows($checked)>0){ 
                while ($rows = mysqli_fetch_array($checked)) {
                    $Pimage = $rows['product_image'];
                }
                unlink($Pimage);
            $delete = "DELETE FROM $table1 WHERE $where";
            if (mysqli_query($this->condb, $delete)) {
                return true;
            }else{
                return false;
            }
        }else{ 
            $check = "SELECT $col FROM $table2 WHERE $where LIMIT $limit";
        if ($checked = mysqli_query($this->condb, $check)) { 
            if(mysqli_num_rows($checked)>0){ 
                while ($rows = mysqli_fetch_array($checked)) {
                    $Pimage = $rows['product_image'];
                }
                unlink($Pimage);
            $delete = "DELETE FROM $table2 WHERE $where";
            if (mysqli_query($this->condb, $delete)) {
                return true;
            }else{
                return false;
            }
        }else{ 
            return false; }
        }
    }
} 
}

   public function reTrieve($table,$cols){
       $retrieve = "SELECT $cols FROM $table";
       if($retrieved = mysqli_query($this->condb, $retrieve)){
           if(mysqli_num_rows($retrieved)>0){ 
               return $retrieved;
           }else{ return false; }
       }else{ return false; }
   }

   public function reTrieveTwo($table1,$table2,$cols){
    $retrieve = "SELECT $cols FROM $table1 UNION SELECT $cols FROM $table2";
    if($retrieved = mysqli_query($this->condb, $retrieve)){
        if(mysqli_num_rows($retrieved)>0){ 
            return $retrieved;
        }else{ return false; }
    }else{ return false; }
}

public function checkIfExist($table,$col,$where,$limit){
    $check = "SELECT $col FROM $table WHERE $where LIMIT $limit";
    if ($checked = mysqli_query($this->condb, $check)) { 
        // if(mysqli_num_rows($checked)>0){ 
            return $checked;
        // }else{ return false; }
    }else{ return false; }
    }


    public function checkIfExist2table($table1,$table2,$col,$where,$limit){
        $check = "SELECT $col FROM $table1 WHERE $where LIMIT $limit";
        if ($checked = mysqli_query($this->condb, $check)) { 
            if(mysqli_num_rows($checked)>0){ 
                return $checked;
            }else{
                $check = "SELECT $col FROM $table2 WHERE $where LIMIT $limit";
                if ($checked = mysqli_query($this->condb, $check)) { 
                    if(mysqli_num_rows($checked)>0){ 
                        return $checked;
                    }else{
                         return $checked;
                        }
                }else{ return false; }
                }
        }else{ return false; }
        }




    public function upDate($table1,$table2,$col,$values,$where,$limit){

        $check = "SELECT $col FROM $table1 WHERE $where LIMIT $limit";
        if ($checked = mysqli_query($this->condb, $check)) { 
            if(mysqli_num_rows($checked)>0){ 
                $update= "UPDATE $table1 SET $values where $where ";
                if(mysqli_query($this->condb, $update)){
                    return true;
                }else{
                    return false;
                }
        }else{ 
            $check = "SELECT $col FROM $table2 WHERE $where LIMIT $limit";
        if ($checked = mysqli_query($this->condb, $check)) { 
            if(mysqli_num_rows($checked)>0){ 
                $update= "UPDATE $table2 SET $values where $where";
                if(mysqli_query($this->condb, $update)){
                    return true;
                }else{
                    return false;
                }
        }else{ 
            return false; }
        }
    }
  }
}

public function upGate($table,$values,$where){
    $update= "UPDATE $table SET $values where $where";
    if(mysqli_query($this->condb, $update)){
        return true;
    }else{
        return false;
    }
}

public function deL($table, $where){
        $delete = "DELETE FROM $table WHERE $where";
        if (mysqli_query($this->condb, $delete)) {
            return true;
        } else {
            return false;
        }
} 

}

class validator{

//standard validation for names with alphabet only and no space
    function validateName($name){
    if (!empty($name) && strlen($name) <= 22) {
        $name = strtolower($name);
            $name = $this->specialChar($name);
            $name = filter_var($name, FILTER_SANITIZE_STRING);
            if (filter_var($name, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z]+$/")))) {
                return $name;
            } else {
                return false;
            }
    } else {
        return false;
    }
}

//standard validation for usernames accepting alpha-num _  .  - and space
function validateAllname($name){
    if(!empty($name) && strlen($name) <= 22){
        $name = strtolower($name);
        $name = $this->specialChar($name);
        if (preg_match("/^([a-zA-Z0-9\s_.\-])+$/i",$name,$res)){
            return $res[0];
        }else{
            return false;}
     }else{
         return false;}

}
//standard validation for all form of email
function validateEmail($e_mail){
    if (!empty($e_mail)) {
    $e_mail = strtolower($e_mail);
    $e_mail = $this->specialChar($e_mail);
    $e_mail = filter_var($e_mail, FILTER_SANITIZE_EMAIL);
    if (filter_var($e_mail, FILTER_VALIDATE_EMAIL)) {
        return $e_mail;
    } else {
        return false;
    }
}else {
    return false;
}
}

//standard validation for all password form
function validatePassword($pword){
    if(!empty($pword)){
       $pword = $this->specialChar($pword);
       $pword = preg_match("/^.*(?=.{8,})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/",$pword,$res);
       if($pword){
           return $res[0];
       }else{
           return false;}
    }else{
        return false;}
}

//standard validation for numbers only
function validateNumber($int){
if (!empty($int) && strlen($int) <= 22) {
        $int = $this->specialChar($int);
        if (is_numeric($int)) {
            if (filter_var($int, FILTER_SANITIZE_NUMBER_INT)) {
                return $int;
            } else {
                return false;
            }
        }else{ return false; }
}else{ return false; }    
}

//standard validation for any string word text
function validateString($strings){
    if(!empty($strings) && strlen($strings) <= 22){
        $strings = strtolower($strings);
        $strings = $this->specialChar($strings);
        $strings = filter_var($strings, FILTER_SANITIZE_STRING);
        if($strings){
            return $strings;
        }else{
            return false;}
     }else{
         return false;}

}


//standard encrypted text validation ie for alpha & numeric
function validateEncrypVal($value){
        if (!empty($value && strlen($value) <= 250)) {
            $value = $this->specialChar($value);
            if (preg_match("/^([[:alnum:]])+$/",$value,$res)){
                return $res[0];
            } else {
                return false;
            }
        }else{
            return false;
        }
    }

//standard note or message validation
    function validateText($note){
        if (!empty($note) && strlen($note) <= 250) {
            $note = $this->specialChar($note);
            if (filter_var($note, FILTER_SANITIZE_SPECIAL_CHARS)){
                return $note;
            } else {
                return false;
            }
        }else{
            return false;
        }
    }

    //standard image validation
function validateImageUpload($file,$fileExe,$fileSize){
        $exeArray = array("jpg","png","jpeg","gif");
        if(!file_exists($file)){
            if(in_array($fileExe,$exeArray)){
                if($fileSize < 2097152){
                    $message = $file;
                }else{
                    $message = "File size too large, Must be exactly 2 MB";
                }
            }else{
                $message = "File format not allowed, please choose a jpg or png or jpeg or gif file.";
            }
        }else{
            $message = "Sorry, file already exist.";
        }
            return $message;
    }

function specialChar($char){
        //remove all form of space at the begining and end of a value
        $char = trim($char);
        //removes unrequired backslash from value
        $char = stripslashes($char);
        //convert html special characters to entity ie < will be &lt;
        $char = htmlspecialchars($char);
        return $char;
   }   
    
}

?>