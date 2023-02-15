<?php

include 'config.php';
require 'script.php';

echo "<center>
<table id='my-table'>";
echo "<tr><th>My drive</th>
<th><a onclick='newFile()'>+New File</a></th></tr>

<div id='newFile' style='display:none'>
<form action='index.php' method='get'>
    <label>File name</label>
    <input type=text id='insertFileInput'>
    <button type='submit' onclick='insertFile()'>Insert</button>
</form>
</div>

<tr><td>File name</td>
<td>Last modified</td></tr>";

$sql2= "SELECT * FROM files";
    
$result2 = $conn->query($sql2);


while($row = mysqli_fetch_assoc($result2)) {
    $fid= $row['fid'];
    echo "<tr>
    <td><a href='excel.php?fid=$fid'>".$row["fname"]."</a></td>
    <td>".$row["lastModified"]."</td>
   </tr>";
   
} 



echo "</table></center>";

pageHeader();
pageBottom();

function pageHeader(){
    echo <<< pageHeader

    <html>
    <head>

    </head>

         <body>

            
 

         <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
         <style>

            tr,td,#my-table{
                border: 1px solid;
            }
            tr,td{
                border: 1px solid;
                padding:20;
            }
            #my-table{
                margin-top:9%;
               padding:5;
            }
            th{
                text-align:center;
                padding:8;
            }

         </style>

    pageHeader;
}
function pageBottom(){
    echo <<< pageBottom
      
    </body>
    </html>

    pageBottom;
}

?>