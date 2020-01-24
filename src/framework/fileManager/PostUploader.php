<?php

namespace Framework\FileManager;

/**
 * Allow to move file from a post request
 *
 * @author mochiwa
 */
class PostUploader implements IUploader{
    
    
    
    public function upload(string $src, string $dest) {
        move_uploaded_file($src,  $dest);
    }

}
