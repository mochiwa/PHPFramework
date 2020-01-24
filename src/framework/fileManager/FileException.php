<?php

namespace Framework\FileManager;

/**
 * Description of FileException
 *
 * @author mochiwa
 */
class FileException extends \Exception {
    
    public function __construct(string $message) {
        parent::__construct($message);
    }

}
