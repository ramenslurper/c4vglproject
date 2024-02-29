<?php    
    ini_set('session.gc_maxlifetime', 3600); // session timeout 1hr in secs.
    session_start(); // start session
    require 'common/nav.php';
    require 'common/unsetvars.php';
    
    checkFormVars();
?>

<!DOCTYPE html>
<html>
<head>
    <title>About Us</title>
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
            <div class="aboutus">                
                <p>Who we are?</p>    
                <em><p>GuardianLink is a platform dedicated to bridging the gap between providers of cybersecurity specialists and clients in need of these services. <br>
                Our primary focus is on supporting non-profit organizations at no cost to them.</p></em>                  
            </div>
            <div class="aboutus">                
                    <p>What we do?</p>
                    <em><p>Our platform facilitates a streamlined process: cybersecurity professionals seeking to volunteer can
                    easily join through our web application to offer their expertise and aid. <br>Simultaneously, non-profit
                    organizations can also apply to become clients of our company, seeking the assistance they require.</p></em>
            </div>
            <div class="aboutus">
                    <p>Why we do it?</p>
                    <em><p>Our overarching goal is to seamlessly connect these two ends of the spectrum, ensuring that the need
                    for cybersecurity assistance within non-profit organizations <br>is met by dedicated and skilled volunteers.
                    At GuardianLink, our mission is to fulfill these needs efficiently and effectively.</p></em>
            </div>
            <div class="aboutus">
                <p>Partnerships</p><br>
                <div class="partner">
                    <img src="images/partner01.png" alt="partner 1">
                    <p>Secure2Day</p>
                </div>
                <div class="partner">
                    <img src="images/partner02.png" alt="partner 2">
                    <p>Shield4network</p>
                </div>
                <div class="partner">
                    <img src="images/partner03.png" alt="partner 3">
                    <p>Triple9Data</p>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <p>© 2024 GuardianLink</p>
    </footer>
</body>
</html>
