<?php      
    // Profiles Page - display different profile groups depending what usertype the logged on user has.
    ini_set('session.gc_maxlifetime', 3600); // session timeout 1hr in secs.
    session_start(); // start session
    require 'common/nav.php';
    require 'common/validate.php';
    require 'common/unsetvars.php';
    
    checkFormVars();
       
    // email button for non admins to contact users
    function emailContact($email){
        echo "<td><a href='mailto:$email?subject=GuardianLink%20is%20here%20to%20help'>Email</a></td>";    
    }

    function adminPanel(){
        echo "<p>Admin Panel</p>";
        echo "<div class='page-forms'>";
        echo<<<_FORM
            <div>
                <form action="adduser.php" method="post">
                    <button type="submit" name="adduser">Add User</button>                
                </form>
            </div>
            <div>
                <form action="resetpw.php" method="post">
                    <button type="submit" name="resetpw">Reset passwords</button>
                </form>
            </div>
_FORM;
        echo "</div>";
    }  

    // display admin table
    function displayAllUsers($pdo){
        // display on admin/basic users
        $qry = $pdo->prepare("SELECT * FROM users WHERE usertype in ('admin','basic') ORDER BY usertype");        
        $qry->execute();

        echo "<div class='profiles'><table>";
        echo "<tr><th>Username</th><th>Email</th><th>User Type</th><th></th><th></th></tr>";

        while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {           
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";          
            echo "<td>" . htmlspecialchars($row['usertype']) . "</td>";          
            $email = htmlspecialchars($row['email']);
            if ($row['username'] !== "gladmin" && $_SESSION['username'] !== $row['username'] && $_SESSION['usertype'] === "admin"){
                echo "<td><form action='edituser.php' method='post'><button type='submit' name='edituser' value='" . htmlspecialchars($row['username']) . "'>Edit</button></form></td>";
                echo "<td><form action='deluser.php' method='post'><button type='submit' name='deleteuser' value='" . htmlspecialchars($row['username']) . "'>Delete</button></form></td>";
            }else{
                echo "<td><a href='mailto:$email?subject=Usertype%20Change%20Request'>Email</a></td>";
            }
            echo "</tr>";
        }   

        echo "</table></div>";     
        $pdo = null;
    }
    
    // display ngos table
    function displayNGOs($pdo){
        $qry = $pdo->prepare("SELECT u.username, u.email, n.orgname, n.areasofconcern FROM users AS u INNER JOIN ngos AS n ON u.ngo_id = n.ngo_id");        
        $qry->execute();
        
        echo "<div class='profiles'><table>";
        echo "<tr><th>Username</th><th>Email</th><th>Organization Name</th><th>Areas of Concern</th><th></th></tr>";

        while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['orgname']) . "</td>";
            echo "<td>" . nl2br(htmlspecialchars($row['areasofconcern'])) . "</td>";            
            if($_SESSION['usertype'] === "admin"){                
                echo "<td><form action='deluser.php' method='post'><button type='submit' name='deleteuser' value='" . htmlspecialchars($row['username']) . "'>Delete</button></form></td>";
            }else{                
                emailContact(htmlspecialchars($row['email']));
            }
            echo "</tr>";
        }   

        echo "</table></div>";
        $pdo = null;
    }
    
    // display volunteers table
    function displayVolunteers($pdo){
        $qry = $pdo->prepare("SELECT u.username, u.email, v.fname, v.lname, v.hrsperweek, v.cbc, v.linkedin FROM users AS u INNER JOIN volunteers AS v ON u.vol_id = v.vol_id");        
        $qry->execute();

        echo "<div class='profiles'><table>";
        echo "<tr><th>Username</th><th>Email</th><th>Name</th><th>HRs/Week</th><th>Criminal Check</th><th>LinkedIn</th><th></th></tr>";

        while ($row = $qry->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['fname']) . " " . htmlspecialchars($row['lname']) . "</td>";            
            echo "<td>" . htmlspecialchars($row['hrsperweek']) . "</td>";
            echo "<td>" . htmlspecialchars($row['cbc']) . "</td>";
            echo "<td>" . htmlspecialchars($row['linkedin']) . "</td>";
            if($_SESSION['usertype'] === "admin"){                
                echo "<td><form action='deluser.php' method='post'><button type='submit' name='deleteuser' value='" . htmlspecialchars($row['username']) . "'>Delete</button></form></td>";
            }else{                
                emailContact($row['email']);
            }
            echo "</tr>";
        }   

        echo "</table></div>";
        $pdo = null;
    }

    function isLoggedin(){        
        if(!isset($_SESSION['username'])){
            echo "<p class='not-loggedin'>Please login to view profiles.</p>";            
        }else{                        
            $pdo = dbconn();        
            $type = $_SESSION['usertype'];                                      
            
            switch($type){
                case "admin": // see all
                    adminPanel();               
                case "basic": // see all
                    echo "<p>Users</p>";     
                    displayAllUsers($pdo);
                    echo "<p>NGOs</p>";
                    displayNGOs($pdo);
                    echo "<p>Volunteers</p>";
                    displayVolunteers($pdo);           
                    break;
                case "ngo": // only see volunteers
                    echo "<p>Volunteers</p>";
                    displayVolunteers($pdo);      
                    break;     
                case "volunteer": // only see ngos
                    echo "<p>NGOs</p>";
                    displayNGOs($pdo);
                    break;
            }
        } 
    } 
    
?>    


<!DOCTYPE html>
<html>
<head>
    <title>Guardian Link Profiles</title>
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