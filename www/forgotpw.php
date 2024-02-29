<?php  
    // Forgot Password Page
    ini_set('session.gc_maxlifetime', 3600); // session timeout 1hr in secs.
    session_start();
    require 'common/nav.php';
    require 'common/validate.php';
    
    function displayForgetPwErrors(){        
        $output = null;
    
        if(isset($_SESSION['usernameError'])){
            $output .= $_SESSION['usernameError'];
        }
        if(isset($_SESSION['pinError'])){
            $output .= $_SESSION['pinError'];
        }
        if(isset($_SESSION['pinNotMatch'])){
            $output .= $_SESSION['pinNotMatch'];
        }
        if(isset($_SESSION['pwError'])){
            $output .= $_SESSION['pwError'];
        }
                        
        unset($_SESSION['usernameError']);
        unset($_SESSION['pinError']);
        unset($_SESSION['pinNotMatch']);
        unset($_SESSION['pwError']);
        
        echo $output;
    }


    function contactSupport(){
        echo "<div class='contact-support'>";
        echo "<p>Contact US<br>If you are still experiencing issues resetting your password.</p>";
        echo "<a href='mailto:support@guardianlink.com?subject=Password%20Reset%20Request'>Contact Support</a>";  
        echo "</div>";
    }

    function pinForm(){         
        displayForgetPwErrors();
        echo<<<_FORM
        <div class="page-forms">
        <form action="forgotpw.php" method="post">
        <label for="username">Enter username:</label><br>
        <input type="text" name="username" required> <br>
        <label for="pin">Enter 8-digit secure PIN:</label><br>
        <input type="password" name="pin" placeholder="enter 8 digits" size="10" required> <br>
        <button type="submit" name="verify">Verify</button>
        </form>
        </div>
        _FORM;
    }


    function pwForm($username){
        displayForgetPwErrors();
        echo <<<_FORM
        <div class="page-forms">
        <form action="forgotpw.php" method="post">
        <input type="hidden" value="$username" name="username">
        <label for="password">Enter new password: </label>
        <input type="password" name="password" required> <br>
        <button type="submit" name="updatepw">Update</button>
        </form>        
        </div>
_FORM;        
    }

    function pinVerifyInDB($pdo, $pin, $username){   
        try
        {
            $qry = $pdo->prepare("SELECT * FROM users WHERE username = :username");            
            $qry->bindParam(':username', $username);
            $qry->execute();
            $row = $qry->fetch(PDO::FETCH_ASSOC);
            $acutalpin = $row['pin'];
            
            if (password_verify(str_replace("'","", $pin), $acutalpin)){           
                return true;       
            } else {
                return false;
            }
        }
        catch (PDOException $e){        
            echo "<p class='error-msg'>Lookup Failed.</p>";
        }
        finally{
            $pdo = null;
        }
    }
         
    function pwReset($pdo, $password, $username){  
        try
        {       
            // Prepare statement to update password for user.
            $qry = $pdo->prepare('UPDATE users SET password=? WHERE username=?');            
            $qry->bindParam(1,$password);
            $qry->bindParam(2,$username);            
            $qry->execute();
            return true;
        }
        catch (PDOException $e){        
            echo "<p class='error-msg'>Update to DB Failed.</p>";
            return false;
        }
        finally {
            $pdo = null;
        }              
    }

    function pwRequest(){   
        $pdo = dbconn();    

        // Do password reset.
        if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['updatepw']) && isset($_POST['username'])){
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $prehashpw = $_POST['password'];

            if(!validatePW($prehashpw)){
                $_SESSION['pwError'] = "<p class ='error-msg'>Invalid password. Requirements min 8, max 32 length, at least 1 uppercase/lowercase/number.</p>";
                pwForm($username);
            } else {
                if(pwReset($pdo, $password, $username)){
                    echo "<p class='success-msg'>Password has been successfully updated.</p>";
                }else{
                    echo "<p class='error-msg'>Password failed to update.</p>";
                }
            }            
        // check if verify button was pressed.    
        } elseif ( $_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['verify'])){            
            $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            $pin = $_POST['pin'];
            $pinlength = strlen($_POST['pin']);
                
            // check if user exists in the system
            if(!userVerify($pdo, $username)){
                $_SESSION['usernameError'] = "<p class='error-msg'>User not found.</p>";
                pinForm();
            }else{
                // check and validate the PIN entered
                
                if(!pinValidate($pin, $pinlength)){
                    $_SESSION['pinError'] = "<p class='error-msg'>PIN must be exactly 8 digits and composed of only digits.</p>";
                }
                if(!pinVerifyInDB($pdo, $pin, $username)){
                    $_SESSION['pinNotMatch'] = "<p class='error-msg'>PIN does not match user</p>";
                }      
                if(isset($_SESSION['pinError']) ||
                isset($_SESSION['pinNotMatch'])
                ){
                    $pinErrors = false;
                }else{
                    $pinErrors = true;
                }
            
                if(!$pinErrors){
                    pinForm();
                }else{
                    pwForm($username);      
                }
            }
        } else {
            pinForm(); // display the form to enter PIN
        }
    }    
?>    


<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
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
            <p>Forgot Password</p>
            <?php
                pwRequest();
                contactSupport();
            ?>
        </div>
    </main>    
    <footer>
        <p>© 2024 GuardianLink</p>
    </footer>
</body>
</html>



















