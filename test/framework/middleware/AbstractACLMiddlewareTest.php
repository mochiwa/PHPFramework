<?php

namespace Test\Framework\Middleware;

use App\Identity\Model\User\User;
use Framework\Acl\ACL;
use Framework\Acl\ControllerTarget;
use Framework\Acl\Role;
use Framework\Middleware\AbstractACLMiddleware;
use Framework\Router\Route;
use Framework\Session\SessionManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

/**
 * Description of ACLMiddlewareTest
 *
 * @author mochiwa
 */
    class AbstractACLMiddlewareTest extends TestCase {

    private $session;
    private $acl;
    private $middleware;

    protected function setUp() {
        $this->session = $this->createMock(SessionManager::class);
        $this->acl = $this->createMock(ACL::class);
        $this->middleware = $this->getMockForAbstractClass(AbstractACLMiddleware::class,[$this->session,$this->acl]);
//$this->createPartialMock(AbstractACLMiddleware::class, ['getCurrentRole']);//new ACLMiddleware($this->session, $this->acl);
    }
    
    private function mockHandler() : MockObject{
        $handler=$this->createMock(RequestHandlerInterface::class);
        return $handler;
    }
    
    private function mockRequest(string $routeName='name',string $target='target'):MockObject{
        $request=$this->createMock(ServerRequestInterface::class);
        $request->method('getAttribute')->with(Route::class)->willReturn(new Route($routeName, $target));
        $request->method('withoutAttribute')->willReturnSelf();
        return $request;
    }
    

    
    function test_process_shouldHandleRequest_whenRequestHasNotRoute(){
        $request=$this->mockRequest();
        $nextHandler=$this->mockHandler();
        
        $request->expects($this->once())->method('getAttribute')->with(Route::class)->willReturn(null);
        $nextHandler->expects($this->once())->method('handle')->with($request);
        $this->middleware->process($request, $nextHandler);
    }

    /*function test_getCurrentRole_shouldReturnRoleVisitor_whenSessionHasNoUserConnected(){
        $this->session->expects($this->once())->method('get')->with(SessionManager::CURRENT_USER_KEY)->willReturn(null);
        $this->acl->expects($this->once())->method('getRoleByName')->with('visitor')->willReturn(Role::of('visitor',0));
             
        $this->assertEquals('visitor', $this->middleware->getCurrentRole()->name());
    }*/
   /* function test_getCurrentRole_shouldReturnRoleUser_whenSessionHasUserConnected(){
        $this->session->expects($this->once())->method('get')->with(SessionManager::CURRENT_USER_KEY)->willReturn($this->createMock(User::class));
        $this->acl->expects($this->once())->method('getRoleByName')->with('user')->willReturn(Role::of('user',1));
             
        $this->assertEquals('user', $this->middleware->getCurrentRole()->name());
    }
    function test_getCurrentRole_shouldReturnRoleAdmin_whenUserConnectedIsAdmin(){
        $user=$this->createMock(User::class);
        $this->session->expects($this->once())->method('get')->with(SessionManager::CURRENT_USER_KEY)->willReturn($user);
        $user->expects($this->once())->method('isAdmin')->willReturn(true);
        $this->acl->expects($this->once())->method('getRoleByName')->with('admin')->willReturn(Role::of('admin',99));
             
        $this->assertEquals('admin', $this->middleware->getCurrentRole()->name());
    }
    
    function test_process_shouldThrowException_whenACLReturnEmptyRole(){
        $request=$this->mockRequest();
        $nextHandler=$this->mockHandler();
        
        
       
        
        $this->acl->expects($this->once())->method('getRoleByName')->willReturn(Role::empty());
        $this->expectException(RuntimeException::class);
        $this->middleware->process($request, $nextHandler);
    }*/

    
    function test_getDenyRule_shouldReturnDenyRuleForAction_whenRouteContainActionParam(){
        $route=new Route('aRouteName','aController',['action'=>'anAction']);
        $rule= ControllerTarget::denyAction('aController','anAction');
        
        $this->assertEquals($rule, $this->middleware->getDenyRule($route));
    }
    function test_getDenyRule_shouldReturnDenyRuleForController_whenRouteNotContainActionParam(){
        $route=new Route('aRouteName','aController');
        $rule= ControllerTarget::deny('aController');
        
        $this->assertEquals($rule, $this->middleware->getDenyRule($route));
    }
    
    function test_process_shouldHandleRequestWithoutRoute_whenRoleHasDenyTargetForRouteAction(){
        $route=new Route('aRouteName','aController',['action'=>'anAction']);
        $role=Role::of('visitor');
        $request=$this->mockRequest();
        $nextHandler=$this->mockHandler();
        
        $request->expects($this->once())->method('getAttribute')->with(Route::class)->willReturn($route);
        $this->middleware->expects($this->any())->method('getCurrentRole')->willReturn($role);
        $this->acl->expects($this->exactly(2))->method('isRoleHasRule')->willReturn(false,true);
        
        $request->expects($this->once())->method('withoutAttribute')->with(Route::class)->willReturnSelf();
        $this->middleware->process($request, $nextHandler);
    }
    
    function test_getAllowRule_shouldReturnAllowRuleForAction_whenRouteContainActionParam(){
        $route=new Route('aRouteName','aController',['action'=>'anAction']);
        $rule= ControllerTarget::allowAction('aController','anAction');
        
        $this->assertEquals($rule, $this->middleware->getAllowAction($route));
    }
    function test_getAllowRule_shouldReturnAllowRuleForAction_whenRouteNotContainActionParam(){
        $route=new Route('aRouteName','aController');
        $rule= ControllerTarget::allow('aController');
        
        $this->assertEquals($rule, $this->middleware->getAllowAction($route));
    }
    
    function test_process_shouldHandleRequest_whenRoleHasAllowTargetForRouteAction(){
        $route=new Route('aRouteName','aController',['action'=>'anAction']);
        $role=Role::of('visitor');
        $request=$this->mockRequest();
        $nextHandler=$this->mockHandler();
        
        $request->expects($this->once())->method('getAttribute')->with(Route::class)->willReturn($route);
        $this->middleware->expects($this->any())->method('getCurrentRole')->willReturn($role);
        $this->acl->expects($this->exactly(2))->method('isRoleHasRule')->willReturnOnConsecutiveCalls(false,true);
        
        $nextHandler->expects($this->once())->method('handle');
        $this->middleware->process($request, $nextHandler);
    }
    function test_process_shouldAskACLForTheWholeController_whenACLHasNotAction(){
        $route=new Route('aRouteName','aController',['action'=>'anAction']);
        $role=Role::of('visitor');
        $request=$this->mockRequest();
        $nextHandler=$this->mockHandler();
        
        $request->expects($this->once())->method('getAttribute')->with(Route::class)->willReturn($route);
        $this->middleware->expects($this->any())->method('getCurrentRole')->willReturn($role);
        $this->acl->expects($this->exactly(3))->method('isRoleHasRule')->willReturnOnConsecutiveCalls(false,false,true);
        
        $nextHandler->expects($this->once())->method('handle');
        $this->middleware->process($request, $nextHandler);
    }
    
    function test_process_shouldHandleRequestWithoutRoute_whenRoleHasNotRuleForAction(){
        $route=new Route('aRouteName','aController',['action'=>'anAction']);
        $role=Role::of('visitor');
        $request=$this->mockRequest();
        $nextHandler=$this->mockHandler();
        
        $request->expects($this->once())->method('getAttribute')->with(Route::class)->willReturn($route);
        $this->middleware->expects($this->any())->method('getCurrentRole')->willReturn($role);
        $this->acl->expects($this->exactly(4))->method('isRoleHasRule')->willReturnOnConsecutiveCalls(false,false,false);
        
        $request->expects($this->once())->method('withoutAttribute')->with(Route::class)->willReturnSelf();
        $this->middleware->process($request, $nextHandler);
    }
    
}
