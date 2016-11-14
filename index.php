
        <?php
        include('classes.php');
        $str="Иванов Иван Иванович";
        $logger=new phpLogger();
        $logger->setMode(3);
        $logger->writeLog($str);
        
         /*
setMode(1)-запись в STDOUT
setMode(2)-запись в MySql
setMode(3)-запись в файл
          */
        ?>

