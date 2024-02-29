<?php
    // validation functions to be used.

    // validate db connection
    function dbconn(){
        require 'dbconn/dbconfig.php';  
        try
        {
            $pdobj = new PDO($attr, $user, $pass, $opts);                
        }
        catch (PDOException $e){        
            echo "<p>Connection to DB Failed.</p>";
        }
        
    return $pdobj;    
    }
    
    // Validate password requirements
    function validatePW($password){
        
        // check length
        if (strlen($password) < 8 || strlen($password) > 32) {
            return false;
        }

        // check for at least 1 uppercase
        if (!preg_match('/[A-Z]/',$password)){
            return false;
        }
        
        // check for at least 1 lowercase
        if (!preg_match('/[a-z]/',$password)){
            return false;
        }

         // Check for at least one digit
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }
        
        // valid password
        return true;
    }

    // Validate username length
    function validateUsernameLength($username){
        // check length
        if (strlen($username) < 3 || strlen($username) > 15) {
            return false;
        }
        return true;
    }

    // Validate email address
    function validateEmail($email){
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return false;
        }
        return true;
    }

    // Validate pin meets requirements of 8 digits and all numbers.
    function pinValidate($pin, $pinlength){        
        if($pinlength != 8 || !ctype_digit(strval($pin))){            
            return false;
        }        
        return true;
    }
    
    // Check if user exists already
    function userVerify($pdo, $username){        
        try
        {
            // Check if user exists in users table.
            $qry = $pdo->prepare("SELECT * FROM users WHERE username = :username");
            $qry->bindParam(':username',$username);
            $qry->execute();
            $row = $qry->fetch(PDO::FETCH_ASSOC);
            
            // if user is not found
            if (!$row) {
                return false;            
            }
        }
        catch (PDOException $e){        
            echo "<p class='error-msg'>Lookup Failed.</p>";
        }
        finally {
            $pdo = null;
        }      
        return true;  
    }

    // check if it is a linkedin url.
    function isLinkedinURL($url) {
        // match http:// or https://
        // www. or without
        // linkedin.com/in/
        // linked in username with or without trailing /
        // caseinsensitive
        if(empty($url)){  // if empty linkedin was entered since it's not required.
            return true; 
        }
        $pattern = "/^(https?:\/\/)?(www\.)?linkedin\.com\/(in\/[^\/]+\/?)$/i";
                
        if(preg_match($pattern, $url)){
                return true;  // if valid url
            }else{
                return false;
        }
    }    
?>
