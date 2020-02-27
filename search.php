<?php
$MadelineProto->loop(function()use($from,$file,$get,$body,$MadelineProto){
//                include "Passworded_fileup.php";
    if(isset($get["search"])){
        $messages_Messages = yield $MadelineProto->messages->search(["add_offset"=>0,"limit"=>87,"max_id"=>0,"max_date"=>0,"min_date"=> -2147483648,'offset_id'=>-2147483648,"min_id"=>-2147483648,'peer' => "@shidown", 'q' => $get["search"]]);
        try{
            
           if(isset($messages_Messages["messages"])&&isset($messages_Messages["messages"][0])&&isset($messages_Messages["messages"][0]["media"])) {
             $e = json_decode($messages_Messages["messages"][0]["message"],true);
                if($e["cfname"] == $get["search"]) {
                    //Is the file passworded?
                    if(($e["password"]??"") == "") {

                        $info = yield $MadelineProto->get_download_info($messages_Messages["messages"][0]["media"]);
                        
                        $from->send(json_encode(["opt"=>1,"header"=>['Content-Length',$info['size']]]));
                        $from->send(json_encode(["opt"=>1,"header"=>['Content-Type',$info['mime']]]));;
                        $output_file_name = yield $MadelineProto->downloadToDir($messages_Messages["messages"][0]["media"], '/tmp/uploads/');
                        $from->send(json_encode(["opt"=>3,"where"=>$output_file_name]));
                    } elseif(password_verify($body["password"]??"_",$e["password"])) {
                        include "Password.php";
                        $output_file_name = yield $MadelineProto->downloadToDir($messages_Messages["messages"][0]["media"], '/tmp/uploads/');
                        $decrypted = SaferCrypto::decryptFile($output_file_name, $body["password"],$output_file_name.".dec");
                        @unlink($output_file_name);
                        $from->send(json_encode(["opt"=>1,"header"=>['Content-Type',$e['mine']??"text/plain"]]));
                        $from->send(json_encode(["opt"=>1,"header"=>['Content-Length',filesize($output_file_name.".dec")]]));

                        $from->send(json_encode(["opt"=>3,"where"=>$output_file_name.".dec"]));
                    } elseif($body["password"]??"" === "") {
                        $from->send(json_encode(["opt"=>1,"header"=>["Content-Type","application/json"]]));
                        $from->send(json_encode(["opt"=>2,"message"=>json_encode(["ok"=>false,"error"=>"Password needed(POST)","ecode"=>403,"t"=>2])]));

                    } else {
                        $from->send(json_encode(["opt"=>1,"header"=>["Content-Type","application/json"]]));
                        $from->send(json_encode(["opt"=>2,"message"=>json_encode(["ok"=>false,"error"=>"Password needed(POST)","ecode"=>403,"t"=>1])]));
                    }
                } else {
                    $from->send(json_encode(["opt"=>1,"header"=>["Content-Type","application/json"]]));
                    $from->send(json_encode(["opt"=>2,"message"=>json_encode(["ok"=>false,"error"=>404])]));
                }
                
           } else {
            $from->send(json_encode(["opt"=>1,"header"=>["Content-Type","application/json"]]));
            $from->send(json_encode(["opt"=>2,"message"=>json_encode(["ok"=>false,"error"=>404])]));
           }
    
} catch (\Throwable $e) {
    $from->send(json_encode(["opt"=>1,"header"=>["Content-Type","application/json"]]));
    $from->send(json_encode(["opt"=>2,"message"=>json_encode(["ok"=>false,"error"=>404])]));
}
} else {
    $from->send(json_encode(["opt"=>1,"header"=>["Content-Type","application/json"]]));
    $from->send(json_encode(["opt"=>2,"message"=>json_encode(["ok"=>false,"error"=>404])]));
}
$from->send(json_encode(["command"=>"close"]));
});
