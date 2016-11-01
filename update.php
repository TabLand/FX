<?php

$project_root = "/home/tab/FX";
require "$project_root/simple_html_dom.php";

get_subscriptions($project_root);

function get_subscriptions($project_root){
    $subscriptions_file = "$project_root/subs";
    $subscriptions = file_get_contents($subscriptions_file);

    $currency_pairs = preg_split("/\n/",$subscriptions);
    
    foreach($currency_pairs as $currency_pair){
        $currency_pair = preg_split("|/|", $currency_pair);
        if(count($currency_pair) == 2){
            $base_currency    = $currency_pair[0];
            $counter_currency = $currency_pair[1];
            
            send_fx_alert($base_currency, $counter_currency, $project_root);
        }
    }   
}

function send_fx_alert($base_currency, $counter_currency, $project_root){
    $rates_js_tmpl = file_get_contents("$project_root/rates.js.tmpl");
    $rates = str_replace("BASE_CURRENCY", $base_currency, $rates_js_tmpl);
    $rates = str_replace("COUNTER_CURRENCY", $counter_currency, $rates);

    file_put_contents("$project_root/rates.js", $rates);
    
    exec("/usr/bin/phantomjs --ssl-protocol=any $project_root/rates.js", $result);
    $fx = implode($result);
    file_put_contents("$project_root/rates",$fx);

    $html = file_get_html("$project_root/rates");
    
    if($html){
        $rates = $html->find('div#rates_detail_desc', 0)->plaintext;
        echo "ijtabahussain@live.com $rates FROM:FX Alerts <alert@ijtaba.me.uk>\r\n";
        #mail("ijtabahussain@live.com", $rates, $rates, "FROM:FX Alerts <alert@ijtaba.me.uk>\r\n");
    } else {
        echo "\nSomething just went royally wrong, wait till next run";
    }
}
?>
