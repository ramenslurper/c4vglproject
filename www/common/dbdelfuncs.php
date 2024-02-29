<?php    
    // delete user from ngos table.
    function delFromNgos($pdo, $ngo_id){
        try{
            $qry = $pdo->prepare('DELETE FROM ngos WHERE ngo_id=?');
            $qry->bindParam(1,$ngo_id);      
            $qry->execute();    
        }
        catch (PDOException $e){        
            echo "<p class='error-msg'>Failed to delete.</p>";                
        }
        $pdo = null;
    }
    
    // delete user from volunteers table.
    function delFromVolunteers($pdo, $vol_id){
        try{
            $qry = $pdo->prepare('DELETE FROM volunteers WHERE vol_id=?');
            $qry->bindParam(1,$vol_id);      
            $qry->execute();    
        }
        catch (PDOException $e){        
            echo "<p class='error-msg'>Failed to delete.</p>";                
        }
        $pdo = null;
    }

    // delete user from users table.
    function delFromUsers($pdo, $username){
        try{
            $qry = $pdo->prepare('DELETE FROM users WHERE username=?');
            $qry->bindParam(1,$username);                    
            $qry->execute([$username]);
        } 
        catch (PDOException $e){        
            echo "<p class='error-msg'>Failed to delete.</p>";   
            return false;             
        } finally {
            $pdo = null;
        }

        return true;
    }

    // return the ngo_id and vol_id used for deletion
    function getUserTypeIDs($pdo, $username){
        try{
            $qry = "SELECT ngo_id, vol_id FROM users WHERE username='" . $username . "'";
            $result = $pdo->query($qry);
        }
        catch (PDOException $e){        
            echo "<p class='error-msg'>Query failed.</p>";                
        }

        $row = $result->fetch();
        $ngo_id = $row['ngo_id'];
        $vol_id = $row['vol_id'];        
        
        return array('ngo_id' => $ngo_id, 'vol_id' => $vol_id);        
    }


?>