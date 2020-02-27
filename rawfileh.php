<?php
$MadelineProto->loop(function()use($from,$file,$get,$params,$body,$MadelineProto){
    var_dump($params);
    if(isset($params["file"])){
        try{
            $messages_Messages = yield $MadelineProto->messages->search(["add_offset"=>0,"limit"=>87,"max_id"=>0,"max_date"=>0,"min_date"=> -2147483648,'offset_id'=>-2147483648,"min_id"=>-2147483648,'peer' => "@shidown", 'q' => $params["file"]]);
        
            
            if(isset($messages_Messages["messages"])&&isset($messages_Messages["messages"][0])&&isset($messages_Messages["messages"][0]["media"])) {
             $e = json_decode($messages_Messages["messages"][0]["message"],true);
                if($e["cfname"] ==$params["file"]) {
                    //Is the file passworded?
                    if(($e["password"]??"") == "") {

                        $info = yield $MadelineProto->get_download_info($messages_Messages["messages"][0]["media"]);
                        
                        $from->send(json_encode(["opt"=>1,"header"=>['Content-Length',$info['size']]]));
                        $from->send(json_encode(["opt"=>1,"header"=>['Content-Type',$info['mime']]]));;
                        $output_file_name = yield $MadelineProto->downloadToDir($messages_Messages["messages"][0]["media"], '/tmp/uploads/');
                        $from->send(json_encode(["opt"=>3,"where"=>$output_file_name]));

                    } elseif(password_verify($body["password"]??($get["password"]??"_"),$e["password"])) {
                        include "Password.php";
                        $output_file_name = yield $MadelineProto->downloadToDir($messages_Messages["messages"][0]["media"], '/tmp/uploads/');
                        $decrypted = SaferCrypto::decryptFile($output_file_name, $body["password"]??($get["password"]??"_"),$output_file_name.".dec");
                        @unlink($output_file_name);
                        $from->send(json_encode(["opt"=>1,"header"=>['Content-Type',$e['mine']??"text/plain"]]));
                        $from->send(json_encode(["opt"=>1,"header"=>['Content-Length',filesize($output_file_name.".dec")]]));

                        $from->send(json_encode(["opt"=>3,"where"=>$output_file_name.".dec"]));
                    } elseif($body["password"]??"" === "") {
                        $from->send(json_encode(["opt"=>2,"message"=>str_replace("%bssb%",$get["search"],file_get_contents("../f/password_reag.html"))]));

                    } else {
                        $from->send(json_encode(["opt"=>2,"message"=>file_get_contents("../f/failed.html")]));
                    }
                } else {
                    $from->send(json_encode(["opt"=>2,"message"=>file_get_contents("../f/failed.html")]));
                }
                
           } else {
            $from->send(json_encode(["opt"=>2,"message"=>file_get_contents("../f/failed.html")]));
           }
    
} catch (\Throwable $e) {
    $from->send(json_encode(["opt"=>2,"message"=>file_get_contents("../f/failed.html")]));
}
} else {
    $from->send(json_encode(["opt"=>2,"message"=>file_get_contents("../f/failed.html")]));
}
$from->send(json_encode(["command"=>"close"]));
});
