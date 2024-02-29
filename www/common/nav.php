<?php
    // display navigation bar. 
    // Sign UP is only visible for those not logged in.
    // Account is only visible for those logged in.
    
    function navBar() {        
        echo '<nav>';
        echo '<div class="nav-links">';
        echo '<div><a href="index.php">Home</a></div>';
        
        // Only show signup for users that are not logged in.
        if(!isset($_SESSION['username'])){
            echo '<div><a href="signup.php">Sign Up</a></div>';
        }
        
        echo '<div><a href="aboutus.php">About Us</a></div>';
        echo '<div><a href="profiles.php">Profiles</a></div>';
        
        // Only show account for users that are logged in.
        if(isset($_SESSION['username'])){
            echo '<div><a href="account.php">Account</a></div>';   
            echo '<div><a href="logout.php?logout=1">Logout</a></div>';         
        }else{
            echo '<div><a href="login.php">Login</a></div>';
        }        
    
        echo '</div>';
        echo '</nav>';
    }    
?>