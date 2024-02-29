<?php
    // confirm deleting user account and kill all sessions and redirect back to main page.
    session_start(); // start session
    require 'common/validate.php';
    require 'common/dbdelfuncs.php';

    $pdo = dbconn();
    if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['delaccountyes'])){                
        $username = $_SESSION['username'];                                        
        $userTypeIDs = getUserTypeIDs($pdo, $username);
        
        // // Delete from DB tables.
        delFromNgos($pdo, $userTypeIDs['ngo_id']);           
        delFromVolunteers($pdo, $userTypeIDs['vol_id']);                           
        delFromUsers($pdo, $username);

        // destory all session variables and return to main page.
        session_destroy();
        $pdo = null;
        header('location: index.php');
        exit;
    }

?>