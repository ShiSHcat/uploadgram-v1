<?php
$MadelineProto->loop(function()use($from,$file,$get,$body,$params,$MadelineProto){
//                include "Passworded_fileup.php";
    if(isset($params["deletes"])){
        $messages_Messages = yield $MadelineProto->messages->search(["add_offset"=>0,"limit"=>87,"max_id"=>0,"max_date"=>0,"min_date"=> -2147483648,'offset_id'=>-2147483648,"min_id"=>-2147483648,'peer' => "@shidown", 'q' => $params["deletes"]]);
        try{
            
           if(isset($messages_Messages["messages"])&&isset($messages_Messages["messages"][0])&&isset($messages_Messages["messages"][0]["media"])) {
             $e = json_decode($messages_Messages["messages"][0]["message"],true);
                if($e["deleteID"]??"" == $params["deletes"]) {
                    echo $messages_Messages["messages"][0]["id"];
                    $MadelineProto->messages->deleteMessages(['revoke' => true, "id"=>[$messages_Messages["messages"][0]["id"]] ]);
                    $from->send(json_encode(["opt"=>1,"header"=>["Content-Type","application/json"]]));
                    $from->send(json_encode(["opt"=>2,"message"=>json_encode(["ok"=>true,"message"=>"Message deleted"])]));
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
