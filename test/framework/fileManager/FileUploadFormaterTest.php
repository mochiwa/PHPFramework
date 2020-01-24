<?php

namespace Test\Framework\FileManager;

use Framework\FileManager\FileUploadFormater;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;

/**
 * Description of FileUploadFormaterTest
 *
 * @author mochiwa
 */
class FileUploadFormaterTest extends TestCase{
    function setUp() {
        
       
    }
    
    function test_pathOf_shouldReturnEmptyString_whenRequestHasNotThePicture(){
        $uploadFormater=new FileUploadFormater([]);
        $this->assertEquals('File not found', $uploadFormater->pathOf('picture'));
    }
    
    function test_pathOf_shouldReturnPathOfTheFile_whenRequestHasNotThePicture(){
        $streamInterface=$this->createMock(StreamInterface::class);
        $streamInterface->expects($this->once())->method('getMetaData')->with('uri')->willReturn('/my/path');
        
        $uploadedFile=$this->createMock(UploadedFileInterface::class);
        $uploadedFile->expects($this->once())->method('getStream')->willReturn($streamInterface);
        
        $uploadFormater=new FileUploadFormater(['picture'=>$uploadedFile]);
        $this->assertEquals('/my/path', $uploadFormater->pathOf('picture'));
    }
    
    
   
}
