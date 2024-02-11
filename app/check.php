<?php

if(isset($_POST['id'])){
    require '../db.php';

    $id = $_POST['id'];

    if(empty($id)){
       echo 'error';
    }else {
        $todolist = $conn->prepare("SELECT id, checked FROM todolist WHERE id=?");
        $todolist->execute([$id]);

        $todo = $todolist->fetch();
        $uId = $todo['id'];
        $checked = $todo['checked'];

        $uChecked = $checked ? 0 : 1;

        $res = $conn->query("UPDATE todolist SET checked=$uChecked WHERE id=$uId");

        if($res){
            echo $checked;
        }else {
            echo "error";
        }
        $conn = null;
        exit();
    }
}else {
    header("Location: ../index.php?mess=error");
}