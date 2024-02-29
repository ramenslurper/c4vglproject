<?php  
    // Register form for either ngo accounts or volunteer accounts    
    ini_set('session.gc_maxlifetime', 3600); // session timeout 1hr in secs.
    session_start();
    require 'common/nav.php';    
    require 'common/validate.php';
    require 'common/unsetvars.php';
    
    checkFormVars();

    function displaySignUpErrors() {
        $output = '';
        if(isset($_SESSION['userError'])){
            $output .= $_SESSION['userError'];            
        }
        if(isset($_SESSION['userLength'])){
            $output .= $_SESSION['userLength'];            
        }
        if(isset($_SESSION['emailError'])){
            $output .= $_SESSION['emailError'];
        }
        if(isset($_SESSION['pwError'])){
            $output .= $_SESSION['pwError'];
        }
        if(isset($_SESSION['pinError'])){
            $output .= $_SESSION['pinError'];
        }
        if(isset($_SESSION['urlError'])){
            $output .= $_SESSION['urlError'];
        }

        // Clear the session variables
        unsetSignUpErrors();

        echo $output; // output all possible errors in the form.
    }

    function displayNgoForm(){
        displaySignUpErrors();
        echo "<div class='page-forms'>";
        echo "<h3>Register as a NGO</h3>";
        echo "<form action='signup.php' method='post'>";
        echo "<input type='hidden' name='type' value='ngo'>";
        echo "<label for='username'>Username:</label><br>";
        if(isset($_SESSION['formUsername'])){
            echo "<input type='text' name='username' value='{$_SESSION['formUsername']}' required><br>";
        } else {
            echo "<input type='text' name='username' placeholder='username here...' required><br>";
        }
        echo "<label for='orgname'>Organization Name:</label><br>";
        if(isset($_SESSION['formOrg'])){
            echo "<input type='text' name='orgname' value='{$_SESSION['formOrg']}' required><br>";
        }else{
            echo "<input type='text' name='orgname' placeholder='Org name here...' required><br>";
        }
        echo "<label for='email'>Email:</label><br>";
        if(isset($_SESSION['formEmail'])){
            echo "<input type='text' name='email' value='{$_SESSION['formEmail']}' required><br>";
        }else{
            echo "<input type='text' name='email' placeholder='some@email.com' required><br>";
        }
        echo "<label for='password'>Password:</label><br>";
        if(isset($_SESSION['formPW'])){
            echo "<input type='password' value='{$_SESSION['formPW']}' name='password' required><br>";
        }else{
            echo "<input type='password' name='password' required><br>";
        }
        echo "<label for='pin'>Enter secure 8-digit PIN for Password Reset:</label><br>";
        if(isset($_SESSION['formPIN'])){
            echo "<input type='password' value='{$_SESSION['formPIN']}'name='pin' required><br>";            
        }else{
            echo "<input type='password' name='pin' required><br>";
        }
        echo "<label for='areasofconcern'>Areas of concern:</label><br>";
        if(isset($_SESSION['formAreasOfConcern'])){
            echo "<textarea name='areasofconcern' rows='10'>{$_SESSION['formAreasOfConcern']}";
        }else{
            echo "<textarea name='areasofconcern' rows='10'>";           
        }
        echo "</textarea>";
        echo "<button type='submit' name='register'>Register</button>";
        echo "</form>";
        echo "</div>";
    }

    function displayVolunteerForm(){
        displaySignUpErrors();        
        echo "<div class='page-forms'>";
        echo "<h3>Register as a Volunteer</h3>";
        echo "<form action='signup.php' method='post'>";
        echo "<input type='hidden' name='type' value='volunteer'>";
        echo "<label for='username'>Username:</label><br>";
        if(isset($_SESSION['formUsername'])){
            echo "<input type='text' name='username' value='{$_SESSION['formUsername']}' required><br>";
        } else {
            echo "<input type='text' name='username' placeholder='username here...' required><br>";
        }
        echo "<label for='email'>Email:</label><br>";
        if(isset($_SESSION['formEmail'])){
            echo "<input type='text' name='email' value='{$_SESSION['formEmail']}' required><br>";
        }else{
            echo "<input type='text' name='email' placeholder='some@email.com' required><br>";
        }
        echo "<label for='password'>Password:</label><br>";
        if(isset($_SESSION['formPW'])){
            echo "<input type='password' value='{$_SESSION['formPW']}' name='password' required><br>";
        }else{
            echo "<input type='password' name='password' required><br>";
        }
        echo "<label for='pin'>Enter secure 8-digit PIN for Password Reset:</label><br>";
        if(isset($_SESSION['formPIN'])){
            echo "<input type='password' value='{$_SESSION['formPIN']}'name='pin' required><br>";            
        }else{
            echo "<input type='password' name='pin' required><br>";
        }
        echo "<label for='fname'>First Name:</label><br>";
        if(isset($_SESSION['formFname'])){
            echo "<input type='text' name='fname' value='{$_SESSION['formFname']}' required><br>";
        } else {
            echo "<input type='text' name='fname' placeholder='First name...' required><br>";
        }
        echo "<label for='lname'>Last Name:</label><br>";
        if(isset($_SESSION['formLname'])){
            echo "<input type='text' name='lname' value='{$_SESSION['formLname']}' required><br>";
        } else {
            echo "<input type='text' name='lname' placeholder='Last name...' required><br>";
        }
        echo "<label for='hrsperweek'>Availability in hours per week:</label><br>";        
        echo "<select id='hrsperweek' name='hrsperweek'>";
        for($i=1; $i <=100; $i++){
            $selected = ($_SESSION['formHrsperweek'] == $i) ? 'selected' : '';
            echo "<option value='{$i}' {$selected}>{$i}</option>";
        }
        echo "</select><br>";
        echo "<label for='cbc'>Do you consent to a Criminal Background Check:</label><br>";
        echo "<select id='cbc' name='cbc' required>";
        echo "<option value='yes'>yes</option>";
        echo "<option value='no'>no</option>";
        echo "</select><br>";
        echo "<label for='linkedin'>LinkedIn URL:</label><br>";
        if(isset($_SESSION['formLinkedin'])){
            echo "<input type='text' name='linkedin' value='{$_SESSION['formLinkedin']}' required><br>";
        } else {
            echo "<input type='text' name='linkedin' placeholder='http://www.linkedin.com/in/user...' required><br>";
        }       
        echo "<button type='submit' name='register'>Register</button>";                
        echo "</form>";
        echo "</div>";
    }

    function displaySelectForm(){
        echo<<<_FORM
        <div class="page-forms">
            <p>Join now</p>
            <form action='signup.php' method='post'>
                <input type='hidden' name='type' value='ngo'>
                <button type='submit'>Register as NGO</button>
            </form>
            <form action='signup.php' method='post'>
                <input type='hidden' name='type' value='volunteer'>
                <button type='submit'>Register as CyberSecurity Volunteer</button>
            </form>
        </div>
_FORM;
    }

    function insertUsersTable($pdo, $username, $email, $password, $type, $pin){
        try
        {
            // set the last insert id to assign to either ngo_id or vol_id
            switch($type){
                case "ngo":
                    // echo "<p>Last ngo ID: " . $pdo->lastInsertId() . "</p>";
                    $ngo_id = $pdo->lastInsertId();
                    $vol_id = 0;
                    break;
                case "volunteer":
                    // echo "<p>Last vol ID: " . $pdo->lastInsertId() . "</p>";
                    $ngo_id = 0;
                    $vol_id = $pdo->lastInsertId();
                    break;
            }
            $qry = $pdo->prepare('INSERT INTO users (username, email, password, usertype, pin, ngo_id, vol_id) VALUES (?,?,?,?,?,?,?)');
            $qry->bindParam(1,$username);
            $qry->bindParam(2,$email);
            $qry->bindParam(3,$password);
            $qry->bindParam(4,$type);
            $qry->bindParam(5,$pin);
            $qry->bindParam(6,$ngo_id);
            $qry->bindParam(7,$vol_id);
            $qry->execute();
        }
        catch (PDOException $e){
            echo "<p>Failed to create user." . $e->getMessage() . "</p>";
        }
        $pdo = null;
    }
    
    function insertVolunteersTable($pdo, $fname, $lname, $hrsperweek, $cbc, $linkedin){
        try
        {
            $qry = $pdo->prepare('INSERT INTO volunteers (fname, lname, hrsperweek, cbc, linkedin) VALUES (?,?,?,?,?)');            
            $qry->bindParam(1,$fname);
            $qry->bindParam(2,$lname);            
            $qry->bindParam(3,$hrsperweek);
            $qry->bindParam(4,$cbc);
            $qry->bindParam(5,$linkedin);
            $qry->execute();
        } catch (PDOException $e){        
                echo "<p>Failed to create vol user." . $e->getMessage() . "</p>";
        }
        $pdo = null;
    }

    function insertNgosTable($pdo, $orgname, $areasofconcern){
        try{
            $qry = $pdo->prepare('INSERT INTO ngos (orgname, areasofconcern) VALUES (?,?)');            
            $qry->bindParam(1,$orgname);
            $qry->bindParam(2,$areasofconcern);
            $qry->execute();            
        } catch (PDOException $e){        
            echo "<p>Failed to create ngo user." . $e->getMessage() . "</p>";
        }
        $pdo = null;
    }

    // validate input. 
    // linkedin url is set to a valid default one if nothing entered in case of the ngo form.
    function validateSignUpInput($pdo, $username, $pin, $pinlength, $prehashpw, $email, $linkedin=null){        
        // validate and verify input
        if(!pinValidate($pin, $pinlength)){
            $_SESSION['pinError'] = "<p class='error-msg'>PIN must be exactly 8-digits.</p>";           
        }else{
            $pin = password_hash($_POST['pin'], PASSWORD_DEFAULT);
        }
        
        if(userVerify($pdo, $username)){
            $_SESSION['userError'] = "<p class='error-msg'>Username already exists.</p>";            
        }else{
            if (!validateUsernameLength($username)){
                $_SESSION['userLength'] = "<p class='error-msg'>Username must be min 3 chars long and max 15 chars long.</p>";
            }
        }
        
        if(!validateEmail($email)){
            $_SESSION['emailError'] = "<p class='error-msg'>Invalid Email.</p>";             
        }
                
        if(!validatePW($prehashpw)){
            $_SESSION['pwError'] = "<p class='error-msg'>Invalid password. Requirements min 8, max 32 length, at least 1 uppercase/lowercase/number.</p>"; 
        }
        
        // if $linkedin is not null. Means data input was from volunteers form.
        if($linkedin !== null){
            if(!isLinkedinURL($linkedin)){
                    $_SESSION['urlError'] = "<p class='error-msg'>Invalid linkedin URL.</p>";                    
            }
        }
        
        if  (isset($_SESSION['pinError']) || 
            isset($_SESSION['userError']) ||
            isset($_SESSION['userLength']) ||
            isset($_SESSION['emailError']) ||        
            isset($_SESSION['pwError']) ||
            ($linkedin !== null && isset($_SESSION['urlError']))            
        )
            {
                return false; // found at least 1 error.
            }else{
                return true;
            }
    }

    function signUp(){
        // If form was filled. Check which type of form was filled and enter data to db.
        switch(true){
            case (isset($_POST['register'])):
                $pdo = dbconn();
                $username = filter_var(strtolower($_POST['username']), FILTER_SANITIZE_STRING);
                $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $prehashpw = $_POST['password'];  
                $pin = $_POST['pin'];
                $pinlength = strlen($_POST['pin']);            
                $type = $_POST['type'];
                $_SESSION['formUsername'] = $username;
                $_SESSION['formEmail'] = $email;   
                $_SESSION['formPW'] = $prehashpw;
                $_SESSION['formPIN'] = $pin;
                
                switch($type){
                    case "ngo":
                        $_SESSION['formOrg'] = $_POST['orgname'];
                        $_SESSION['formAreasOfConcern'] = $_POST['areasofconcern'];                        
                        $orgname = filter_var($_POST['orgname'],FILTER_SANITIZE_STRING);
                        $areasofconcern = (filter_var($_POST['areasofconcern'], FILTER_SANITIZE_STRING));
                        
                        if(!validateSignUpInput($pdo, $username, $pin, $pinlength, $prehashpw, $email)){
                            displayNgoForm();                            
                        } else {
                            $pin = password_hash($_POST['pin'], PASSWORD_DEFAULT);
                                                        
                            // prepare statement insert into ngos table.
                            insertNgosTable($pdo, $orgname, $areasofconcern);                            
                            
                            // prepare statement insert into users table.
                            insertUsersTable($pdo, $username, $email, $password, $type, $pin);
                            
                            echo "<p class='success-msg'>NGO registered.</p>";      

                            // unset variables
                            unsetNgoFormVars();
                                             
                        }
                        break;
                    case "volunteer":
                        $linkedin = filter_var($_POST['linkedin'], FILTER_SANITIZE_URL);
                        $fname = filter_var($_POST['fname'], FILTER_SANITIZE_STRING);
                        $lname = filter_var($_POST['lname'], FILTER_SANITIZE_STRING);
                        $hrsperweek = $_POST['hrsperweek'];                                        
                        $cbc = $_POST['cbc'];
                        $_SESSION['formFname'] = $fname;
                        $_SESSION['formLname'] = $lname;       
                        $_SESSION['formHrsperweek'] = $hrsperweek;                 
                        $_SESSION['formLinkedin'] = $linkedin;
                        if(!validateSignUpInput($pdo, $username, $pin, $pinlength, $prehashpw, $email, $linkedin)){
                            displayVolunteerForm();
                        } else {
                            $pin = password_hash($_POST['pin'], PASSWORD_DEFAULT);
                            
                            // prepare statement insert into volunteer table.
                            insertVolunteersTable($pdo, $fname, $lname, $hrsperweek, $cbc, $linkedin);
                            
                            // prepare statement insert into users table.
                            insertUsersTable($pdo, $username, $email, $password, $type, $pin);                
                            
                            echo "<p class='success-msg'>Volunteer registered.</p>";      
                                                        
                            // unset variables                            
                            unsetVolFormVars();
                        }
                        break;
                }

                break;
            case (($_SERVER['REQUEST_METHOD'] === "POST" && $_POST['type'] === 'ngo')):
                displayNgoForm();
                break;
            case ($_SERVER['REQUEST_METHOD'] === "POST" && $_POST['type'] === 'volunteer'):
                displayVolunteerForm();
                break;
            default:
                displaySelectForm();
                break;
        }        
    }

?>

<!DOCTYPE html>
<html>
<head>
    <title>GuardianLink Register an account</title>
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
        <?php  signUp(); ?> 
    </div>
</main>
<footer>
    <p>© 2024 GuardianLink</p>
</footer>
</body>
</html>