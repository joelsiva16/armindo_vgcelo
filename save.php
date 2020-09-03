<?php

    include 'inc/config.inc';

    $i = $_GET['i'];

    switch($i){

        case 1:

        $name = $_POST['name'];
        $password = $_POST['password'];

        $encpassword = hash('sha256', $password);

        $sql_i = "INSERT INTO users (name, password) VALUES ('$name', '$encpassword')";
        mysqli_query($conn, $sql_i);

        header("Location: index.php");

        break;

        case 2:

            $opp = $_POST['opp'];

            $sql_info = "SELECT name, vgc_elo FROM users
                        WHERE id = $opp";
            $result_info = mysqli_query($conn, $sql_info);
            $row_info = mysqli_fetch_array($result_info);

           $name = $row_info['name'];
           $elo = $row_info['vgc_elo'];

            echo "<h2>".$name."</h2><h4>".$elo."</h4>";
          
        break;
    
    }

?>