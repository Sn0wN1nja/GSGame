<?php
header('Content-type:text/html;charset=utf-8');
$link = mysqli_connect('127.0.0.1','root', '', 'abubakarov');
if($_POST['kavo']=='reg') {
    $values1 = array($_POST["name"],$_POST["email"],$_POST["password"],$_POST["city"],intval($_POST["game_fav"]),$_POST["birth_date"],$_POST["about"],date("Y-m-d"),intval($_POST["admin"]));
    $values = ("\"$values1[0]\",\"$values1[1]\",\"$values1[2]\",\"$values1[3]\",$values1[4],\"$values1[5]\",\"$values1[6]\",\"$values1[7]\",$values1[8]");
    print($values);
}

if(1){
    $query = $_POST['query'];
    $table = $_POST['table'];

    if($_POST['where']){
        $where = $_POST['where'];
    }
    if($_POST['limit']){
        $limit = $_POST['limit'];
    }
    if($_POST['fields']){
        $fields = $_POST['fields'];
    }
    else{
        $fields = "*";
    }
    if($_POST['values']){
        $values = $_POST['values'];
    }
    $data = [
        'table' => $table,
        'fields' => $fields,
        'values' => $values,
        'where' => $where,
        'limit' => $limit,
    ];

    switch($query){
        case 'insert': insert($data); break;
        case 'select': select($data); break;
        case 'update': update($data); break;
        case 'delete': delete($data); break;
    }

}

function insert($data){
    global $link;
    $sql = " INSERT INTO ".$data['table'].'('.$data['fields'].') VALUES ('.$data['values'].')';
    echo($sql);
    $link->query($sql);
}


function select($data){
    global $link;

    $sql = 'SELECT '.$data['fields'].' FROM '.$data['table'];

    if($data['where']){
        $sql = $sql . ' WHERE '.$data['where'];
    }
    if($data['limit']){
        $sql = $sql . ' LIMIT '.$data['limit'];
    }
    $res = $link->query($sql);
    while($row = $res->fetch_assoc()){
        $result[] = $row;
    }
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}

function update($data){
    global $link;

    $fields = explode(',', $data['fields']);
    $values = explode(',', $data['values']);

    foreach($fields as $key => $value){
        $update_values = $update_values . $value.'='.$values[$key].',';
    }
    $update_values = substr($update_values, 0, -1);

    $sql = "UPDATE ".$data['table']." SET ".$update_values;

    if($data['where']){
        $sql = $sql . ' WHERE '.$data['where'];
    }

    $link->query($sql);
 
}
function delete($data){
    global $link;

    $sql = "DELETE FROM ".$data['table'];

    if($data['where']){
        $sql = $sql . ' WHERE '.$data['where'];
    }
    $link->query($sql);
}

?>