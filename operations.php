<?php

require 'config.php';

if ($_GET['op'] == "add") {
    insert($conn);
} else if ($_GET['op'] == "update") {
    update($conn);
} else if ($_GET['op'] == "deleteColumn") {
    deleteColumn($conn);
    //row ve col datasını al -1 yap
    //fonksiyona git  
} else if ($_GET['op'] == "deleteRow") {
    deleteRow($conn);
    //row ve col datasını al -1 yap
    //fonksiyona git   
} else if ($_GET['op'] == "clearColumn") {
    clearColumn($conn);
} else if ($_GET['op'] == "clearRow") {
    clearRow($conn);
} else if ($_GET['op'] == "addSheet") {
    addSheet($conn);
} else if ($_GET['op'] == "insertColumnLeft") {
    insertColumnLeft($conn);
} else if ($_GET['op'] == "insertColumnRight") {
    insertColumnRight($conn);
} else if ($_GET['op'] == "insertRowAbove") {
    insertRowAbove($conn);
} else if ($_GET['op'] == "insertRowBelow") {
    insertRowBelow($conn);
} else if ($_GET['op'] == "insertFile"){
    insertFile($conn);
}

function insertFile($conn)
{
    $sname = mysqli_real_escape_string($conn, $_GET['sname']);
    $fid = mysqli_real_escape_string($conn, $_GET['fid']);
    $fname = mysqli_real_escape_string($conn, $_GET['fname']);
    mysqli_query($conn, $sql = "INSERT INTO `files` (`fname`) VALUES ('$fname')") or die(mysqli_error($conn) . 'sql komutu: ' . $sql);
    $fid = $conn->insert_id;
    mysqli_query($conn, $sql = "INSERT INTO `sheets` (`sid`, `sname`, `rows`, `cols`, `fid`) VALUES (NULL, 'Sheet-1', '10', '10', '$fid')") or die(mysqli_error($conn) . ' sql komutu: ' . $sql);

}

function insert($conn)
{
    $row = mysqli_real_escape_string($conn, $_GET['row']);
    $col = mysqli_real_escape_string($conn, $_GET['col']);
    $sid = mysqli_real_escape_string($conn, $_GET['sid']);
    $data = mysqli_real_escape_string($conn, $_GET['data']);
    mysqli_query($conn, $sql = "INSERT INTO cell (row, col , sid , data) VALUES ('$row', '$col', '$sid','$data')") or die(mysqli_error($conn) . ' sql komutu: ' . $sql);
}

function update($conn)
{
    $row = mysqli_real_escape_string($conn, $_GET['row']);
    $col = mysqli_real_escape_string($conn, $_GET['col']);
    $sid = mysqli_real_escape_string($conn, $_GET['sid']);
    $data = mysqli_real_escape_string($conn, $_GET['data']);

    if ($data != "")
        mysqli_query($conn, $sql = "UPDATE cell SET data='$data' WHERE sid='$sid' AND col='$col' AND row='$row'") or die(mysqli_error($conn) . ' sql komutu: ' . $sql);
    else
        mysqli_query($conn, $sql = "DELETE FROM cell WHERE sid='$sid' AND col='$col' AND row='$row'") or die(mysqli_error($conn) . ' sql komutu: ' . $sql);
}

function deleteColumn($conn)
{
    $col = mysqli_real_escape_string($conn, $_GET['col']);
    $sid = mysqli_real_escape_string($conn, $_GET['sid']);
    $fid = mysqli_real_escape_string($conn, $_GET['fid']);

    mysqli_query($conn, $sql = "DELETE FROM `cell` WHERE `sid`=$sid AND `col`=$col") or die(mysqli_error($conn) . ' sql komutu: ' . $sql);
    $columnCount = $_GET['columnCount'] - 2;
    mysqli_query($conn, $sql = "UPDATE `sheets` SET `cols`=$columnCount WHERE `sid`=$sid AND `fid`=$fid") or die(mysqli_error($conn) . ' sql komutu: ' . $sql);
    mysqli_query($conn, $sql = "UPDATE `cell` SET `col`=`col`-1 WHERE `sid`=$sid AND `col`>$col") or die(mysqli_error($conn) . ' sql komutu: ' . $sql);
}

function deleteRow($conn)
{
    $row = mysqli_real_escape_string($conn, $_GET['row']);
    $sid = mysqli_real_escape_string($conn, $_GET['sid']);
    $fid = mysqli_real_escape_string($conn, $_GET['fid']);

    mysqli_query($conn, $sql = "DELETE FROM `cell` WHERE `sid`=$sid AND `row`=$row") or die(mysqli_error($conn) . ' sql komutu: ' . $sql);
    $rowCount = mysqli_real_escape_string($conn, $_GET['rowCount'] - 1);
    mysqli_query($conn, $sql = "UPDATE `sheets` SET `rows`=$rowCount WHERE `sid`=$sid AND `fid`=$fid") or die(mysqli_error($conn) . ' sql komutu: ' . $sql);
    mysqli_query($conn, $sql = "UPDATE `cell` SET `row`=`row`-1 WHERE `sid`=$sid AND `row`>$row") or die(mysqli_error($conn) . ' sql komutu: ' . $sql);
}

