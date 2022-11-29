<?php

namespace Routes;

class Helpers {
    
    public static function messages(array $params){
        $values = json_encode($params);
        $message = "<div class='alertinfo' data-alert='$values'></div>";
        return $message;
    }

    public static function callMethod(string $method, string $type = 'POST', array $params = [], array $headers = []){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $type);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($curl, CURLOPT_URL, "". $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        return new Response($httpcode,$result);
    }

    public static function js($script){
        $js = "<script src=\"Public/js/$script\"></script>";
        return $js;
    }

    public static function dateConvert(string $date){
        $parts = [];
        if(strpos('T', $date)){
            $parts = explode('T', $date);
        }else{
            $parts = explode(' ', $date);
        }
        $day = explode('-', $parts[0]);
        $hours = explode(':', $parts[1]);
        $time = mktime($hours[0],$hours[1],$hours[2], $day[1], $day[2], $day[0]);
        return date('d/m/Y H:i:s', $time);
    }

    public static function getPath(){
        return str_replace('index.php', '', $_SERVER['PHP_SELF']);
    }

    public static function debug($params){
        echo "<pre>";
        print_r($params);
        echo "</pre>";
    }

}

?>