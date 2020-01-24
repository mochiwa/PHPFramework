<?php
namespace Framework\Renderer;

use InvalidArgumentException;

/**
 * class responsible to return the view
 * @author mochiwa
 */
class ViewBuilder implements IViewBuilder {
    
    /**
     * @var array list of directory path
     */
    private $directoryPaths=[];
    /**
     * @var mixed list of global variable to share with view 
     */
    private $globals=[];
    
    /**
     * @var string  the base layout path
     */
    private $layout=null;
    
    
    /**
     * Build a viewBuilder with a default layout
     * @param string $namepsace
     * @param string $directoryPath
     * @param string $viewName
     * @return \Framework\Renderer\ViewBuilder
     */
    public static function buildWithLayout(string $namepsace, string $directoryPath,string $viewName)
    {
        $viewBuilder=new ViewBuilder();
        $viewBuilder->addPath($namepsace, $directoryPath);
        $viewBuilder->setDefaultLayout('@'.$namepsace.'/'.$viewName);
        return $viewBuilder;
    }
   
    
    /**
     * Link a directory to a namespace.
     * 
     * @param string $namepsace the namespace to find the directory
     * @param string $directoryPath the directory path
     * @return IViewBuilder
     * 
     * @throws InvalidArgumentException when the namespace is empty
     * @throws InvalidArgumentException when the path is empty
     */
    public function addPath(string $namepsace , string $directoryPath): IViewBuilder
    {
        if(empty($namepsace))
        {
            throw new InvalidArgumentException ("The namespace cannot be empty");
        }
        elseif(empty($directoryPath))
        {
            throw new InvalidArgumentException ("The path cannot be empty");
        }
        
        $this->directoryPaths[$namepsace]=$directoryPath;
        return $this;
    }
    
    /**
     * Try load load a view and return it.
     * IF the view builder has a default layout, then
     * The content is loaded into the variable 'content' from the layout,
     * else just return the content.
     * 
     * 
     * @param string $view file name with the <b>namespace</b> like @mynamespace/post
     * @param array $parameters a list of parameters
     * @return string 
     * 
     * @throws InvalidArgumentException when the view doesn't contain @
     * @throws InvalidArgumentException when the view end by /
     * @throws InvalidArgumentException when the namespace not found
     * @throws InvalidArgumentException when the file not found
     */
    public function build(string $view,array $parameters=[]):string
    {
        $content=$this->query($view,$parameters);
        if($this->layout)
        {
            ob_start();
            $viewBuilder=$this;
            
            require $this->findViewPath($this->layout);
            
            return ob_get_clean();
        }
        return $content;
    }
    
    /**
     * Assert that view contain a namespace
     * and the view file doesn't ending by /
     * 
     * @param string $view
     * @throws InvalidArgumentException when the view doesn't contain @
     * @throws InvalidArgumentException when the view end by /
     */
    private function assertViewFormat(string $view)
    {
        if($view[0]!=='@')
        {
            throw new InvalidArgumentException ("The view must contain a namespace @");
        }
        elseif($view[-1]==='/')
        {
            throw new InvalidArgumentException ("The view must be a file, it cannot ending with /");
        }
    }
    
    
    /**
     * Return the path of the view
     * @param string $view
     * @return string the file path
     * 
     * @throws InvalidArgumentException when the namespace not found
     * @throws InvalidArgumentException when the file not found
     */
    private function findViewPath(string $view) : string
    {
        $namespace=$this->getNamespace($view);
        if(!array_key_exists($namespace, $this->directoryPaths))
        {
            throw new InvalidArgumentException ("The namespace : ".$namespace." has not been found"); 
        }
        
        $file=$this->directoryPaths[$namespace].DIRECTORY_SEPARATOR.$this->getView($view).'.php';
        if(!file_exists($file))
        {
            throw new InvalidArgumentException ("The view file : ".$file." has not been found");
        }
        return $file;
    }
    
    /**
     * Try load load a view and return it.
     * @param string $view the module
     * @param array $parameters
     * @return string
     * 
     * @throws InvalidArgumentException when the view doesn't contain @
     * @throws InvalidArgumentException when the view end by /
     * @throws InvalidArgumentException when the namespace not found
     * @throws InvalidArgumentException when the file not found
     */
    public function query(string $view,array $parameters=[]): string
    {
        $this->assertViewFormat($view);
        $file=$this->findViewPath($view);
        
        ob_start();
        $viewBuilder=$this;
        extract($parameters);
        extract($this->globals);
        
        require ($file);
        return ob_get_clean();
    }
    
    /**
     * Append a variable that can be used from any view generated by this
     * @param string $key
     * @param type $data
     */
    public function addGlobal(string $key,$data): IViewBuilder
    {
        $this->globals[$key]=$data;
        return $this;
    }
    
    /**
     * When a layout is setted then you must
     * append a variable name 'content' in your layout
     * then every content inserted by build will be inserted
     * into the variable 'content'
     * @param string $view the view with the namespace like @template/myLayout
     */
    public function setDefaultLayout(string $view):self
    {
        $this->layout=$view;
        return $this;
    }
    private function getNamespace(string $view)
    {
        return substr($view, 1, strrpos($view, '/')-1);
    }
    private function getView(string $view)
    {
        return substr(strrchr($view, "/"), 1);
    }
   
    
}