function clearColumn($conn)
{
    $col = mysqli_real_escape_string($conn, $_GET['col']);
    $sid = mysqli_real_escape_string($conn, $_GET['sid']);

    mysqli_query($conn, $sql = "DELETE FROM `cell` WHERE `sid`=$sid AND `col`=$col") or die(mysqli_error($conn) . ' sql komutu: ' . $sql);
}

function clearRow($conn)
{
    $row = mysqli_real_escape_string($conn, $_GET['row']);
    $sid = mysqli_real_escape_string($conn, $_GET['sid']);

    mysqli_query($conn, $sql = "DELETE FROM `cell` WHERE `sid`=$sid AND `row`=$row") or die(mysqli_error($conn) . ' sql komutu: ' . $sql);
}

function addSheet($conn)
{
    $sname = mysqli_real_escape_string($conn, $_GET['sname']);
    $fid = mysqli_real_escape_string($conn, $_GET['fid']);

    mysqli_query($conn, $sql = "INSERT INTO `sheets` (`sid`, `sname`, `rows`, `cols`, `fid`) VALUES (NULL, '$sname', '10', '10', '$fid')") or die(mysqli_error($conn) . ' sql komutu: ' . $sql);
}

function insertColumnLeft($conn)
{
    $col = mysqli_real_escape_string($conn, $_GET['col']);
    $sid = mysqli_real_escape_string($conn, $_GET['sid']);
    $fid = mysqli_real_escape_string($conn, $_GET['fid']);

    $columnCount = $_GET['columnCount'];
    mysqli_query($conn, $sql = "UPDATE `sheets` SET `cols`=$columnCount WHERE `sid`=$sid AND `fid`=$fid") or die(mysqli_error($conn) . ' sql komutu: ' . $sql);
    mysqli_query($conn, $sql = "UPDATE `cell` SET `col`=`col`+1 WHERE `sid`=$sid AND `col`>=$col") or die(mysqli_error($conn) . ' sql komutu: ' . $sql);
}

function insertColumnRight($conn)
{
    $col = mysqli_real_escape_string($conn, $_GET['col']);
    $sid = mysqli_real_escape_string($conn, $_GET['sid']);
    $fid = mysqli_real_escape_string($conn, $_GET['fid']);

    $columnCount = $_GET['columnCount'];
    mysqli_query($conn, $sql = "UPDATE `sheets` SET `cols`=$columnCount WHERE `sid`=$sid AND `fid`=$fid") or die(mysqli_error($conn) . ' sql komutu: ' . $sql);
    mysqli_query($conn, $sql = "UPDATE `cell` SET `col`=`col`+1 WHERE `sid`=$sid AND `col`>$col") or die(mysqli_error($conn) . ' sql komutu: ' . $sql);
}

function insertRowAbove($conn)
{
    $row = mysqli_real_escape_string($conn, $_GET['row']);
    $sid = mysqli_real_escape_string($conn, $_GET['sid']);
    $fid = mysqli_real_escape_string($conn, $_GET['fid']);

    $rowCount = mysqli_real_escape_string($conn, $_GET['rowCount'] + 1);
    mysqli_query($conn, $sql = "UPDATE `sheets` SET `rows`=$rowCount WHERE `sid`=$sid AND `fid`=$fid") or die(mysqli_error($conn) . ' sql komutu: ' . $sql);

    mysqli_query($conn, $sql = "UPDATE `cell` SET `row`=`row`+1 WHERE `sid`=$sid AND `row`>=$row") or die(mysqli_error($conn) . ' sql komutu: ' . $sql);
}

function insertRowBelow($conn)
{
    $row = mysqli_real_escape_string($conn, $_GET['row']);
    $sid = mysqli_real_escape_string($conn, $_GET['sid']);
    $fid = mysqli_real_escape_string($conn, $_GET['fid']);

    $rowCount = mysqli_real_escape_string($conn, $_GET['rowCount'] + 1);
    mysqli_query($conn, $sql = "UPDATE `sheets` SET `rows`=$rowCount WHERE `sid`=$sid AND `fid`=$fid") or die(mysqli_error($conn) . ' sql komutu: ' . $sql);

    mysqli_query($conn, $sql = "UPDATE `cell` SET `row`=`row`+1 WHERE `sid`=$sid AND `row`>$row") or die(mysqli_error($conn) . ' sql komutu: ' . $sql);
}

?>