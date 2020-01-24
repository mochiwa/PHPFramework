<?php

namespace Framework\Renderer;

use Framework\DependencyInjection\IContainer;
use Twig\Environment;

/**
 * Description of TwigFactory
 *
 * @author mochiwa
 */
class TwigFactory {
    
    /**
     * the di container
     * @var IContainer 
     */
    private $container;
    
    /**
     * list of extension instance
     * @var array
     */
    private $extentions;
    
    /**
     *
     * @var The twig loader 
     */
    private $loader;
    /**
     *
     * @var type 
     */
    private $twig;
    
    public function __construct(IContainer $container,$loader) {
        $this->container = $container;
        $this->loader = $loader;
    }

    public function __invoke(array $extensions=[]) : IViewBuilder {
        $this->twig=new Environment($this->loader);
        foreach ($extensions as $extension){
            $this->twig->addExtension($this->container->get($extension));
        }
        
        
        
        
        return new TwigViewBuilder($this->loader, $this->twig);
    }
    
}
