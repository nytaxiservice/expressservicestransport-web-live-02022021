<?PHP

if(isset($_POST['submit']))
{
      $path = "";
      $validFiletype = 'pem';
      $fileName = basename( $_FILES['uploaded_file']['name']);

      if(!empty($fileName)){

        $fileToUpload = $path.$fileName;

        $ext = pathinfo($fileName, PATHINFO_EXTENSION);

        if($ext == $validFiletype){

          if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $fileToUpload)) {
            $action = 1;
            $msg = "<u>".  $fileName . "</u> has been uploaded";
          } else{
            $action = 0;
            $msg = "There was an error uploading the file, please try again!";
          }
        }else{
          $action = 0;
          $msg = "<u>".$fileName."</u> is not looks like valid 'pem' Certificates. Please check uploaded file.";
        }
      }
      else{
        $action = 0;
        $msg = "You haven't Selected any Certificate(s)";
      }
      
}
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="https://bootswatch.com/3/paper/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <title>iPhone Certificates uploading</title>
  <style>.well{margin-top: 200px;}.alert{width: 90%;}.left{text-align: left;font-style:italic;font-size:16px;}u{color: black;}.alert { width: 85%;border: 2px solid #e51c23;color: black;background-color: #f5e4e4;}.alert .close{color: black;}#doUpload{display: none;}u{color: red;}.note{font-size: 27px;margin: 0px;}</style>
</head>
<body>
  <div class="container-fluide">
    <div class="col-md-12">
        <h4>iPhone Developer's Certificates upload area</h4><hr>
        
        <?php
          if(isset($action)){ 
            $action = ($action == 1) ? 'success' : 'danger';
            if($action == 'success'){
              header( "refresh:3;url=uploadCertificate.php" );
            }
        ?>
        <div class="alertarea" align="center">
          <div class="alert alert-<?=$action?>">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <p class="left"><?=$msg?></p>
          </div>
        </div>
        <?php } ?>

        <form enctype="multipart/form-data" action="" method="POST" class="form-horizontal">
          <div class="form-group">
            <label class="col-lg-3 control-label">Please Select Your Certificate(pem only)</label>
            <div class="col-lg-4">
              <input type="file" style="margin-top: 7px;" name="uploaded_file" id="id_certi" onchange="checkSelectedFile()">
            </div>
            <div class="col-lg-2">
              <button type="submit" name="submit" id="doUpload" class="btn btn-sm btn-primary">Upload Certificate</button>
            </div>
          </div>
        </form>

        <div class="well">
          <ul>
            <li><code>The uploaded file being upload to root of your directory</code></li>
            <li><code>If File Is exist than it will be replaced with current file</code></li>
            <li><code>After Uploading Page will automatically refresh.Do next upload only after it.</code></li>
          </ul>
        </div>
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
      var fileName = $("#id_certi").val();
      var ext = fileName.split('.').pop();

      if(fileName!= '') {
        $("#doUpload").show();
      }
    }

</script>
</body>
</html>
