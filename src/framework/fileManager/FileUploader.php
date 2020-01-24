<?php
namespace Framework\FileManager;
/**
 * The class is responsive to manage upload
 * file from client.
 *
 * @author mochiwa
 */
class FileUploader {
    /**
     * Contain the php or other logic to upload file on the server
     * @var IUploader 
     */
    private $uploader;
    
    /**
     * The default directory where put file if not dir are specified
     * @var string
     */
    private $defaultDirectory;
    
    /**
     * 
     * @param \Framework\FileManager\IUploader $uploader
     * @param string $defaultDirectory must be the local path from $_SERVER['DOCUMENT_ROOT']
     * @throws \RuntimeException
     */
    public function __construct(IUploader $uploader,string $defaultDirectory='upload')
    {
        if(empty($defaultDirectory)){
            throw new \RuntimeException('Default directory cannot be empty');
        }
        $this->uploader=$uploader;
        $this->defaultDirectory=$defaultDirectory;
    }
   
    /**
     * Upload a file from a src to a destination folder
     * If the directory not exist then it will be created
     * 
     * @param string $sourceFile the source file with its path
     * @param string $filename the name of the file when uploaded
     * @param string $directory the directory with its path
     * @throws FileException
     */
    public function upload(string $sourceFile,string $filename,string $directory)
    {
        $directory=$_SERVER['DOCUMENT_ROOT'].$directory;
        if(!file_exists($directory) ){
            mkdir($directory,0777,true);
        }
        
        
        if(!file_exists($sourceFile)){
            throw new FileException("File not found !");
        }
        
        if(!is_dir($directory) || !is_writable($directory)){
            throw new FileException('The destination : '.$directory.' is not a directory or is not writable');
        }
        $this->uploader->upload($sourceFile, $directory.DIRECTORY_SEPARATOR.$filename);
    }
    
    /**
     * Upload a file directly into the default directory specified in constructor ('upload' by default)
     * @param string $sourceFile the source file with its path
     * @param string $filename the name of the file when uploaded
     */
    public function uploadToDefault(string $sourceFile,string $filename)
    {
        $this->upload($sourceFile, $filename, $this->defaultDirectory);
    }
    
    /**
     * Return the full path of the default directory
     * @return string
     */
    public function defaultDirectory():string{
        return realpath($this->defaultDirectory);
    }
    
    public function defaultDirectoryLocalPath():string{
        return $this->defaultDirectory;
    }
    
    /**
     * Return true when the default directory contains the file
     * @param string $filename
     * @return bool
     */
    public function isDefaultDirectoryContains(string $filename):bool{
        return file_exists($this->defaultDirectory().'/'.$filename);
    }
}


