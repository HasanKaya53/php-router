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
    private static $fileName = "";


    static private function find_slug($request){
        if(strstr($request,"{")){
            return "true";
        }
        return "false";
    }


    static private function fileType($callback){
        $fileType = explode(".",$callback);
        $fileType = end($fileType);
        self::$includeFileType = ".".$fileType;
        self::$fileName = str_replace(self::$includeFileType,"",$callback);
    }


    static private function SetIndex(){

        if(self::$requestUrl == "/") self::$requestUrl = "/index";
        if(self::$currentUrl == "/") self::$currentUrl = "/index";
        if(self::$method == "FINAL") self::$requestUrl = self::$currentUrl;


    }

    // find request url
    static  public function findRequestUrl(){

        $fullPath = $_SERVER['DOCUMENT_ROOT'];
        $realPath = realpath('.');
        $urlPath = $_SERVER['REQUEST_URI'];

        $folderPath = str_replace($fullPath,"",$realPath);
        self::$requestUrl = substr($urlPath, strlen($folderPath));

        self::SetIndex();




    }

    static private function explodeRequest($request){

        //$params = str_replace("/","",$request);
        $params = explode("/",$request);

        $paramsStatus = [];
        foreach ($params as $key => $value) {
          if(!empty($value)){
                $paramsStatus[$value] =
                    [
                        "Status" => self::find_slug($value),

                    ];


            }
        }

        return $paramsStatus;
    }

    // route function
    static private function route($request, $callback,$args = [])
    {
        // echo self::$method." ";

        self::$currentUrl = $request;
        self::findRequestUrl();




        /* echo self::find_slug($request); */




        $paramsChecker = self::explodeRequest($request);


        

        if((self::$currentUrl != self::$requestUrl) && self::$method != "FINAL" ){
            if(max($paramsChecker)  === false)  return false; //parametre yoktur..

            //parametre vardır.
            $newReqUrl = "/";
            $getArray = [];

            foreach ($paramsChecker as $key => $value) {
                if($value["Status"] === "false") $newReqUrl .= $key."/";
                else{


                    $newKey = str_replace("{","",$key);
                    $newKey = str_replace("}","",$newKey);



                    $getArray[$newKey] = "";
                }
            }


            if($newReqUrl[strlen($newReqUrl)-1] == "/")
                $newReqUrl = substr($newReqUrl,0,-1);





            if(strstr(self::$requestUrl,$newReqUrl) === false) return false;
            else{

                $newReqUrl = str_replace($newReqUrl,"",self::$requestUrl);

                $newReqUrl = explode("/",$newReqUrl);

                $newReqUrl = array_filter($newReqUrl);





                $counter = 0;
                foreach ($getArray as $key => $value) {

                    if(empty($newReqUrl[$counter+1])) return false;
                    $getArray[$key] = $newReqUrl[$counter+1];

                    if(strstr(self::$currentUrl,$key)){


                        self::$currentUrl = str_replace("{".$key."}",$getArray[$key],self::$currentUrl);


                    }


                    $counter++;
                }

                $_GET = array_merge($_GET,$getArray);




            }
        }



        if(self::$requestUrl[0] == "/") self::$requestUrl = substr(self::$requestUrl, 1);
        if(self::$currentUrl[0] == "/") self::$currentUrl = substr(self::$currentUrl, 1);



        if(!is_array($args)) $args = [];




        if(is_callable($callback)){


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
            $newUrl = self::$themeFolder.self::$fileName.self::$includeFileType;
            if(file_exists($newUrl)){
                require_once($newUrl);
                self::$pageStatus = 200;
            }
            else {
                self::$pageStatus = 404;
                echo "File Not Found ".self::$fileName.self::$includeFileType;
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
