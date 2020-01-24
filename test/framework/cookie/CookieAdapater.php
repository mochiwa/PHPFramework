<?php
namespace Test\Framework\Cookie;

use Framework\Cookie\ICookieStore;
/**
 * Description of CookieAdapater
 *
 * @author mochiwa
 */
class CookieAdapater implements ICookieStore{
    private $store;
    
    public function __construct() {
        $this->store=[];
    }
    
    public function getStore() : array {
       return $this->store;
    }

    public function setCookie(string $name, string $value = '', int $expire = 1, string $path = '', string $domain = '', string $secure = '', bool $httponly = false) {
        $this->store[$name]=['value'=>$value,'expire'=>$expire,'path'=>$path,'domain'=>$domain,'secure'=>$secure,'httponly'=>$httponly];
    }

    public function erase(string $name): void {
        unset($this->store[$name]);
    }

}
