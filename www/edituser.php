<?php      
    ini_set('session.gc_maxlifetime', 3600); // session timeout 1hr in secs.
    require 'common/nav.php';
    require 'common/validate.php';
    session_start(); // start session
    
    function editUser($pdo, $username){
        $qry = "SELECT * FROM users WHERE username='$username'";
        $result = $pdo->query($qry);
        $row = $result->fetch();
      
        $current_usertype = $row['usertype'];
        $usertype_array = array('admin','basic');
      
        echo <<<_FORM
        <div class="page-forms">
        <p>Edit User</p>
        <p>User:$username</p>
        <p>Current usertype:$current_usertype</p><br>
        <form action="edituser.php" method="post">
            <input type="hidden" name="username" value="$username">
            <label for="usertype">Choose usertype:</label>
            <select id="usertype" name="usertype">
_FORM;
        // only display the other usertypes user is not part of.
        foreach($usertype_array as $value){
            if($current_usertype !== $value){
                echo "<option value=\"$value\">$value</option>";
            }
        }
        echo <<<_FORM
        </select>
        <button type="submit" name="updateuser">Update</button>
        <button type="submit" formnovalidate formaction="profiles.php">Cancel</button>
        </form>
        </div>
_FORM;
      
    }

    function updateUser($pdo, $username, $usertype){
        try
        {
            $query = $pdo->prepare('UPDATE users SET usertype = ? WHERE username = ?');
            $query->bindParam(1,$usertype);
            $query->bindParam(2,$username);            
            $query->execute();
            return true;
        }
        catch (PDOException $e){
            echo "<p class='error-msg'>Failed to udpate user.</p>";
        } finally{
            $pdo = null;
        }
        return false;
    }

    
    function isLoggedin(){
        if(!isset($_SESSION['username'])){
            header('location: index.php'); // redirect back to main page
        }else{
            $pdo = dbconn();    
                                                            
            // editing user
            if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['edituser'])){            
                $username = $_POST['edituser'];
                editUser($pdo, $username);
            }

            if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['updateuser'])){            
                $username = $_POST['username'];
                $usertype = $_POST['usertype'];
                if(updateUser($pdo, $username, $usertype)){
                    echo "<p class='success-msg'>Update successful.</p>";
                }else{
                    echo "<p class='error-msg'>Update failed.</p>";
                }
            }
                             
        } 
    } 
?>    


<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
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