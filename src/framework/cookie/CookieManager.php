<?php
namespace Framework\Cookie;
/**
 * Description of CookieManager
 *
 * @author mochiwa
 */
class CookieManager {
    const TIME_ONE_DAY=86400;
    
    /**
     * The cookies store
     * @var ICookie
     */
    private $cookieStore;
    
    
    public function __construct(ICookieStore $cookieStore){
        $this->cookieStore=$cookieStore;
    }
    
    /**
     * Return true if the cookie store has the cookie
     * @param string $cookieName
     * @return bool
     */
    public function hasCookie(string $cookieName) : bool
    {
        return isset($this->cookieStore->getStore()[$cookieName]);
    }
    
  
    /**
     * Append a cookie the cookie store, if a cookie with 
     * the cookie name already exist then throw CookieStoreException
     * @param string $cookieName
     * @param string | array
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param string $secure
     * @param bool $httponly
     * @throws CookieStoreException
     */
    public function addCookie(string $cookieName, $value = '', int $expire = 1, string $path = '/', string $domain = '', string $secure = '', bool $httponly = false){
        if($this->hasCookie($cookieName)){
            throw new CookieStoreException("A cookie with this name already exist in cookie store");
        }
        $this->setCookie($cookieName, $value,$expire,$path,$domain,$secure,$httponly);
    }

    /**
     * Set a cookie to the cookieStore , if cookie already exist then <b>replace</b> it 
     * @param string $cookieName
     * @param string | array $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param string $secure
     * @param bool $httponly
     */
    public function setCookie(string $cookieName, $value='' , int $expire = 1, string $path = '/', string $domain = '', string $secure = '', bool $httponly = false){
        $this->cookieStore->setCookie($cookieName,
            is_array($value) ? json_encode($value) : $value ,
            $expire,
            $path,$domain,$secure,$httponly);
    }
    
    
    /**
     * erase the cookie from the cookie store,
     * if store hasn't the cookie throw Exception
     * @param string $cookieName
     * @throws CookieStoreException
     */
    public function eraseCookie(string $cookieName)
    {
        if(!$this->hasCookie($cookieName)){
            throw new CookieStoreException('The cookie store has not the cookie <'.$cookieName.'>');
        }
        $this->cookieStore->erase($cookieName);
    }
    
    
    public function getCookie(string $cookieName) : array
    {
        if(!$this->hasCookie($cookieName)){
            throw new CookieStoreException('The cookie store has not the cookie <'.$cookieName.'>');
        }
        return $this->cookieStore->getStore()[$cookieName];
    }
    
    /**
     * Return data contained into value from cookie , if values are encode with Json
     * then return values decoded into an array else return the value into an array
     * @param string $cookieName
     * @param bool $associative
     * @return array
     * @throws CookieStoreException
     */
    public function getDecodedValuesFromCookie(string $cookieName,bool $associative=false):array
    {
        if(!$this->hasCookie($cookieName)){
            throw new CookieStoreException('The cookie store has not the cookie <'.$cookieName.'>');
        }
        $cookie=$this->getCookie($cookieName);
        
        return json_decode($cookie['value'],$associative) ?? [$cookie['value']];
    }
}