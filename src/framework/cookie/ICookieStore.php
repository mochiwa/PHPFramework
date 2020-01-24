<?php
namespace Framework\Cookie;

/**
 * Description of ICookie
 *
 * @author mochiwa
 */
interface ICookieStore {
    /**
     * should append a cookie to the cookie store
     * @param string $name
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param string $secure
     * @param bool $httponly
     */
    function setCookie(string $name,string $value='',int $expire=1,string $path='/',string $domain='',string $secure='',bool $httponly=false);
    /***
     * Should return the cookie store
     */
    function getStore() : array;

    /**
     * Should erase the cookie stored at name index
     * @param string $name
     * @return void
     */
    function erase(string $name) : void ;
}
