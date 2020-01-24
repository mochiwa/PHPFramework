<?php

namespace Framework\FileManager;

/**
 * Description of IUploader
 *
 * @author mochiwa
 */
interface IUploader {
    function upload(string $src,string $dest);
}
