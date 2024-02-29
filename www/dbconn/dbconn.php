<?php
    // db connection
    function dbconn(){
        require 'dbconfig.php';  
        try
        {
            $pdobj = new PDO($attr, $user, $pass, $opts);                
        }
        catch (PDOException $e){        
            echo "<p>Connection to DB Failed.</p>";
        }
        
    return $pdobj;    
    }
    
?>