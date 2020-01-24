<?php
namespace Test\Framework\FileManager;

use Exception;
use Framework\FileManager\FileException;
use Framework\FileManager\FileUploader;
use Framework\FileManager\LocalUploader;
use PHPUnit\Framework\TestCase;


class FileUploaderTest extends TestCase{
    private $DIRECTORY;
    private $uploader;
    private $fileUploader;
    
    public function setUp() {
       $this->uploader=new LocalUploader();
       $this->fileUploader=new FileUploader($this->uploader);
       touch('file.txt');
       $this->DIRECTORY=getcwd().DIRECTORY_SEPARATOR.'testDirectory';
    }
    
    public function tearDown() {
       if(file_exists( $this->DIRECTORY)){
           if(file_exists($this->DIRECTORY.'/file.txt'))
               unlink ($this->DIRECTORY.'/file.txt');
           if(file_exists($this->DIRECTORY.'/myFile'))
                unlink ($this->DIRECTORY.'/myFile');
           
           rmdir ( $this->DIRECTORY);
       }
       unlink('file.txt');
    }
    
    function test_upload_shouldCreateTheDirectory_whenTheDirectoryNoExist()
    {
        $this->assertDirectoryNotExists( $this->DIRECTORY);
        $this->fileUploader->upload('file.txt','file.txt', $this->DIRECTORY);
        $this->assertDirectoryExists( $this->DIRECTORY);
    }
    
    function test_upload_shouldNotCreateTheDirectory_whenTheDirectoryAldreadyExist()
    {
        $this->assertDirectoryNotExists( $this->DIRECTORY);
        try{
            $this->fileUploader->upload('file.txt','file.txt', $this->DIRECTORY);
            $this->fileUploader->upload('file.txt', 'file.txt', $this->DIRECTORY);
        } catch (Exception $ex) {
            $this->assertNull($ex);
        }
        $this->assertDirectoryExists( $this->DIRECTORY);
    }
    
    function test_upload_shouldThrowFileNotFoundException_whenSourceFileNotFound()
    {
        $this->expectException(FileException::class);
        $this->fileUploader->upload('NotExistingFile','NotExistingFile', $this->DIRECTORY);
    }
    
    
    function test_upload_shouldMoveSourceFileIntoTheDirectory()
    {
        $this->fileUploader->upload('file.txt','file.txt', $this->DIRECTORY);
        $this->assertFileExists( $this->DIRECTORY.DIRECTORY_SEPARATOR.'file.txt');
    }
    
    function test_upload_shouldChangeNameOfTheFile_whenItSpecified()
    {
        $this->fileUploader->upload('file.txt','myFile', $this->DIRECTORY);
        $this->assertFileExists( $this->DIRECTORY.DIRECTORY_SEPARATOR.'myFile');
    }
    
    function test_uploadToDefault_shouldUploadFileIntoDefaultFolder(){
        $this->fileUploader->uploadToDefault('file.txt','myFile');
        $this->assertFileExists( $this->fileUploader->defaultDirectory().DIRECTORY_SEPARATOR.'myFile');
    }
    
    
    function test_constructor_shouldInitializeDefaultDirectoryLike_upload_whenItIsNotSpecified()
    {
        $uploader=new FileUploader(new LocalUploader);
        $this->assertSame(getcwd().'/upload', $uploader->defaultDirectory());
    }
    function test_constructor_shouldThrowRUntimeException_whenDefaultDirectoryIsEmpty()
    {
        $this->expectException(\RuntimeException::class);
        new FileUploader(new LocalUploader,'');
    }
    

    
   
    
}
