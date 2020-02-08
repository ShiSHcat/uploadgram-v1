<?php
if(!function_exists ("getpzUser")){
function getpzUser($MadelineProto,string $user,$messages_Messages) {
    try {
    if( isset($messages_Messages["messages"]) && isset($messages_Messages["messages"][0]) ) {
        $e = json_decode($messages_Messages["messages"][0]["message"],true);
        if($user == $e["token"]) {
            return $e["p"];
        } else {
            return 0;
        }
    } else {
        return 0;
    }
} catch (\Throwable $e) {
    return 0;
}
}}
/**
 * 0 is free 
 * 1 is Patreon Plus
 * 2 is Patreon Plus2
 * 3 is Patreon Pro
 * 4 is BEST Supporter ever
 */
