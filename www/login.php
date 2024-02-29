<?php
    // Login page
    ini_set('session.gc_maxlifetime', 3600); // session timeout 1hr in secs.
    session_start();
    require 'common/nav.php';
    require 'common/validate.php';
    require 'common/unsetvars.php';
    
    checkFormVars();
    
    function displayLoginErrors(){
        if(isset($_SESSION['loginerror'])){        
            if($_SESSION['loginerror'] == 1){
                echo '<p class="error-msg">User not found.</p>';                    
            }
            if($_SESSION['loginerror'] == 2){
                echo '<p class="error-msg">Password incorrect. Please try again.</p>';                   
            }
            unset($_SESSION['loginerror']);       
        }
    }

    // Check if user and password were submitted.
    if(isset($_POST['username']) && isset($_POST['password'])) {
        $pdo = dbconn();     

        $usr_temp = filter_var($_POST['username'], FILTER_SANITIZE_STRING); // remove any html tags
        $pw_tmp = filter_var($_POST['password'], FILTER_SANITIZE_STRING);     

        $qry = $pdo->prepare("SELECT * FROM users WHERE username = :usr_temp");
        $qry->bindParam(':usr_temp', $usr_temp);
        $qry->execute();
        $row = $qry->fetch(PDO::FETCH_ASSOC);

        if(!$row){
                $_SESSION['loginerror'] = 1; // 1 = user not found
                $_SESSION['formUsername'] = $usr_temp;
                header('location: login.php');
                exit;
        }

        $pw = $row['password'];
        $currentUser = $row['username'];
        $type = $row['usertype'];
        
        if (password_verify(str_replace("'","", $pw_tmp), $pw)){        
            $_SESSION['username'] = $currentUser;      
            $_SESSION['usertype'] = $type;          
            unset($_SESSION['formUsername']);
            unset($_SESSION['formPW']);
            header('Location: profiles.php');
            exit;
        } else {
            $_SESSION['loginerror'] = 2;  // 2 = error password incorrect
            $_SESSION['formUsername'] = $usr_temp;
            $_SESSION['formPW'] = $pw_tmp;
            header('Location: login.php');
            exit;
        }
    }

?>

<!DOCTYPE html>
<html>
<head>
    <title>GuardianLink Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="common/styles.css">
</head>
<body>

<div class="login-box">
    <img src="images/gl_logo.png" alt="Logo">
    <p>Login</p>
    <form action="login.php" method="post">
        <label for="username"><b>Username</b></label>
        <?php if (isset($_SESSION['formUsername'])){       
            echo "<input type='text' name='username' value='{$_SESSION['formUsername']}' required>";
        } else {
            echo "<input type='text' placeholder='Enter Username' name='username' required>";
        }
        echo "<label for='password'><b>Password</b></label>";
        if (isset($_SESSION['formPW'])){
            echo "<input type='password' value='{$_SESSION['formPW']}' name='password' required>";
        } else {
            echo "<input type='password' placeholder='Enter Password' name='password' required>";
        }
        displayLoginErrors(); 
        ?>
        <button type="submit">Login</button>
        <button type="button" onclick="location.href='forgotpw.php?fp=1';">Forgot Password</button>
        <button type="button" onclick="location.href='index.php';">Return to Home</button>
    </form>
</div>

</body>
</html>
