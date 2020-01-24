<?php
namespace Framework\Renderer;

/**
 * Description of IViewBuilder
 *
 * @author mochiwa
 */
interface IViewBuilder {
    /**
     * Add a path to a view with a name space
     * @param string $namepsace
     * @param string $path
     * @return void
     */
    function addPath(string $namepsace , string $path):self;
    /**
     * Return the view 
     * @param string $view
     * @param array $parameters
     * @return string
     */
    function build(string $view,array $parameters=[]):string;
    
    /**
     * Query a module into a view
     * @param string $view
     * @param array $parameters
     * @return string
     */
    function query(string $view,array $parameters=[]): string;
    /**
     * Add variable accessible anywhere 
     * @param string name to use to find variable
     * @param type $data
     * @return self
     */
    function addGlobal(string $key,$data):self;
    
    /**
     * Set a path for the defaultLayout
     * @param string $filePath
     */
    function setDefaultLayout(string $view);
}
