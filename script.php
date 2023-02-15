<script type="text/javascript">
  row_nr = 0;
  col_nr = 0;
  var control = 0;
  var deletecol = 0;
  var col;
  var row;
  var id;
  var rowCount;
  var alphabet = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ'];


  window.addEventListener("contextmenu", function(event) {
    event.preventDefault();
    let x = event.clientX + "px";
    let y = event.clientY + "px";
    let contextmenu = document.querySelector("#contextmenu");

    id = event.target.id;
    console.log(id);
    //1 ,'den sonra sağ tarafı alıyor
    //0 ,'den sonra sol tarafı
    col = Number(id.split(',')[1]);
    row = Number(id.split(',')[0]);

    contextmenu.style.top = y;
    contextmenu.style.left = x;
    contextmenu.style.visibility = 'visible';

  });

  window.addEventListener("click", function(event) {
    let contextmenu = document.querySelector("#contextmenu");
    contextmenu.style.visibility = 'hidden';
  });

  function newFile(){
    document.getElementById('newFile').style.display='';
  }

  function insertFile(){
    fname = document.getElementById('insertFileInput').value;
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", 'operations.php?fname='+ fname +'&op=insertFile', true);

    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        console.log(fname);
 
      }
    };

    xhttp.send();
  }

  function add(value, row, col, sid) {

    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", 'operations.php?row=' + row + '&col=' + col + '&data=' + value + '&sid=' + sid + '&op=' + control, true);

    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        console.log(this.responseText);
        console.log(value);
      }
    };

    xhttp.send();
  }

  function check(value) {
    if (value == "") {
      control = "add";
    } else {
      control = "update";
    }
  }

  function deleteColumn(sid, fid) {

    var columnCount = document.getElementById('columnCount').childElementCount;
    var rowCount = document.getElementsByClassName('rows').length;
    
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", 'operations.php?col=' + col + '&sid=' + sid + '&op=deleteColumn&fid=' + fid + '&columnCount=' + columnCount, true);

    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        for (var i = col+1; i < columnCount; i++) {
          document.getElementById("my-table").children[0].children[1].children[i].innerHTML = alphabet[i-2];

        }

        for (var i = 0; i <= rowCount; i++) {
          document.getElementById("my-table").children[0].children[i + 1].children[col].remove();

        }
      }
    };
    xhttp.send();
  }

  function deleteRow(sid, fid) {
    var columnCount = document.getElementById('columnCount').childElementCount;
    var rowCount = document.getElementsByClassName('rows').length;

    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", 'operations.php?row=' + row + '&sid=' + sid + '&op=deleteRow&fid=' + fid + '&rowCount=' + rowCount, true);
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {

        document.getElementById("my-table").children[0].children[row + 1].remove();


        for (var i = row + 1; i <= rowCount; i++) {
          var q = document.getElementById("my-table").children[0].children[i].children[0].innerHTML;
          document.getElementById("my-table").children[0].children[i].children[0].innerHTML = Number(q) - 1;
          for (var j = 1; j < columnCount; j++) {
            document.getElementById("my-table").children[0].children[i].children[j].children[0].setAttribute('id', (i - 1) + ',' + j);
            document.getElementById("my-table").children[0].children[i].children[j].children[0].setAttribute('onclick', 'check(this.value)');
            document.getElementById("my-table").children[0].children[i].children[j].children[0].setAttribute('onchange', 'add(this.value,' + (i - 1) + ',' + j + ',' + sid + ')');

          }
        }
      }
    };
    xhttp.send();
  }

  function clearColumn(sid, fid) {
    var rowCount = document.getElementsByClassName('rows').length;

    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", 'operations.php?col=' + col + '&sid=' + sid + '&op=clearColumn&fid=' + fid + '&rowCount=' + rowCount, true);
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        for (var i = 0; i <= rowCount; i++) {
          console.log(i);

          document.getElementById("my-table").children[0].children[i + 2].children[col].children[0].value = "";

        }
      }
    };
    xhttp.send();
  }

  function clearRow(sid, fid) {

    var columnCount = document.getElementById('columnCount').childElementCount;
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", 'operations.php?row=' + row + '&sid=' + sid + '&op=clearRow&fid=' + fid + '&columnCount=' + columnCount, true);
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {

        var numberRow = Number(row);
        for (var i = 1; i < columnCount; i++) {
          document.getElementById("my-table").children[0].children[numberRow + 1].children[i].children[0].value = "";
        }
      };
    }
    xhttp.send();
  }

  function addSheet(fid) {

    var a = document.getElementById("my-table").children[0].lastChild.lastChild.innerHTML;
    var b = a.split("-")[1];
    var c = b.split("<")[0]
    var d = Number(c);
    var sname = "Sheet-" + (d + 1);

    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", 'operations.php?sname=' + sname + '&op=addSheet&fid=' + fid, true);

    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("my-table").children[0].lastChild.appendChild(sname);
      };
    }

    xhttp.send();

  }

  function insertColumnLeft(sid, fid) {

    var columnCount = document.getElementById('columnCount').childElementCount;
    var rowCount = document.getElementsByClassName('rows').length;
    console.log(rowCount);

    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", 'operations.php?col=' + col + '&sid=' + sid + '&row=' + row + '&op=insertColumnLeft&fid=' + fid + '&columnCount=' + columnCount + '&rowCount=' + rowCount, true);

    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {


        for (var i = col; i < columnCount; i++) {
          document.getElementById("my-table").children[0].children[1].children[i].innerHTML = alphabet[i];

        }
        for (var i = 1; i <= rowCount; i++) {
          for (var j = col; j < columnCount; j++) {
            console.log(i + " " + j + " " + rowCount);
            document.getElementById("my-table").children[0].children[i + 1].children[j].children[0].setAttribute('id', i + ',' + (j + 1));
            document.getElementById("my-table").children[0].children[i + 1].children[j].children[0].setAttribute('onclick', 'check(this.value)');
            document.getElementById("my-table").children[0].children[i + 1].children[j].children[0].setAttribute('onchange', 'add(this.value,' + i + ',' + (j + 1) + ',' + sid + ')');

          }
        }

        var table = document.getElementById('my-table');
        console.log(col);

        var cell = table.children[0].children[1].insertCell(col);
        cell.innerHTML = alphabet[col - 1];
        for (var i = 1; i <= rowCount; i++) {
          var cell = table.children[0].children[i + 1].insertCell(col);
          cell.innerHTML = "<input type='text'id='" + (i) + "," + (col) + "' style='height:50px;' onclick='check(this.value)' onchange='add(this.value," + i + "," + (col) + "," + sid + ")'>";
        }

      }
    };
    xhttp.send();

  }

  function insertColumnRight(sid, fid) {

    var columnCount = document.getElementById('columnCount').childElementCount;
    var rowCount = document.getElementsByClassName('rows').length;
    console.log(rowCount);

    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", 'operations.php?col=' + col + '&sid=' + sid + '&row=' + row + '&op=insertColumnRight&fid=' + fid + '&columnCount=' + columnCount + '&rowCount=' + rowCount, true);

    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {

        for (var i = col+1; i < columnCount; i++) {
          document.getElementById("my-table").children[0].children[1].children[i].innerHTML = alphabet[i];

        }


        for (var i = 1; i <= rowCount; i++) {
          for (var j = col + 1; j < columnCount; j++) {
            console.log(i + " " + j + " " + rowCount);
            document.getElementById("my-table").children[0].children[i + 1].children[j].children[0].setAttribute('id', i + ',' + (j + 1));
            document.getElementById("my-table").children[0].children[i + 1].children[j].children[0].setAttribute('onclick', 'check(this.value)');
            document.getElementById("my-table").children[0].children[i + 1].children[j].children[0].setAttribute('onchange', 'add(this.value,' + i + ',' + (j + 1) + ',' + sid + ')');

          }
        }


        var table = document.getElementById('my-table');
        console.log(col);

        var cell = table.children[0].children[1].insertCell(col + 1);
        cell.innerHTML = alphabet[col];
        for (var i = 1; i <= rowCount; i++) {
          var cell = table.children[0].children[i + 1].insertCell(col + 1);
          cell.innerHTML = "<input type='text'id='" + (i) + "," + (col + 1) + "' style='height:50px;' onclick='check(this.value)' onchange='add(this.value," + i + "," + (col + 1) + "," + sid + ")'>";
          cell.setAttribute('id', 656);
        }

      }
    };
    xhttp.send();

  }

  function insertRowAbove(sid, fid) {


    var columnCount = document.getElementById('columnCount').childElementCount;
    var rowCount = document.getElementsByClassName('rows').length;

    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", 'operations.php?col=' + col + '&sid=' + sid + '&row=' + row + '&op=insertRowAbove&fid=' + fid + '&columnCount=' + columnCount + '&rowCount=' + rowCount, true);

    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        console.log("row = " + row + "numberli= " + Number(row));

        for (var i = Number(row) + 1; i <= rowCount + 1; i++) {
          var q = document.getElementById("my-table").children[0].children[i].children[0].innerHTML;
          console.log(Number(q));
          document.getElementById("my-table").children[0].children[i].children[0].innerHTML = Number(q) + 1;
          for (var j = 1; j < columnCount; j++) {
            document.getElementById("my-table").children[0].children[i].children[j].children[0].setAttribute('id', i + ',' + j);
            document.getElementById("my-table").children[0].children[i].children[j].children[0].setAttribute('onclick', 'check(this.value)');
            document.getElementById("my-table").children[0].children[i].children[j].children[0].setAttribute('onchange', 'add(this.value,' + i + ',' + j + ',' + sid + ')');

          }
        }

        var table = document.getElementById('my-table');

        var newrow = table.insertRow(Number(row) + 1);
        var cell = newrow.insertCell();
        cell.innerHTML = Number(row);
        cell.setAttribute('class', 'rows');
        for (var i = 1; i < columnCount; i++) {
          var cell = newrow.insertCell();
          cell.innerHTML = "<input type='text'id='" + (row) + "," + i + "' style='height:50px;' onclick='check(this.value)' onchange='add(this.value," + (row) + "," + i + "," + sid + ")'>";
          cell.setAttribute('id', 656);
        }

      }
    };
    xhttp.send();


  }

  function insertRowBelow(sid, fid) {

    var columnCount = document.getElementById('columnCount').childElementCount;
    var rowCount = document.getElementsByClassName('rows').length;

    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", 'operations.php?col=' + col + '&sid=' + sid + '&row=' + row + '&op=insertRowBelow&fid=' + fid + '&columnCount=' + columnCount + '&rowCount=' + rowCount, true);

    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        console.log("row = " + row + "numberli= " + Number(row));

        for (var i = Number(row) + 2; i <= rowCount + 1; i++) {
          var q = document.getElementById("my-table").children[0].children[i].children[0].innerHTML;
          console.log(Number(q));
          document.getElementById("my-table").children[0].children[i].children[0].innerHTML = Number(q) + 1;
          for (var j = 1; j < columnCount; j++) {
            document.getElementById("my-table").children[0].children[i].children[j].children[0].setAttribute('id', i + ',' + j);
            document.getElementById("my-table").children[0].children[i].children[j].children[0].setAttribute('onclick', 'check(this.value)');
            document.getElementById("my-table").children[0].children[i].children[j].children[0].setAttribute('onchange', 'add(this.value,' + i + ',' + j + ',' + sid + ')');

          }
        }

        var table = document.getElementById('my-table');

        var newrow = table.insertRow(Number(row) + 2);
        var cell = newrow.insertCell();
        cell.innerHTML = Number(row) + 1;
        cell.setAttribute('class', 'rows');

        for (var i = 1; i < columnCount; i++) {
          var cell = newrow.insertCell();
          cell.innerHTML = "<input type='text'id='" + (row + 1) + "," + i + "' style='height:50px;' onclick='check(this.value)' onchange='add(this.value," + (row + 1) + "," + i + "," + sid + ")'>";
        }

      }
    };
    xhttp.send();

  }
</script>