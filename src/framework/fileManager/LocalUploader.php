<?php

namespace Framework\FileManager;

/**
 * LocalUploader is useful to move file inside the server
 *
 * @author mochiwa
 */
class LocalUploader implements IUploader {
    
    public function upload(string $src, string $dest) {
        copy($src, $dest);
    }

}
