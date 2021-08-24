<?php

    //============================================
	// Github: github.com/developerzion
	// Author: Moses Samuel Zion
	// Website: www.devparse.com 
	//============================================

    //==================================================
	//PERFORMING CRUD OPERATION ON TXT FILE USING PHP
	//==================================================

    //Database 
    $database = "database.txt";
    //Create database "database.txt" if not exists  
    if(!file_exists($database)){
        touch($database);
    }

    
    //  
    if(isset($_POST['register'])){

        //Setting the timezone
        date_default_timezone_set("Africa/Lagos");
        //Generating random number for unique id
        $id = mt_rand();

        //Post values
        $email = $_POST['email'];
        $fullname = $_POST['fname'];
        $surname = $_POST['sname'];
        $gender = $_POST['gender'];
        $age = $_POST['age'];

        //Declaring a boolean variable to check if the email is available
        $emailexists = false; 

        //Declaring and initializing date
        $date = date('d-F-Y h:i:s');

        //Get the fields in the database text file and convert it into an array
        $array = file($database);
        
        for ($i=0; $i < count($array); $i++) {        
            $getdata = $array[$i];
            $each = explode("|", $getdata);
            $eachmail = $each[1];
            if($eachmail == $email){
                $emailexists = true;                
            }
        }

        if($emailexists){
            echo message("Email address has been taken","danger");
        }else{
            $data = array($id,$fullname,$surname,$gender,$age);
            $data = $id."|".$email."|".$fullname."|".$surname."|".$gender."|".$age."\n";            

            $file = fopen($database, "a");
            if(fwrite($file, $data)){
                echo message("Registration Successful","success");
                fclose($file);
            }else{
                echo message("Error occured: Unable to register account","danger");
            }               
        }
    }
    //==========================================
    //------------START DELETE RECORD-----------
    //==========================================
    if (isset($_POST['delRecord'])) {
        $a = file($database); 
        $rowid = $_POST['rowid'];

        $newlist = "";

        for ($i=0; $i < count($a); $i++) {        
            $row = $a[$i];
            $field = explode("|", $row);
            $id = $field[0];
            if($id == $rowid){   
                unset($a[$i]);
                sort($a);
                for ($j=0; $j < count($a) ; $j++) { 
                    $newlist .= $a[$j];
                }
            }                    
        }

        $file = fopen($database, "w");
        if(fwrite($file, $newlist)){
            echo message("Record Deleted","success");
            fclose($file);
        }               
    }
    //==========================================
    //----------STOP DELETE RECORD--------------
    //==========================================

    if (isset($_POST['updateRecord'])) {

        $a = file($database); 
        $rowid = $_POST['rowid'];

        $newlist = "";

        //Post values
        $upemail = "";
        $upfirstname = "";
        $upsurname = "";
        $upgender = "";
        $upage = "";

        for ($i=0; $i < count($a); $i++) {        
            $row = $a[$i];
            $field = explode("|", $row);
            $id = $field[0];
            if($id == $rowid){

                $eachdata =explode("|", $a[$i]);

                $upid = $eachdata[0];
                $upemail = $eachdata[1];
                $upfirstname = $eachdata[2];
                $upsurname = $eachdata[3];
                $upgender = $eachdata[4];
                $upage = $eachdata[5];
            }                    
        }           
    }

    if (isset($_POST['updateRecords'])) {

        $a = file($database); 
        

        $myid = $_POST['myid'];
        $email = $_POST['email'];
        $fullname = $_POST['fname'];
        $surname = $_POST['sname'];
        $gender = $_POST['gender'];
        $age = $_POST['age'];
        
        $data = $myid."|".$email."|".$fullname."|".$surname."|".$gender."|".$age."\n"; 
        
        for ($i=0; $i < count($a); $i++) {        
            $getdata = $a[$i];
            $each = explode("|", $getdata);
            $eachmail = $each[1];
            if($eachmail == $email){
                $a[$i] = $data;
            }
        }

        $updaterow = "";

        for ($i=0; $i < count($a); $i++) {        
            $updaterow .= $a[$i];                            
        } 

        $file = fopen($database, "w");
        if(fwrite($file, $updaterow)){
            echo message("Record Updated","success");
            fclose($file);
        }   
    }

    function message($msg, $status){
        return "<div class='col-lg-7 col-lg-offset-2'><div class='alert alert-$status'>$msg</div></div>";
    }

    

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="css/bootstrap.css">
    <title>Performing CRUD operation on a txt file</title>
