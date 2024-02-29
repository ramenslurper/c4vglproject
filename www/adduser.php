<?php      
    // add users only can be performed by admin group
    
    ini_set('session.gc_maxlifetime', 3600); // session timeout 1hr in secs.
    require 'common/nav.php';
    require 'common/validate.php';
    session_start(); // start session
   
    function displayAdduserError() {
        $output = null; 
    
        if (isset($_SESSION['userError'])) {
            $output .= "<p class='adduser-error'>{$_SESSION['userError']}</p>";
        }
        if (isset($_SESSION['emailError'])) {
            $output .= "<p class='adduser-error'>{$_SESSION['emailError']}</p>";
        }
        if (isset($_SESSION['pwError'])) {
            $output .= "<p class='adduser-error'>{$_SESSION['pwError']}</p>";
        }
    
        // Clear the session variables
        unset($_SESSION['pwError']);
        unset($_SESSION['emailError']);
        unset($_SESSION['userError']);
    
        // Output the combined error messages        
        echo $output;
    }
   
    function validateAddUserInput($pdo, $username, $prehashpw, $email){
        // validate and verify input
        if(!validatePW($prehashpw)){
            $_SESSION['pwError'] = "<p class='error-msg'>Invalid password. Requirements min 8, max 32 length, at least 1 uppercase/lowercase/number.</p>";                
        }

        if(userVerify($pdo, $username)){
            $_SESSION['userError'] = "<p class='error-msg'>Username already exists.</p>";
        }

        if(!validateEmail($email)){
            $_SESSION['emailError'] = "<p class='error-msg'>Invalid Email.</p>";                
        }

        if  (isset($_SESSION['userError']) ||
            isset($_SESSION['emailError']) ||        
            isset($_SESSION['pwError']))
            {
                return false; // found at least 1 error.
            }else{
                return true;
            }
    }


    // User form.
    function userForm(){        
        displayAdduserError();
        echo <<<_FORM
        <div class="page-forms">
        <p>Add User</p><br>
        <form action="adduser.php" method="post">
            <label for="username">Enter username:</label><br>
            <input type="text" name="username" required> <br>
            <label for="email">Enter email:</label><br>
            <input type="text" name="email" required><br>
            <label for="password">Enter password:</label><br>
            <input type="password" name="password" required><br>            
            <label for="usertype">Choose usertype:</label><br>
            <select id="usertype" name="usertype">
                <option value="admin">admin</option>
                <option value="basic">basic</option>                
            </select><br>
            <button type="submit" name="addusernow">Add User</button>
        </form>
        </div>
_FORM;
    }

    function addUserToDB($pdo, $username, $email, $password, $usertype){
        try{            
            $pin = password_hash("11111111", PASSWORD_DEFAULT); 
            $ngo_id = 0;
            $vol_id = 0;
            $qry = $pdo->prepare('INSERT INTO users (username, email, password, usertype, pin, ngo_id, vol_id) VALUES (?,?,?,?,?,?,?)');
            $qry->bindParam(1,$username);
            $qry->bindParam(2,$email);
            $qry->bindParam(3,$password);
            $qry->bindParam(4,$usertype);
            $qry->bindParam(5,$pin);            
            $qry->bindParam(6,$ngo_id); 
            $qry->bindParam(7,$vol_id); 
            $qry->execute();
            return true;
        }
        catch (PDOException $e){
            echo "<p class='error-msg'>Failed to add user.</p>";
        }
        return false;
    }
   
    function isLoggedin(){        
        if(!isset($_SESSION['username'])){
            header('location: index.php'); // redirect back to main page
        }else{
            $pdo = dbconn();            
                                    
            if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['addusernow'])){
                // echo "ADD NOW.<br>";
                $username = filter_var(strtolower($_POST['username']), FILTER_SANITIZE_STRING);
                $email = $_POST['email'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $prehashpw = $_POST['password'];          
                $usertype = $_POST['usertype'];

                if(!validateAdduserInput($pdo, $username, $prehashpw, $email)){
                    userForm();
                } else {
                    if(addUserToDB($pdo, $username, $email, $password, $usertype)){
                        echo "<p class='success-msg'>User account added.</p>";
                        unset($_SESSION['pwError']);
                        unset($_SESSION['emailError']);
                        unset($_SESSION['usernameError']);
                    }else{
                        echo "<p class='error-msg'>User account failed to add.</p>";
                    }
                }             
            } 
            else {                
                if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['adduser'])){  
                    userForm();
                }
            }
        } 
    } 
?>    


<!DOCTYPE html>
<html>
<head>
    <title>Add User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="common/styles.css">
</head>
<body>
<header>
        <div class="header-images">            
            <div><img src="images/gl_mainlogo.png" alt="Logo"></div>
        </div>        
    </header>
    <?php navBar(); ?>
<main>
    <div class="main-block">       
        <?php
            isLoggedin();
        ?>
    </div>
</main>
<footer>
    <p>© 2024 GuardianLink</p>
</footer>

</body>
</html>


