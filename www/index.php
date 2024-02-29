<?php    
    ini_set('session.gc_maxlifetime', 3600); // session timeout 1hr in secs.
    session_start(); // start session
    require 'common/nav.php'; // contains navigation bar elements
    require 'common/unsetvars.php';
    
    checkFormVars();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome to GuardianLink</title>
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
        <div class="main-row">
            <div class="main-col"><img src="images/image01.png" alt="laptop secure"></div>
            <div class="main-col"><p>Protect yourself and your data.</p></div>
        </div>
        <div class="main-row">
            <div class="main-col"><p>Secure your future with the help from our Cybersecurity Specialists.</p></div>
            <div class="main-col"><img src="images/image02.png" alt="threat detected"></div>
        </div>        
        <div class="main-row">
            <div class="main-col"><img src="images/image03.png" alt="suspicous man"></div>
            <div class="main-col"><p>We have the skills and tools to prevent against threats.</p></div>
        </div>
        <div class="main-row">
            <div class="main-col"> <p>Have Inquiries feel free to contact us:</p>
                <a href='mailto:inquiries@guardianlink.com?subject=GuardianLink%20Inquiries'>Contact Support</a></div>
            <div class="main-col"><img src="images/image04.png" alt="2 people consulting"></div>
        </div>        
    </div>       
</main>    
<footer>
    <p>© 2024 GuardianLink</p>
</footer>
</body>
</html>
