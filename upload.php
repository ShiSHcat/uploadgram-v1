<?php
$MadelineProto->loop(function()use($from,$file,$get,$body,$MadelineProto){
include "tokenH.php";
    $rsss = true;
    try{
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 3; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $DeleteID1 = bin2hex(random_bytes(12));
            $xee = $body["password"]??($get["password"]??"");
            $ssd = $body["eu"]??($get["eu"]??"");
            $qre = "";
            $ee23 = explode(".",$file["originalname"]);
            $rr23 = $ee23[count($ee23)-1];
            $randomString.=".".$rr23;
            $t1 = $body["token"]??($get["token"]??"");
            $pref = $body["prefix"]??($get["prefix"]??"");
            $x = "";
            if($pref !== ""&& (($t1??"") !== "")) {
                $messages_Messages = yield $MadelineProto->messages->search(["add_offset"=>0,"limit"=>87,"max_id"=>0,"max_date"=>0,"min_date"=> -2147483648,'offset_id'=>-2147483648,"min_id"=>-2147483648,'peer' => "@shishcat8214", 'q' => $t1]);
                $xe = getpzUser($MadelineProto,$t1,$messages_Messages);
                if($xe>0){
                    $randomString = preg_replace('/[^a-z0-9_]/', "",$pref).$randomString;
                }
            }
            if($xee !== ""||$ssd !== ""||$file["mimetype"]=="image/gif") {
                if($xee !== "") {
                    $x = password_hash($xee,PASSWORD_DEFAULT);
                } elseif($file["mimetype"]=="image/gif"){
                    $xee = "_";
                    $x = password_hash("_",PASSWORD_DEFAULT);
                } else {
                    $bytes = random_bytes(7);
                    $xee = bin2hex($bytes);
                    $x = password_hash($xee,PASSWORD_DEFAULT);
                    $qre="?password=".$xee;

                }
                

                echo"a";
                include __DIR__."/Password.php";
            
                $steee = SaferCrypto::encryptFile($file["path"],$xee,$file["path"]."enc");
                $sentMessage = yield $MadelineProto->messages->sendMedia([
                    'peer' => '@shidown',
                    'media' => [
                        '_' => 'inputMediaUploadedDocument',
                        'file' => $steee,
                        'attributes' => [
                            ['_' => 'documentAttributeImageSize'],
                            ['_' => 'documentAttributeFilename', 'file_name' => $file["originalname"].".enc"]
                        ]
                    ],
                    'message' => json_encode(["filename"=>$file["originalname"],"mine"=>$file["mimetype"],"encrypted"=>true,"cfname"=>$randomString,"password"=>$x,"deleteID"=>$DeleteID1])
                ]);
            } else {
            
                echo"bba";
            $sentMessage = yield $MadelineProto->messages->sendMedia([
                'peer' => '@shidown',
                'media' => [
                    '_' => 'inputMediaUploadedDocument',
                    'file' => $file["path"],
                    'attributes' => [
                        ['_' => 'documentAttributeImageSize'],
                        ['_' => 'documentAttributeFilename', 'file_name' => $file["originalname"]]
                    ]
                ],
                'message' => json_encode(["filename"=>$file["originalname"],"cfname"=>$randomString,"deleteID"=>$DeleteID1])
            ]);            }
    } catch (\Throwable $e){
        var_dump($e);
        $rsss = false;

    if($body["ht3"]??false) {
        $from->send(json_encode(["opt"=>2,"message"=>file_get_contents("../web/failed.html")]));
    } elseif ($get["dh3"]??false) {
        $from->send(json_encode(["opt"=>2,"message"=>"failed"]));

    } else {
        $from->send(json_encode(["opt"=>1,"header"=>["Content-Type","application/json"]]));
        $from->send(json_encode(["opt"=>2,"message"=>json_encode(["ok"=>false])]));
        var_dump($e);
    }


    }
    if($rsss) {
        if($body["ht3"]??false) {
            $from->send(json_encode(["opt"=>2,"message"=>str_replace("%%s2e%%",$DeleteID1,str_replace("%se%",$randomString.$qre,file_get_contents("../web/success.html")))]));
        } elseif ($body["dh3"]??false){
            $from->send(json_encode(["opt"=>2,"message"=>"https://uploadgram.me/f/".$randomString.$qre]));
        }else {
            $from->send(json_encode(["opt"=>1,"header"=>["Content-Type","application/json"]]));
                $from->send(json_encode(["opt"=>2,"message"=>json_encode(["ok"=>true,"link"=>"https://uploadgram.me/f/".$randomString.$qre,"deleteID"=>"https://uploadgram.me/api/delete/".$DeleteID1])]));
            }

            echo "close";
        }

    }
    $from->send(json_encode(["command"=>"close"]));

    echo "close";

});
