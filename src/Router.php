<?php

namespace LuckyStar\PhpRouter;
class BHRouter
{
    public static $themeFolder = "";


    private static $requestUrl;
    private static $currentUrl;
    private static $method = "";

    private static $pageStatus = 404;
    private static $includeFileType = "";


    static function find_slug($request){
        if(strstr($request,"{")){
            return "true";
        }
        return "false";
    }


    static function fileType($callback){
        $fileType = explode(".",$callback);
        $fileType = end($fileType);
        self::$includeFileType = ".".$fileType;
    }



    static private function SetIndex(){

        if(self::$requestUrl == "/") self::$requestUrl = "/index";
        if(self::$currentUrl == "/") self::$currentUrl = "/index";
        if(self::$method == "FINAL") self::$requestUrl = self::$currentUrl;


    }

    // find request url
    static public function findRequestUrl(){

        $folderPath = dirname($_SERVER['SCRIPT_NAME']);
        $urlPath = $_SERVER['REQUEST_URI'];
        self::$requestUrl = substr($urlPath, strlen($folderPath));

        self::SetIndex();


    }

    // route function
    static private function route($request, $callback,$args = [])
    {
        // echo self::$method." ";

        self::$currentUrl = $request;


        self::findRequestUrl();






        if((self::$currentUrl != self::$requestUrl) && self::$method != "FINAL" ){
            // echo "girdi";
            return false;
        }





        if(self::$requestUrl[0] == "/") self::$requestUrl = substr(self::$requestUrl, 1);
        if(self::$currentUrl[0] == "/") self::$currentUrl = substr(self::$currentUrl, 1);


        if(!is_array($args)) $args = [];

        if(is_callable($callback)){

            // echo self::$currentUrl."-".self::$requestUrl;
            if(self::$currentUrl == self::$requestUrl) $callback();
            exit;
        }else if(function_exists($callback)){
            $callback();
            exit;
        }else{
            self::fileType($callback);

            self::render($args);
            exit;
        }



    }

    // get function
    static function get($request,$callback,$args = []){
        if($_SERVER['REQUEST_METHOD'] == "GET"){
            self::$method = "GET";
            self::Route($request,$callback,$args);
        }
    }

    // post function
    static function post($request,$callback,$args = []){
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            self::$method = "POST";
            self::Route($request,$callback,$args);
        }
    }

    // render function
    static private function render($args = []){

        if(is_dir(self::$themeFolder)){
            $newUrl = self::$themeFolder.self::$requestUrl.self::$includeFileType;
            if(file_exists($newUrl)){
                require_once($newUrl);
                self::$pageStatus = 200;
            }
            else {
                self::$pageStatus = 404;
                echo "File Not Found ".self::$themeFolder.self::$requestUrl;
            }
        }else{
            self::$pageStatus = 404;
            echo "Theme Folder Not Found ".self::$themeFolder;
        }
        $page = ob_get_clean();
        echo $page;
    }

    // no one function
    static function noOne($request,$callback,$args = []){

        if(self::$pageStatus == "404"){
            self::$method = "FINAL";
            self::Route($request,$callback,$args);
        }


    }

}


?>