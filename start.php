<?php
if (!file_exists('madeline.php')) {
    copy('https://phar.madelineproto.xyz/madeline.php', __DIR__.'/madeline.php');
}
include __DIR__.'/madeline.php';
$settings['updates']['handle_updates'] = false;
$MadelineProto = new \danog\MadelineProto\API(__DIR__.'/session.madeline',$settings);
$MadelineProto->async(true); 
