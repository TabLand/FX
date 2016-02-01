<?php 
    $project_root = "/home/tab/FX";
    require "$project_root/simple_html_dom.php";
    
    exec("/usr/bin/phantomjs --ssl-protocol=any $project_root/rates.js", $result);
    $drivejoy = implode($result);
    file_put_contents("$project_root/rates",$drivejoy);

    $html = file_get_html("$project_root/rates");
    
    if($html){
        $rates = $html->find('div#rates_detail_desc', 0)->plaintext;
        mail("ijtabahussain@live.com", $rates, $rates, "FROM:fx-alerts@ijtaba.me.uk\r\n");
    } else {
        echo "\nSomething just went royally wrong, wait till next run";
    }
?>
