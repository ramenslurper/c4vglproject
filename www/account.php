<?php     
    // user Account paged for logged in users to change email/password
    // account deletion is only available to ngo/volunteer users.
    ini_set('session.gc_maxlifetime', 3600); // session timeout 1hr in secs.
    require 'common/nav.php';
    require 'common/validate.php';    
    session_start(); // start session


    function displayChangeErrors() {
        $output = '';
        if (isset($_SESSION['emailError'])) {
            $output .= $_SESSION['emailError'];
        }
        if (isset($_SESSION['pwError'])) {
            $output .= $_SESSION['pwError'];
        }
        unset($_SESSION['emailError']);
        unset($_SESSION['pwError']);
        echo $output;
    }

    function chgEmailForm(){        
        displayChangeErrors();
        echo <<< _FORM
        <div class="page-forms">
        <form action='account.php' method='post'>
        <label for="email">Enter new email</label><br>        
        <input type="text" name="email" placeholder="some@email.com" required><br>                
        <button type="submit" name="changeemail">Change</button>
        <button type="submit" formnovalidate formaction="account.php">Cancel</button>
        </form>
        </div>
_FORM;        
    }

    function chgPWForm(){
        displayChangeErrors();
        echo <<< _FORM
        <div class="page-forms">
        <form action="account.php" method="post">                          
        <label for="password">Enter new Password:</label><br>
        <input type="password" name="password" required><br>
        <button type="submit" name="resetpw">Change</button>        
        <button type="submit" formnovalidate formaction="account.php">Cancel</button>        
        </form>
        </div>
        _FORM;        
    }
    
    function delConfirmation(){
        echo <<< _FORM
        <div class="page-forms">
        <form action="delconfirm.php" method="post">                          
        <p>
        ACCOUNT DELETION !!<br>
        This is permanent. You will need to re-register a new account if you want access.
        Click YES to proceed. This will delete your account and return to the main page.
        </p>
        <button type='submit' name='delaccountyes'>YES</button>
        <button type='submit'>NO</button>
        </form>
        </div>
_FORM;
    }

    function defaultForm($type){
        echo <<<_FORM
        <div class="page-forms">
        <form action="account.php" method="post">
            <input type="hidden" value="chgemail" name="chgfield">
            <button type="submit">Change Email</button>
        </form>
        <form action="account.php" method="post">
            <input type="hidden" value="chgpw" name="chgfield">
            <button type="submit">Change Password</button>            
        </form>                      
_FORM;
    
        if($type === "ngo" || $type === "volunteer"){
            echo <<<_FORM
            <form action="account.php" method="post">
            <input type="hidden" value="delaccount" name="chgfield">
            <button type="submit">Delete Account</button>            
            </form>
_FORM;
        }
        echo "</div>";
    }
    
    function updateEmail($pdo, $username, $email){
        try{
            // prepare statement update email in users table.
            $qry = $pdo->prepare('UPDATE users SET email = ? WHERE username=?');
            $qry->bindParam(1,$email);
            $qry->bindParam(2,$username);                
            $qry->execute([$email, $username]);            
        }
        catch (PDOException $e){        
            echo "Update failed.";                
            return false;
        }finally{
            $pdo = null;
        }
        return true;
    }
    
    function updatePw($pdo, $username, $password){
        try{
            // prepare statement update email in users table.
            $qry = $pdo->prepare('UPDATE users SET password = ? WHERE username=?');
            $qry->bindParam(1,$password);
            $qry->bindParam(2,$username);                
            $qry->execute([$password, $username]);
        }
        catch (PDOException $e){        
            echo "<p class='error-msg'>Update failed.</p>";      
            return false;          
        }finally{
            $pdo = null;
        }
        return true;
    }

    function displayAccount($pdo){
        try{
            $qry = "SELECT * FROM users WHERE username='" . $_SESSION['username'] . "'";
            $result = $pdo->query($qry);
        }
        catch (PDOException $e){        
            echo "<p class='error-msg'>Update failed.</p>";                
        }

        $row = $result->fetch();
        $username = htmlspecialchars($row['username']);
        $email = htmlspecialchars($row['email']);        
        $type = htmlspecialchars($row['usertype']);
        
        // Display user account
        echo "<div class='account-details'><table>";
        echo "<tr><th>User</th><th>Email</th></tr>";
        echo "<tr><td>{$username}</td><td>{$email}</td></tr>";
        echo "</table></div>";        
    } 

   

    function isLoggedin(){                
        $pdo = dbconn();
      
        if(!isset($_SESSION['username'])){
            echo "<h2>Please login to view Account details.</h2><br>";            
        }else{            
            $type = $_SESSION['usertype'];

            // Display account info.
            displayAccount($pdo);                                   

            if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['cancel'])){
                header('location: account.php');
            }

            // Update email change
            if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['changeemail'])){                
                $email = $_POST['email'];                
                $username = $_SESSION['username'];
                $type = $_SESSION['usertype'];
                
                if(!validateEmail($email)){
                    $_SESSION['emailError'] = "<p class='error-msg'>Please enter a valid email.</p>";
                    chgEmailForm();
                }else{
                    if(updateEmail($pdo, $username, $email)){                                                
                        echo "<p class='success-msg'>Email updated successfully.</p>";                        
                    }else{
                        echo "<p class='error-msg'>Email failed to update.</p>";
                    }                   
                }
            }    
            // Reset users password
            elseif($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['resetpw'])){                
                $username = $_SESSION['username'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $prehashpw = $_POST['password'];                

                if(!validatePW($prehashpw)){
                    $_SESSION['pwError'] = "<p class='error-msg'>Invalid password. Requirements min 8, max 32 length, at least 1 uppercase/lowercase/number.</p>";
                    chgPWForm($username);
                } else {
                    if(updatePw($pdo, $username, $password)){                        
                        echo "<p class='success-msg'>Password has been successfully updated.</p>";
                        unset($_SESSION['pwError']);
                    }else{
                        echo "<p class='error-msg'>Password failed to update.</p>";
                    }
                }                
            }            
            
            // check which button was pressed to display the correct form.
            elseif($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['chgfield'])){
                $username = $_SESSION['username'];            
                // display change email form
                if($_POST['chgfield'] === "chgemail"){
                    chgEmailForm();
                }
                
                // display change password form
                if($_POST['chgfield'] === "chgpw"){          
                    chgPWForm();          
                }
                
                // display account deletion confirmation
                if($_POST['chgfield'] === 'delaccount'){
                    delConfirmation();
                }                
            } else {                                        
                defaultForm($type);                   
            }
        } 
    } 
?>    

<!DOCTYPE html>
<html>
<head>
    <title>Account Details</title>
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
        <p>Account Details</p>
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