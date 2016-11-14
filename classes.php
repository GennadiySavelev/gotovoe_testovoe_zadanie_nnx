<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        
        abstract class Logwriter{
        abstract public function LogStr($str);}
       
        class Stdoutlogwriter extends Logwriter{
            private $stdout;
            public function __construct() {
                $this->stdout=fopen('php://stdout','w');
            }
            public function LogStr($str) {
               fwrite($this->stdout,$str);
            }
       }
        class Mysqllogwriter extends Logwriter{
            private $HOST="localhost";
            private $USER="root";
            private $PASS="";
            private $bdname="Logger";
            private $tbname="logStr";
            private $link;
            
            public function __construct() {
                $this->link=mysqli_connect("$this->HOST", "$this->USER", "$this->PASS");
              if (!$this->link) exit(mysqli_error($this->link));
                    $r = mysqli_query($this->link,"CREATE DATABASE IF NOT EXISTS $this->bdname  ");
                    if (!$r) exit(mysqli_error($this->link));
                    mysqli_select_db($this->link,$this->bdname);
                    mysqli_query($this->link,'SET NAMES UTF8');
                    $res = mysqli_query($this->link,"CREATE TABLE IF NOT EXISTS $this->tbname
 (`id` INT(11)COLLATE utf8_general_ci NOT NULL AUTO_INCREMENT,
 `message` CHAR(200) COLLATE utf8_general_ci NOT NULL,
 PRIMARY KEY(`id`));");   
            }
            public function LogStr($str) {
                $insert_log = "INSERT INTO $this->tbname (`id`,`message`) VALUES (0,'$str');";
                 if (!$this->link) exit(mysqli_error($this->link));
                mysqli_query($this->link,$insert_log);
            }
        }
        class FilelogWriter extends Logwriter{
            private $writelog;
            public function __construct($filename) {
                $this->writelog=fopen($filename,'a');
              
            }
            public function LogStr($str) {
                $outStr=$str."\r\n";
              fwrite($this->writelog,$outStr);
            }
           
        }
        class phpLogger
{
	private $stdLogger;
	private $mySqlLogger;
	private $fileLogger;
        private $currentLogger;
        



        public function __construct()
	{
            $filename="logstr.txt";
		$this->stdLogger   = new Stdoutlogwriter();
		$this->mySqlLogger = new Mysqllogwriter ();
		$this->fileLogger  = new FilelogWriter($filename);
                $currentLogger=$this->fileLogger;
	}

	public function formatString($str)
	{
		
		return date('Y-m-d H:i:s')." ".$str;
	}

	public function setMode($mode)
	{
		if($mode == 1)
                {$this->currentLogger = $this->stdLogger;}
                else if($mode == 2){
                $this->currentLogger = $this->mySqlLogger;}
                else if($mode == 3){
                $this->currentLogger = $this->fileLogger;}
	}

	public function writeLog($str)
	{
		$this->currentLogger->LogStr($this->formatString($str));
	}

}


        ?>
    </body>
</html>
