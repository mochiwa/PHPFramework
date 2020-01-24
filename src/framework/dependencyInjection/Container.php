<?php
namespace Framework\DependencyInjection;

use ReflectionClass;
/**
 * Class Responsible to inject dependencies;
 * @author mochiwa
 */
class Container  implements IContainer{
    /**
     * @var array contain classes reference
     */
    private $container; 
    /**
     * @var array contains all class already instantiated by get
     */
    private $instances;
    
    public function __construct() {
        $this->container=[];
        $this->instances=[];
    }
    
    
    /**
     * Append a class reference in the container,
     * if the instances array contain an instance of the older value then unset it
     * @param string   $key   the key to found class ,(recommended to use ::class)
     * @param callable|string|array $content function or string  that call the class with your own parameter
     */
    public function set(string $key,  $content) {
        $this->container[$key] = $content;
        
        if(key_exists($key, $this->instances)){
            unset($this->instances[$key]);
        }
        
    }
    
    /**
     * Append an content to the container,
     * if the container hasn't the key then set it,
     * if the container has the key ant it's an array then append content to the array
     * else throw exception when container has key and it's not an array (use set instead)
     * @param string $key
     * @param array $content
     * @throws ContainerException
     */
    public function add(string $key, array $content){
        if(!$this->has($key)){
            $this->set($key, $content);
        }elseif(is_array($this->container[$key])){
            foreach ( $content as $item){
                array_push($this->container[$key],$item);
            } 
        }else{
           throw new ContainerException('The container not contain array on this key : '.$key);
        }
    }

    /**
     * allow to load multiple definition from an array
     * @param array $definitions
     */
    public function appendDefinition(array $definitions){
        foreach ($definitions as $key => $value){
            if(is_array($value) && key_exists(IContainer::ADD, $value)){
                $this->add($key, $value[IContainer::ADD]);
            }else{
                $this->set($key, $value);
            }
        }
        
        
    }
    /**
     * Return an instance linked to the key, it use lazy loading
     * and return always the same instance.
     * @param type $key
     * @return mixed
     */
    public function get($key) {
        if($this->hasConstants($key)){
            return $this->container[$key];
        }
        elseif(!array_key_exists($key, $this->instances))
        {
            if($this->has($key))
            {
                $this->instances[$key]=$this->container[$key]($this);
            }
            else
            {
                $this->instances[$key]=($this->resolve($key));
            }
        }
        return $this->instances[$key];
    }

    /**
     * Return true when the <b>container</b> contains the key
     * @param type $key
     * @return bool
     */
    public function has($key): bool {
        return array_key_exists($key, $this->container);
    }
    
    /**
     * Return true when the <b>container</b> contains the key and the value
     * contained is not a Closure\callable
     * @param string $key
     * @return boolean
     */
    public function hasConstants(string $key):bool{
        if($this->has($key)){
            return ! ($this->container[$key] instanceof \Closure);
        }
        return false;
    }
    
    /**
     * Create a new instance
     * @param type $key
     * @return mixed
     */
    public function make($key) 
    {
        if($this->has($key)){      
            return $this->container[$key]();
        }
        return $this->resolve($key);
        
    }

    /**
     * Try to resolve the class , if class not found throw exception,
     * if class constructor contain arguments try to resolve, if any get a new instance
     * @param string $key
     * @return type
     * @throws DIException
     */
    private function resolve(string $key) {
        $reflected_class = new ReflectionClass($key);
        
        if ($reflected_class->isInstantiable()) {
            $constructor = $reflected_class->getConstructor();
            if ($constructor)
            {
                return $reflected_class->newInstanceArgs($this->buildArguments($constructor));
            }
            else
            {
                return $reflected_class->newInstance();
            }
        } 
        throw new ContainerException('The ' . $key . ' is not Instanciable');
        
    }
    
    /**
     * Try to build argument , if the argument is a class the call get method to try to resolve it,
     * or if the argument is not a class it must have a default value , any else an exception is throw
     * @param  [type] $constructor [description]
     * @return [type]              [description]
     */
    private function buildArguments($constructor) {
        $parameters = $constructor->getParameters();
        $parametersBuilded = [];
        foreach ($parameters as $p) 
        {
            if ($p->getClass())
            {
                $parametersBuilded[] = $this->get($p->getClass()->getName());
            }
            else
            {
                $parametersBuilded[] = $p->getDefaultValue();
            }
        }
        return $parametersBuilded;
    }
}
