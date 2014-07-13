<?php
    include_once('server_config.php');
    
    //検索時刻範囲の取得
    $time1=filter_input(INPUT_GET,"time1",FILTER_SANITIZE_NUMBER_INT);
    $time2=filter_input(INPUT_GET,"time2",FILTER_SANITIZE_NUMBER_INT);

    $timestr1="cast('".$time1."' as datetime)";
    $timestr2="cast('".$time2."' as datetime)";
    
    //データベースからカウントを取得
    $cn=mysqli_connect($sql_server, $sql_user, $sql_pw, $sql_db)
    or die("failed:".mysqli_error($cn));
    
    $result=mysqli_query($cn,"SELECT * FROM UserCount "
            . "WHERE Time BETWEEN ".$timestr1."AND ".$timestr2
            ." ORDER BY Time ASC");
     
    //XMLデータの出力
    header("Content-type:text/xml");
    $writer=new XMLWriter();
    $writer->openMemory();
    
    $writer->startDocument();
    $writer->setIndent(true);
    
    $writer->startElement('UserCounts');
    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
        $writer->startElement("Count");
        $writer->writeAttribute('Time', date("YmdHis",strtotime($row['Time'])));
        $writer->writeRaw($row['Count']);
        $writer->endElement();
    }
    $writer->endElement();
    
    echo $writer->outputMemory();
    
