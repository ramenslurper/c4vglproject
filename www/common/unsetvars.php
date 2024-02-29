<?php
    
    function checkFormVars() {
        if(isset($_SESSION['formUsername'])){        
            unset($_SESSION['formUsername']);
        }

        if(isset($_SESSION['formOrg'])){
            unset($_SESSION['formOrg']);
        }

        if(isset($_SESSION['formEmail'])){
            unset($_SESSION['formEmail']);
        }

        if(isset($_SESSION['formPW'])){
            unset($_SESSION['formPW']);
        }

        if(isset($_SESSION['formPIN'])){
            unset($_SESSION['formPIN']);        
        }

        if(isset($_SESSION['formAreasOfConcern'])){
            unset($_SESSION['formAreasOfConcern']);    
        }

        if(isset($_SESSION['formFname'])){
            unset($_SESSION['formFname']);                
        }
            
        if(isset($_SESSION['formLname'])){
            unset($_SESSION['formLname']);    
        }

        if(isset($_SESSION['formHrsperweek'])){
            unset($_SESSION['formHrsperweek']);    
        }

        if(isset($_SESSION['formLinkedin'])){
            unset($_SESSION['formLinkedin']);    
        }        
    }
    
    function unsetNgoFormVars() {
        unset($_SESSION['formUsername']);
        unset($_SESSION['formOrg']);
        unset($_SESSION['formEmail']);
        unset($_SESSION['formPW']);
        unset($_SESSION['formPIN']);
        unset($_SESSION['formAreasOfConcern']);
    }

    function unsetVolFormVars() {
        unset($_SESSION['formUsername']);
        unset($_SESSION['formEmail']);
        unset($_SESSION['formPW']);
        unset($_SESSION['formPIN']);
        unset($_SESSION['formFname']);
        unset($_SESSION['formLname']); 
        unset($_SESSION['formHrsperweek']);
        unset($_SESSION['formLinkedin']);
    }

    function unsetSignUpErrors() {
        unset($_SESSION['pwError']);
        unset($_SESSION['userError']);
        unset($_SESSION['emailError']);
        unset($_SESSION['pinError']);
        unset($_SESSION['urlError']);
        unset($_SESSION['userLength']);
    }
?>
