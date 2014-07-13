<?php
    include_once('server_config.php');
    
    //設定時刻を取得
    $time=filter_input(INPUT_POST,"time",FILTER_SANITIZE_NUMBER_INT);
    $timestr="cast('".$time."' as datetime)";
    
    //オプションを取得(1:書き込み 0:削除)
    $option=filter_input(INPUT_POST,"option",FILTER_SANITIZE_NUMBER_INT);
    
    //MySQLの操作
    
    $cn=mysqli_connect($sql_server, $sql_user, $sql_pw, $sql_db)
       or die("failed:".mysqli_error($cn));
    
    $result=mysqli_query($cn,"SELECT * FROM UserCount WHERE Time=".$timestr);
    $resultcnt=mysqli_num_rows($result);
    
    //書き込み
    if($option==1){
        if($resultcnt==0){
            mysqli_query($cn,"INSERT INTO UserCount VALUES (".$timestr.",0)");
        }
        mysqli_query($cn,"UPDATE UserCount SET Count=Count+1 WHERE Time=".$timestr);
    }
      
    //削除
    if($option==0){
        if($resultcnt>0){
            $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
            if($row["Count"]>1){
                mysqli_query($cn,"UPDATE UserCount SET Count=Count-1 WHERE Time=".$timestr);
            }else{
                mysqli_query($cn,"DELETE FROM UserCount WHERE Time=".$timestr);
            }
        }
    }
     
    mysqli_close($cn);
