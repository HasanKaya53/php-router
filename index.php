<?php


require_once('vendor/autoload.php');






$file = "test.php";
$file_2 = "test.php";

/*LuckyStar\PhpRouter\BHRouter::SetThemeGroup("/",
    [
        "ThemeFolder"=> "themes/",
        "ThemeFiles" => ["private/header.php","BHFOLDER","private/footer.php","BHFOLDER"],
        "ThemeFileMap"=>[$file,$file_2]
    ],
); */



LuckyStar\PhpRouter\BHRouter::$themeFolder = "themes/";


LuckyStar\PhpRouter\BHRouter::get('/bloglar/tes/{name}/{surname}', function($name,$surname){



});

LuckyStar\PhpRouter\BHRouter::get('/bloglar/tes/{name}/{surname}', 'bloglar.php');
LuckyStar\PhpRouter\BHRouter::get('/user', 'user.php');



