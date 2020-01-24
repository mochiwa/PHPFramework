<?php

namespace Framework\Cookie;

/**
 * Description of PhpCookie
 *
 * @author mochiwa
 */
class PhpCookieStore implements ICookieStore{
    
    public function erase(string $name): void {
        $this->setCookie($name,'',1);
    }

    public function getStore(): array {
        return $_COOKIE;
    }

    
    public function setCookie(string $name, string $value = '', int $expire = 1, string $path = '/', string $domain = '', string $secure = '', bool $httponly = false) {
        setcookie($name,$value,$expire,$path,$domain,$secure,$httponly);
    }

}
