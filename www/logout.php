<?php
    // logout user and destroy all sessions, redirect back to main page.
    ini_set('session.gc_maxlifetime', 3600); // session timeout 1hr in secs.
    session_start(); // start session
    
    function logoutUser(){        
        if(isset($_SESSION['username']) && $_SERVER['REQUEST_METHOD'] === "GET" && $_GET['logout'] == 1){            
            session_destroy();
            header('location: index.php');
        } else {
            header('location: index.php');            
        }

    }

    logoutUser();
?>