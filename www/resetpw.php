<?php      
    // Reset password page for Admin

    ini_set('session.gc_maxlifetime', 3600); // session timeout 1hr in secs.
    require 'common/nav.php';
    require 'common/validate.php';
    session_start(); // start session
    
    $_SESSION['resetUser'] = null;

    function displayPwError(){  
        if(isset($_SESSION['pwError'])){
            echo "<p class='pw-error'>{$_SESSION['pwError']}</p>";
        }        
        unset($_SESSION['pwError']);                
    }

    function resetForm($pdo){
        try{
            $qry = "SELECT * FROM users";
            $stmt = $pdo->prepare($qry);
            $stmt->execute();
        }   
        catch (PDOException $e){
            echo "<p class='error-msg'>Query Failed.</p>";
        }
        
        displayPwError();
        echo <<<_FORM
        <div class="page-forms">
        <p>Reset Password</p>
        <form action="resetpw.php" method="post">
            <label for="username">Select username:</label><br>            
            <select id="username" name="username">
_FORM;
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            if ($row['username'] !== "gladmin"){ // do not show the master admin account
                if (isset($_SESSION['resetUser']) && $_SESSION['resetUser'] === $row['username']){
                    echo "<option value=\"{$row['username']}\" selected>{$row['username']}</option>";
                } else {                   
                    echo "<option value=\"{$row['username']}\">{$row['username']}</option>";
                }
            }
        }
    
        echo <<<_FORM
            </select><br>
            <label for="password">Enter new password: </label><br>
            <input type="password" name="password"><br>
            <button type="submit" name="resetpwnow">Reset Password</button>
        </form>
        </div>
_FORM;
        $_SESSION['pwError'] = null;
    }

    function updatePW($pdo, $username, $password){
        try
        {
            $qry = $pdo->prepare('UPDATE users SET password = ? WHERE username = ?');
            $qry->bindParam(1,$password);
            $qry->bindParam(2,$username);            
            $qry->execute();            
        }
        catch (PDOException $e){
            echo "<p class='error-msg'>Failed to udpate password.</p>";
        }
        $pdo = null;
    }
    
    function isLoggedin(){
        if(!isset($_SESSION['username'])){
            header('location: index.php'); // redirect back to main page
        }else{     
            $pdo = dbconn();
                            
            // redirect back to profiles page if user was clicking on email button.    
            if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['resetpw'])){                
                resetForm($pdo);
            }

            if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['resetpwnow']) && isset($_POST['password'])){
                echo "<p>User:<strong> " . $_POST['username'] . "</strong></p>";
                $username = $_POST['username'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $prehashpw = $_POST['password'];

                if(validatePW($prehashpw) === false){
                    $_SESSION['pwError'] = "<p class='error-msg'>Invalid password. Requirements min 8, max 32 length, at least 1 uppercase/lowercase/number.</p>";
                    $_SESSION['resetUser'] = $username; // 
                    resetForm($pdo);
                } else {
                    updatePW($pdo, $username, $password);
                    echo "<p class='success-msg'>Password Updated.</p>";
                }
            }
        } 
    } 
?>    


<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
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