<?php

namespace Framework\Session;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Description of SessionTwigExtension
 *
 * @author mochiwa
 */
class SessionTwigExtension extends AbstractExtension{
    private $session;
    
    public function __construct(SessionManager $session) {
        $this->session=$session;
    }
    


    public function getTests() {
        return [];
    }
    
    public function getFunctions() {
        return [new TwigFunction('sessionHasFlashMessage', [$this,'hasFlashMessage']),
            new TwigFunction('flashMessage', [$this,'flashMessage'])];
    }
    
    /**
     * Return the flash message contained in session
     * @return string
     */
    public function flashMessage() : FlashMessage{
        return $this->session->flash();
    }
    
    /**
     * Return true if the session contain flash message
     * @return string
     */
    public function hasFlashMessage() : bool{
        return $this->session->has(SessionManager::FLASH_KEY);
    }

}
