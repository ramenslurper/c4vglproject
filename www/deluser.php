<?php      
    ini_set('session.gc_maxlifetime', 3600); // session timeout 1hr in secs.
    session_start(); // start session
    require 'common/nav.php';
    require 'common/validate.php';
    require 'common/dbdelfuncs.php';
    
    function delConfirmation($username){
            echo <<< _FORM
            <div class="page-forms">
            <form action="deluser.php" method="post">                          
            <p>
            Delete user: $username<br>
            ACCOUNT DELETION !!<br>
            This is permanent. 
            Click YES to proceed. This will delete the account and return to the previous page.
            </p>
            <input type="hidden" value="$username" name="deluser">
            <button type='submit' name='delaccountyes'>YES</button>
            <button type='submit' name='delaccountno'>NO</button>
            </form>
            </div>
_FORM;
    }
    
    function isLoggedin(){        
        if(!isset($_SESSION['username'])){
            header('location: index.php'); // redirect back to main page
        }else{                           
            $pdo = dbconn();

            if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['delaccountyes'])){                  
                $username = $_POST['deluser'];
                // Delete from DB tables.
              
                $userTypeIDs = getUserTypeIDs($pdo, $username);
            
                delFromNgos($pdo, $userTypeIDs['ngo_id']);           
                delFromVolunteers($pdo, $userTypeIDs['vol_id']);                           
                delFromUsers($pdo, $username);
                echo "<p class='success-msg'>User account deleted.</p>";
            }

            if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['delaccountno'])){
                header('location: profiles.php');
            }

            // Checks if delete was pressed from the profiles page. Display delete confirmation.
            if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['deleteuser'])){                
                $username = $_POST['deleteuser'];
                delConfirmation($username);
            }
        } 
    } 
?>    


<!DOCTYPE html>
<html>
<head>
    <title>Delete User</title>
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