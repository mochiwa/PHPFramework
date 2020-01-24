<?php
session_start();
use Framework\Session\SessionManager;
use PHPUnit\Framework\TestCase;

/**
 * Description of SessionManagerTest
 *
 * @author mochiwa
 */
class SessionManagerTest extends TestCase {
    private $manager;
    private $session;
    
    public function setUp() {
        $this->session=$this->getMockBuilder(\Framework\Session\PhpSession::class)->setMethods(['isStarted','start','set','get','remove'])->getMock();
        $this->manager=new SessionManager($this->session);
    }
    
    function test_isActive_shouldReturnTrue_whenSessionIsStarted()
    {
        $this->session->method('isStarted')->willReturn(true);
        $this->assertTrue($this->manager->isActive());
    }
    
    function test_isActive_shouldReturnFalse_whenSessionIsNotStarted()
    {   
        $this->session->method('isStarted')->willReturn(false);
        $this->assertFalse($this->manager->isActive());
    }
    
    function test_set_ShouldStartSession_whenItClose()
    {
        $this->session->expects($this->once())->method('start');
       
        $this->manager->set('aKey');
    }
    
    
    function test_set_ShouldAppendTheKeyWithNullToTheSession_whenParamsIsNotSpecified()
    {
        $this->session->expects($this->once())->method('get')->willReturn(null);
        $this->manager->set('user');
        $this->assertNull($this->manager->get('user'));
    }
    function test_set_ShouldAppendTheValueToTheSession_whenParamsIsSpecified()
    {
        $this->session->expects($this->once())->method('get')->willReturn('johnDoe');
        
        $this->manager->set('user','johnDoe');
        $this->assertEquals('johnDoe',$this->session->get('user'));
    }
    
    function test_delete_ShouldDeleteTheDataFromSession()
    {
        $this->session->expects($this->once())->method('remove');
        
        $this->manager->set('user','johnDoe');
        $this->manager->delete('user');
        $this->assertNull($this->session->get('user'));
    }
    
    
   
    
    
}
