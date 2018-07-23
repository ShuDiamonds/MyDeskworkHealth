<?php


//header('Content-Length: ' . 1000);
ini_set("display_errors",1);


$users[] = array('name'=>'tamura', 'age'=>'25');
$users[] = array('name'=>'suzuki', 'age'=>'23');
$users[] = array('name'=>'maki', 'age'=>'29');

//print_r($users);

$users[0]['sex'] = 'male';
$users[1]['sex'] = 'female';
$users[2]['sex'] = 'male';

//print_r($users);

foreach($users as $user){
    //echo $user['name'] . 'さんは' . $user['age'] . "歳です。\n";
}
  

//echo "users json data: ".json_encode($users);

//echo PHP_EOL."###############################".PHP_EOL;

if(!isset($_GET["filename"])){
    echo "filenameクエリが設定されていません";
    http_response_code(400);
    die();
}
$filename=$_GET["filename"];

//###################POSTの処理
if($_SERVER['REQUEST_METHOD']==="POST"){
    echo "This is POST method\n".PHP_EOL;
  
    $rawdata = file_get_contents("php://input");
    $new_jsondata=json_decode($rawdata,true);   //trueを入れないと配列に治らない
    $handle = fopen($filename, "r");
    $contents = fread($handle, filesize($filename));
    fclose($handle);
    //echo "json file is:".$contents;
    $old_jsondata=json_decode($contents,true);
    //debug
    //echo "old json data is ";
    //print_r($old_jsondata).PHP_EOL;
	
    //echo "new json data is ";
    //print_r($new_jsondata).PHP_EOL;

    //jsonの追加処理
    //参考URL:http://qiita.com/shuntaro_tamura/items/784cfd61f355516dfff0
    $fp = fopen($filename, "w"); 
    //$Save_json_data=array_merge($old_jsondata,$new_jsondata);
    $Save_json_data=$old_jsondata;    //データの読み込み
    $Save_json_data[]=$new_jsondata;    //データの読み込み
    echo "save json is ".json_encode($Save_json_data).PHP_EOL;
    fwrite($fp, json_encode($Save_json_data));
    fclose($fp);

//##################GETの処理
} else if($_SERVER['REQUEST_METHOD']==="GET"){
    echo "This is GET method".PHP_EOL;
    $handle = fopen($filename, "r");
    $contents = fread($handle, filesize($filename));
    fclose($handle);
    $old_jsondata=json_decode($contents,true);
    echo "json file is:".$contents.PHP_EOL;
    print_r($old_jsondata);
    foreach($old_jsondata as $user){
        echo "IDが".$user['id']."の". $user['name'] . 'さんは' . $user['age'] . "歳です。\n";
    }


}else{
    echo $_SERVER['REQUEST_METHOD'];
}



?>

