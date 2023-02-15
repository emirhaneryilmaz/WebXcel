<?php

include 'config.php';
require 'script.php';

pageHeader();

 $alphabet = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
 $num_per_page=1;
 $sid;
 $fid;

// lastModified UPDATE
  $fid = mysqli_real_escape_string($conn,$_GET['fid']);
  mysqli_query($conn , "UPDATE `files` SET `lastModified` = now() WHERE `fid` = $fid");
// 


function getName($conn){
  $fid = mysqli_real_escape_string($conn,$_GET['fid']);
  $result = mysqli_query($conn,"SELECT fname FROM files WHERE `fid`=$fid");
  $row = mysqli_fetch_assoc($result);
  return $row['fname'];
}
 
    if(isset($_GET["page"])){
        $page=$_GET["page"];
    }
    else{
        $page=1;
    }

    $start_from=($page-1)*1;
    $sorgu= "SELECT * FROM sheets WHERE fid='$_GET[fid]' LIMIT $start_from,$num_per_page";
    $sonuc2 = mysqli_fetch_assoc($conn->query("SELECT COUNT(*) FROM sheets WHERE fid=".$_GET['fid']))["COUNT(*)"];
  
    $sonuc = $conn->query($sorgu);
    $total_records=mysqli_num_rows($sonuc);
// 974x725 body
echo "<div id=container>";
echo "<div id=content>";
echo "<table id='my-table'>
<tr id='row-id'>";
echo "<td colspan='1'><center><a href='index.php' style='width:100px;height:50px' class='btn btn-outline-secondary'>←</a></center></td>
<td colspan='10'>".getName($conn)."</td>";
echo "</tr>";

$sql= "SELECT * FROM sheets WHERE fid='$_GET[fid]'";

$result = $conn->query($sorgu);

while($row = mysqli_fetch_assoc($result)) {
  
    global $sid;
    global $fid;
    $sid = $row['sid'];
    $fid = $row['fid'];

  echo "<tr id='columnCount'><td></td>";
  for($k=0; $k<$row['cols']; $k++){
    $kk = $k+1;
    echo "<td style='text-align:center;' id='0,$kk'>$alphabet[$k]</td>";
  }
  echo "</tr>";
    
    for($i=1;$i<=$row['rows'];$i++ ){
      echo "<tr><td style='text-align:center;width:100px;height:50px' class='rows'>$i</td>";
        for($j=1; $j<=$row['cols'];$j++){
           echo "<td><input type='text' id='$i,$j' style='height:50px;' onclick='check(this.value)' onchange='add(this.value,$i,$j,$sid)' value=''></td>";
        }
        echo "</tr>";
    }
}
echo "</div>";
echo "<tr>";
echo "<td><center><a href='' onclick='addSheet($fid)'>+</a></td>";

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

for($i=1; $i<=$sonuc2; $i++){
    echo "<td style='padding:1'><a href='?fid=". $_GET['fid']."&page=".$i."'>Sheet-$i</a></td>";
}
"</tr>";
echo "</table>";

  $kayitKumesi1 = mysqli_query($conn, $sql = "SELECT * FROM cell WHERE sid='$sid';") or die(mysqli_error($conn));
	
  while($kayit = mysqli_fetch_assoc($kayitKumesi1)){
    echo "<script type=\"text/javascript\">
    document.getElementById('{$kayit['row']},{$kayit['col']}').value = '{$kayit['data']}';
     </script>";
  }
  echo "</div>";


  echo "<div id='contextmenu'>
  <div class='list'>
    <div onclick='deleteColumn($sid,$fid)' class='item'>
      <i class='fa fa-magic'></i>
      Delete Column
    </div>
    <div onclick='deleteRow($sid,$fid)' class='item'>
      <i class='fa fa-plus-square-o'></i>
      Delete Row
    </div>
  </div>
  <div class='list'>
    <div onclick='clearColumn($sid,$fid)' class='item'>
      <i class='fa fa-pencil-square-o'></i>
      Clear column
    </div>
    <div onclick='clearRow($sid,$fid)' class='item'>
      <i class='fa fa-clone'></i>
      Clear row
    </div>
    <div onclick='insertColumnLeft($sid,$fid)' class='item'>
      <i class='fa fa-plus-trash'></i>
      Insert 1 column left
    </div>
    <div onclick='insertColumnRight($sid,$fid)' class='item'>
    <i class='fa fa-plus-trash'></i>
    Insert 1 column right
    </div>
    <div onclick='insertRowAbove($sid,$fid)' class='item'>
    <i class='fa fa-plus-trash'></i>
   Insert 1 row above
    </div>
    <div onclick='insertRowBelow($sid,$fid)' class='item'>
    <i class='fa fa-plus-trash'></i>
    Insert 1 row below
    </div>
 </div>
</div>";

pageBottom();

function pageHeader(){
    echo <<< pageHeader

    <html>
    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

        <script>

        </script>

        <style>

       

        div#container { height: 725px; width: 100%; overflow: scroll; }
        div#content { height: 725px;}
        
        div::-webkit-scrollbar {
            width: 12px;
        }
        
        div::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
            border-radius: 10px;
        }
        
        div::-webkit-scrollbar-thumb {
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5);
        }

          td{
            border: 0.5px solid black;
            border-collapse: collapse;
            width:100px;
            height:50px;
            padding:0;
            text-align:center;
          }
          
          #row-id{
           
          }
          #my-table{
            margin-top:0%;
            margin-bottom:0%;
            border-collapse: collapse;
            
          }
          #contextmenu {
            position:fixed;
            top:10px;
            left:10px;
            background:#ffff;
            width:250px;
            box-shadow:2px 2px 5px 2px rgba(0,0,0,0,05);
            visibility:hidden;
            transform-origin:top left;
          }
          #contextmenu.active{
            transform.scale(1);
            transition:transform 250ms ease-in-out;
          }
        
          #contextmenu .list {
            border-bottom:1px solid #eee;
          }
          #contextmenu .list .item {
            position:relative;
            padding:10px;
            font-size:14px;
            color:#555;
            cursor:pointer;
          }
          #contextmenu .list .item i{
            display:inline-block;
            width:20px;
            margin-right:5px;
          }
          #contextmenu .list .item:before{
            content:"";
            position:absolute;
            top:0px;
            left:0px;
            width:100%;
            height:100%;
            background:#eee;
            z-index:-1;
            transition:all 50ms ease-in-out;
          }
          .item:hover{
            background:black;
            color:white;
          }
          
        </style>
    </head>
       <body>
       
    
    pageHeader;
}
function pageBottom(){
    echo <<< pageBottom
      
    </body>
    </html>

    pageBottom;
}

?>