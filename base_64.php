<?php
$key="KW_key_1234567890";
function encryption($value, $type=0){
    global $key;
    $key_str = md5($key);
    return str_replace('=','',base64_encode($value ^ $key_str));
}
function decryption($value, $type=0){
    //解密
    global $key;
    $key_str = md5($key);
    $value = base64_decode($value."=");
    return $value ^ $key_str;
}
echo encryption("mouse001");
echo "<BR>";
echo decryption("Xg1GRgQABFI");
?>