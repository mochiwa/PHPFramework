<?php

namespace Framework\Middleware;

use Framework\Acl\AbstractTarget;
use Framework\Acl\ACL;
use Framework\Acl\ControllerTarget;
use Framework\Acl\Role;
use Framework\Router\Route;
use Framework\Session\SessionManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

/**
 * This middleware is responsible to allow or deny action on route 
 * @author mochiwa
 */
abstract class AbstractACLMiddleware implements MiddlewareInterface{
    /**
     *
     * @var SessionManager 
     */
    private $session;
    
    /**
     *
     * @var ACL 
     */
    private $acl;
    
    public function __construct(SessionManager $session, ACL $acl) {
        $this->session = $session;
        $this->acl = $acl;
    }
    
    /**
     * If the request hasn't a route then send to next handler,
     * seek the current role , if role is empty throw runtime exception (that
     * means the ACLDefinition is not well formed)
     * 
     * If the ACL has not the allow rule for current role and it's or lower role has deny rule
     * handle without route
     * If the ACL has the rule for action handle to next middleware
     * If the ACL has not the rule for action but for the whole controller handle to next Middleware
     * 
     * And finally when ACL has not the rule handle to next controller without route
     * 
     * 
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        $route=$request->getAttribute(Route::class);
        
        if(!$route){
            return $handler->handle($request);
        }
        
        $role=$this->getCurrentRole();
        if($role==Role::empty()){
            throw new RuntimeException("The base Acl must contain roles : visitor ,admin ,user");
        }
        
        $denyRoute=$this->getDenyRule($route);
        $allowRoute=$this->getAllowAction($route);
        
        
        if(!$this->acl->isRoleHasRule($role, $allowRoute)  && $this->acl->isRoleHasRule($role, $denyRoute)  ){
            return $handler->handle($request->withoutAttribute(Route::class));
        }
        
        if($this->acl->isRoleHasRule($role, $allowRoute)){
            return $handler->handle($request);
        }
        if($this->acl->isRoleHasRule($role, ControllerTarget::allow($route->target()))){
            return $handler->handle($request);
        }
        
        return $handler->handle($request->withoutAttribute(Route::class));
        
    }
   
    /**
     * Should return the current role
     */
    public abstract function getCurrentRole():Role;
    
    /**
     * Return deny action when route contain action,else deny controller
     * @param Route $route
     * @return AbstractTarget
     */
    public function getDenyRule(Route $route): AbstractTarget{
        $action=$route->param('action');
        if(empty($action)){
            return ControllerTarget::deny($route->target());
        }
        return ControllerTarget::denyAction($route->target(), $route->param('action'));
    }
    /**
     * return allow action when route contain action,else allow controller
     * @param Route $route
     * @return AbstractTarget
     */
    public function getAllowAction(Route $route): AbstractTarget{
        $action=$route->param('action');
        if(empty($action)){
            return ControllerTarget::allow($route->target());
        }
        return ControllerTarget::allowAction($route->target(), $route->param('action'));
    }

}