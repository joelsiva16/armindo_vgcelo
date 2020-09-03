<?php

    session_start();

    include 'inc/config.inc';

    $user_id = $_SESSION['user'];

    $result = $_POST['radio'];

    if($result == 'W'){
        $result_opp = 'L';
    }else{
        $result_opp = 'W';
    }

    $myelo = $_POST['myelo'];

    $opponent = $_POST['sel_opponent'];
    
    $sql_get_opp_elo = "SELECT vgc_elo
                        FROM users
                        WHERE id = $opponent";
    $result_get_opp_elo = mysqli_query($conn, $sql_get_opp_elo);
    $row_get_opp_elo = mysqli_fetch_array($result_get_opp_elo);
    $opp_elo = $row_get_opp_elo['vgc_elo'];


    $rank1 = $myelo;
    $rank2 = $opp_elo;

    $k = 30;


    $q1 = pow(10, ($rank1/800));
    $q2 = pow(10, ($rank2/800));

    $elo1 = $q1 / ($q1 + $q2);
    $elo2 = $q2 / ($q1 + $q2);

    if($result == 'W'){

        $newrank1 = $rank1 + $k * (1 - $elo1);
        $newrank2 = $rank2 + $k * (0 - $elo2);

    }else if($result == 'L'){

        $newrank1 = $rank1 + $k * (0 - $elo1);
        $newrank2 = $rank2 + $k * (1 - $elo2);
    }

   $sql_update_mine = "UPDATE users SET vgc_elo = $newrank1 WHERE id = $user_id";
   mysqli_query($conn, $sql_update_mine);

   $sql_update_opponent = "UPDATE users SET vgc_elo = $newrank2 WHERE id = $opponent";
   mysqli_query($conn, $sql_update_opponent);

   $sql_log_mine = "INSERT INTO elo_log (id_user, result, previous_elo, new_elo, timestamp) VALUES ($user_id, '$result', $rank1, $newrank1, now())";
   echo $sql_log_mine;
   mysqli_query($conn, $sql_log_mine);

   $sql_log_opp = "INSERT INTO elo_log (id_user, result, previous_elo, new_elo, timestamp) VALUES ($opponent, '$result_opp', $rank2, $newrank2, now())";
   mysqli_query($conn, $sql_log_opp);

   header("Location: index.php#table");

?>