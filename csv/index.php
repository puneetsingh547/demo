<?php
$conn = mysqli_connect("localhost", "root", "puneet", "redcarpet");

if (isset($_POST["import"])) {
    
    $fileName = $_FILES["file"]["tmp_name"];
    
    if ($_FILES["file"]["size"] > 0) {
        
        $file = fopen($fileName, "r+");
        
        while (($column = fgetcsv($file, ",")) !== FALSE) {
            $sqlInsert = "INSERT into all_txn (profile_phone, date )
                   values ('" . $column[0] . "','" . $column[1] . "')";
            $result = mysqli_query($conn, $sqlInsert);
            echo mysqli_error($conn);
            
            if (! empty($result)) {
                $type = "success";
                $message = "CSV Data Imported into the Database";
            } else {
                $type = "error";
                $message = "Problem in Importing CSV Data";
            }
        }
    }
}
?>  
<!DOCTYPE html>
<html>

<head>
<script src="jquery-3.2.1.min.js"></script>
<link rel="stylesheet" href="style.css">
<script src="script.js"></script>
</head>

<body>
    <h2>Import CSV file into Mysql database</h2>
    
    <div id="response" class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>"><?php if(!empty($message)) { echo $message; } ?></div>
    <div class="outer-scontainer">
        <div class="row">

            <form class="form-horizontal" action="" method="post"
                name="frmCSVImport" id="frmCSVImport" enctype="multipart/form-data">
                <div class="input-row">
                    <label class="col-md-4 control-label">Choose CSV
                        File</label> <input type="file" name="file"id="file" accept=".csv" required>
                    <button type="submit" id="submit" name="import"
                        class="btn-submit">Import</button>
                    <br />

                </div>

            </form>

        </div>
    <h2>Import CSV file into Mysql database</h2>
    
   <!-- <div id="response" class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>"><?php if(!empty($message)) { echo $message; } ?></div> -->
    <div class="outer-scontainer">
        <div class="row">

            <form class="form-horizontal" action="" method="post"
                name="frmCSVImport" id="frmCSVImport" enctype="multipart/form-data">
                <div class="input-row">
                    <label class="col-md-4 control-label">Choose CSV
                        File</label> <input type="file" name="file"
                        id="file" accept=".csv">
                    <button type="submit" id="submit" name="import"
                        class="btn-submit">Import</button>
                    <br />

                </div>

            </form>

        </div>
<?php
            $sqlSelect = "SELECT * FROM users limit 2";
            $result = mysqli_query($conn, $sqlSelect);
            
            if (mysqli_num_rows($result) > 0) {
                ?>
            <table id='userTable'>
            <thead>
                <tr>
                    <th>RowNbr</th>
                    <th>TranDate</th>
                    <th>ValueDate</th>
                </tr>
            </thead>
 <?php
                
                while ($row = mysqli_fetch_array($result)) {
                    ?>
                <tbody>
                <tr>
                    <td><?php  echo $row['txn_id']; ?></td>
                    <td><?php  echo $row['profile_phone']; ?></td>
                    <td><?php  echo $row['date']; ?></td>
                </tr>
                    <?php
                }
                ?>
                </tbody>
        </table>
        <?php } ?> 
    </div>
    <!-- download csv -->
    <div class="container">
 
    <form method='post' action='download.php'>
        <input type='submit' value='Download csv' name='Export'>
 
            <table border='1' style='border-collapse:collapse;'>
                <tr>
                    <th>RowNbr</th>
                    <th>TranDate</th>
                    <th>ValueDate</th>
                    
                </tr>
    <?php 
     $query = "SELECT * FROM all_txn limit 2";
     $result = mysqli_query($conn,$query);
     $user_arr = array();
     while($row = mysqli_fetch_array($result)){
      
      $user_arr[] = array($row['phone_no'],$row['current_bal'],$row['date']);
   ?>
            <tr>
                    <td><?php  echo $row['phone_no']; ?></td>
                    <td><?php  echo $row['current_bal']; ?></td>
                    <td><?php  echo $row['date']; ?></td>
                    
             </tr>
   <?php
    }
   ?>
   </table>
   <?php 
    $serialize_user_arr = serialize($user_arr);
   ?>
  <textarea name='export_data' style='display: none;'><?php echo $serialize_user_arr; ?></textarea>
 </form>

</body>

</html>