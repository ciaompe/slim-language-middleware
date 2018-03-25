<?php

namespace App\Middlewares\Language;


class Language{

    private $lang,
            $folder,
            $ext = ".php";

    public function __construct($lang, $folder){

        $this->folder = $folder;
        $this->lang = $lang;
        
    }

    public function getFileAsArray(){

        $file = $this->folder . $this->lang . $this->ext;

        if (file_exists($file)) {
            $words = include $file;
            return $words;
        }else{
            throw new Exception("The Language file does not exists", 1);
        }    
    }

    public function setLang($lang){
        $this->lang = $lang;
    }

}
