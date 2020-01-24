<?php

namespace Framework\FileManager;

/**
 * Description of FileUploaded
 *
 * @author mochiwa
 */
class FileUploaded {
    /**
     * The name of the file
     * @var string 
     */
    private $fileName;
    /**
     * The type of file like type/ext
     * @var string 
     */
    private $type;
    /**
     * the tmp name path
     * @var string 
     */
    private $tmpName;
    /**
     * the size of the file
     * @var Int 
     */
    private $size;
    
    public function __construct(string $fileName,string $type,string $tmpName='',int $size=0) {
        $this->fileName = $fileName;
        $this->type = $type;
        $this->tmpName = $tmpName;
        $this->size = $size;
    }
    
    
    public function getFileName() {
        return $this->fileName;
    }

    public function getType() {
        return $this->type;
    }

    public function getTmpName() {
        return $this->tmpName;
    }

    public function getSize(): Int {
        return $this->size;
    }



}
