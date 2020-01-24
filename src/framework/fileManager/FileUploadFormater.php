<?php

namespace Framework\FileManager;

use Exception;

/**
 * This class is responsible to convert an object that implement
 * the FileUploadedInterface to common language
 *
 * @author mochiwa
 */
class FileUploadFormater{ 
    /**
     * Contain file uploaded , each file must implement
     * FileUploadedInterface from PSR
     * @var  array
     */
    private $files;
    
    public function __construct(array $filesUploaded) {
        $this->files = $filesUploaded;
    }
    
    public static function of(array $filesUploaded):self{
        return new self($filesUploaded);
    }
    
    /**
     * Return the path of the uploaded file if exist,else empty string
     * @param string $file
     * @return string
     */
    public function pathOf(string $file):string  {
        try{
            return $this->files[$file]->getStream()->getMetaData('uri');
        } catch (Exception $ex) {
            return 'File not found';
        }
    }

    public function picturePath(string $file){
        try{
            $image= $this->files[$file];
            if(strpos($image->getClientMediaType(),'image')!==false){
                   return $this->pathOf($file);
            }
             return "";
        } catch (Exception $ex) {
            return 'File not found';
        }
    }
    
}