</head>
<body>
<center>
<div class="row">
    <div class="col-lg-7 col-lg-offset-2">
    <h3>Performing CRUD operation on txt file using PHP </h3>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

            <input type="hidden" name="myid" value="<?php if(isset($upid)){ echo $upid; } ?>">

            <table class="table table-bordered table-hover ">
                <tr>
                    <td>Email:</td>
                    <td><input class="form-control" type="text" value="<?php if(isset($upemail)){ echo $upemail; } ?>" name="email" required <?php if (isset($_POST['updateRecord'])) { echo "readonly"; } ?>></td>
                </tr>
                <tr>
                    <td>Firstname:</td>
                    <td><input class="form-control" type="text" value="<?php if(isset($upfirstname)){ echo $upfirstname; } ?>" name="fname" required></td>
                </tr>
                <tr>
                    <td>Surname:</td>
                    <td><input type="text" class="form-control" name="sname" value="<?php if(isset($upsurname)){ echo $upsurname; } ?>" required></td>
                </tr>
                <tr>
                    <td>Gender:</td>
                    <td>
                        <select name="gender" required class="form-control">
                            <?php if(isset($upgender)){ ?>    
                            <option><?php echo $upgender; ?></option>
                            <?php }else{ ?>
                            <option value="">-- Select Gender --</option>
                            <?php } ?>
                            <option>Male</option>
                            <option>Female</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Age:</td>
                    <td><input type="number" class="form-control" value="<?php if(isset($upage)){ echo $upage; } ?>" name="age" required></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <?php if(isset($_POST['updateRecord'])){ ?>
                            <button class="btn btn-info"  name="updateRecords">Update</button>
                        <?php }else{ ?>
                            <button class="btn btn-success" name="register">Register</button>
                        <?php } ?>
                    </td>
                </tr>
            </table>
        </form>


        <h3>Records</h3> 
        <table class="table table-bordered table-hover">

            <tr>
                <td>Id</td>
                <td>Email</td>
                <td>Firstname</td>
                <td>Surname</td>
                <td>Gender</td>
                <td>Age</td>
                <td>Action</td>
            </tr>
            
            <?php

                //======================================================
                //------------START INSERT RECORDS INTO TABLE-----------
                //======================================================

                $array = file($database);  
                if(count($array) < 1){
                    echo "<tr><td colspan='7' style='text-align:center'>No record found</td></tr>";
                }else{           
                    for ($i=0; $i < count($array); $i++) {        
                        $getdata = $array[$i];
                        $each = explode("|", $getdata);
                        
                        //Get data from each row
                        $id = $each[0];
                        $email = $each[1];
                        $firstname = $each[2];
                        $surname = $each[3];
                        $gender = $each[4];
                        $age = $each[5];

                        ?>
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                            <tr>
                                <td><?php echo $id; ?></td>
                                <td><?php echo $email; ?></td>
                                <td><?php echo $firstname; ?></td>
                                <td><?php echo $surname; ?></td>
                                <td><?php echo $gender; ?></td>
                                <td><?php echo $age; ?></td>
                                <td>
                                    <input type="hidden" value="<?php echo $id; ?>" name="rowid">
                                    <button name="delRecord" class="btn btn-danger">X</button>                        
                                    <button name="updateRecord" class="btn btn-info">UPDATE</button>                        
                                </td>
                            </tr>
                        </form>
                        <?php
                    }
                }

                //======================================================
                //------------STOP INSERT RECORDS INTO TABLE-----------
                //======================================================    
            ?>

        </table>        
    </div> 
</div>
<a target="_blank" href="https://github.com/developerzion">Github</a> | <a target="_blank" href="https://www.youtube.com/channel/UCe0t7d9o4kd9gc9_sdUL_TA">Youtube Channel</a>
</center>

</body>
</html>
<?php



?>

