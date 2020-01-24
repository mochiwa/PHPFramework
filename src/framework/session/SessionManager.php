<?php
namespace Framework\Session;
/**
 * This class is responsible to manage session
 *
 * @author mochiwa
 */
class SessionManager {
    const FLASH_KEY='flash';
    const CURRENT_USER_KEY='user';
    private $session;
    
    function __construct(ISession $session)
    {
        $this->session=$session;
    }
    
    /**
     * Return true if the session is active
     * @return bool
     */
    public function isActive():bool
    {
        return $this->session->isStarted();
    }
    
    /**
     * if session is active then append the key-value to the session
     * else open session and process again
     * @param string $key
     * @param type $value
     */
    public function set(string $key,$value=""){
        if(!$this->isActive()){
            $this->session->start();    
        }
        $this->session->add($key,$value);
    }
    
    /**
     * if session is active then return the value from the session
     * else open session and process again.
     * If $key not found return null
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        if(!$this->isActive()){
            $this->session->start();    
        }
        return $this->session->get($key);
    }
    
    /**
     * if session is active then remove the key-value to the session
     * else open session and process again.
     * @param string $key
     */
    public function delete(string $key)
    {
        if(!$this->isActive()){
            $this->session->start();    
        }
        $this->session->remove($key);
    }
    /**
     * Get the value from the session then delete it and remove
     * By default a flash keyword is used
     * @param string $key
     * @return type
     */
    public function flash(string $key= self::FLASH_KEY) : FlashMessage
    {
        $data=$this->get($key);        
        $this->delete($key);
        return $data ?? FlashMessage::null();
    }
    
    /**
     * Set flash message to the default flash key
     * @param string $message
     */
    public function setFlash(FlashMessage $message)
    {
        $this->set(self::FLASH_KEY, $message);
    }
    
    /**
     * Return true if something exist at key in session
     * @see SessionManager::get
     * @param string $key
     * @return bool
     */
    public function has(string $key):bool{
        return $this->get($key) != null;
    }
    
}
