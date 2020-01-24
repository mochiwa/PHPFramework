<?php

namespace Framework\Session;

/**
 * Description of ISession
 *
 * @author mochiwa
 */
interface ISession {
    /**
     * Start the session
     */
    function start();
    /**
     * Stop the session
     */
    function stop();
    /**
     * Append a parameters to the session
     * @param string $key
     * @param type $value
     * @return void
     */
    function add(string $key,$value) : void;
    /**
     * Remove a parameters from the session
     * @param string $key
     * @return void
     */
    function remove(string $key) : void;
    /**
     * Return a parameter from the sesion
     * @param string $key
     */
    function get(string $key);
    /**
     * Return true when session is started
     * @return bool 
     */
    function isStarted() :bool;
}
