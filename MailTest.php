      <?php
         $to = "vikranthjanjanam@gmail.com";
         $subject = "This is subject";
         
         $message = "This is HTML message.\n";
         $message .= "<h1>This is headline.\n"
                 . "Sent by PHP Program\n"
                 . "Developed by VikranthJanjanam</h1>";
         
         $retval = mail ($to,$subject,$message);
         
         if( $retval == true ) {
            echo "Message sent successfully...";
         }
         else {
            echo "Message could not be sent...<br>";
            print_r($_SESSION);
         }
      /*
$gender = $mysqli->escape_string($_POST['gender']);
                                $qual = $mysqli->escape_string($_POST['qual']);
                                $branch = $mysqli->escape_string($_POST['branch']);
                                $year = $mysqli->escape_string($_POST['year']);
                                $clg = $mysqli->escape_string($_POST['clg']);
                                $phone = $mysqli->escape_string($_POST['phone']);
                                $pass1 = $mysqli->escape_string(password_hash($_POST['pass'], PASSWORD_BCRYPT));
        
                                $insert = "INSERT INTO Student (`Name`, `UserID`, `Gender`, `Qualification`, `Branch`, `Year`, `College`, `Email`, `Phone`, `Password`)"
                                    . "VALUES ('$name', '$regId', '$gender', '$qual', '$branch', '$year', '$clg', '$email', '$phone', '$pass1');";
        
                                if ( $mysqli->query($insert) ){
                                    $errmsg="Succesfully created account.\nLogin to your Account.";
                                }
                                else{
                                    $errmsg="Falied To Register\nPlease Try Again Later.";
                                }
                             819176   */
         ?>