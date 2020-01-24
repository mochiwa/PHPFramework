<?php
namespace Test\Framework\Cookie;

use Framework\Cookie\CookieManager;
use Framework\Cookie\CookieStoreException;
use PHPUnit\Framework\TestCase;
/**
 * Description of CookieManagerTest
 *
 * @author mochiwa
 */
class CookieManagerTest extends TestCase{
    private $manager;
    private $cookieStoreAdapter;
    
    
    public function setUp()
    {
        $this->cookieStoreAdapter=new CookieAdapater();
        $this->manager=new CookieManager($this->cookieStoreAdapter);
    }
            
    function test_hasCookie_shouldReturnFalse_whenUserNavgiatorHasNotTheCookie()
    {
        $this->assertFalse($this->manager->hasCookie('myCookie'));
    }
    
    function test_hasCookie_shouldReturnTrue_whenUserNavigatorHasTheCookie()
    {
        $this->cookieStoreAdapter->setCookie('myCookie', '');
        $this->assertTrue($this->manager->hasCookie('myCookie'));
    }
    
    function test_addCookie_shouldAppendCookieToCookieStore_whenCookieNameIsNotAlreadyUsed(){
        $this->manager->addCookie('aCookieName');
        $this->assertTrue($this->manager->hasCookie('aCookieName'));
    }
    
    function test_addCookie_shouldThrowCookieStoreException_whenItsAlreadyPresent()
    {
        $this->cookieStoreAdapter->setCookie('myCookie', 'aValueforCookie');
        
        $this->expectException(CookieStoreException::class);
        $this->manager->addCookie('myCookie','AnotherValueForACookie');
        $this->assertEquals('aValueforCookie',$this->cookieStoreAdapter->getStore()['myCookie']['value']);
    }
    
    function test_setCookie_shouldAppendCookieToCookieStore_whenCookieNameIsNotAlreadyUsed(){
        $this->manager->setCookie('aCookieName');
        $this->assertTrue($this->manager->hasCookie('aCookieName'));
    }
    
    function test_setCookie_shouldReplaceTheCookie_whenCookieIsAlreadyPresentInCookieStore()
    {
        $this->cookieStoreAdapter->setCookie('myCookie', 'aValueforCookie');
        
        $this->manager->setCookie('myCookie','AnotherValueForACookie');
        $this->assertEquals('AnotherValueForACookie',$this->cookieStoreAdapter->getStore()['myCookie']['value']);
    }
    
    
    function test_eraseCookie_shouldRepalceRemoveTheCookieFromTheCookieStore_whenCookieExist()
    {
        $this->cookieStoreAdapter->setCookie('myCookie', 'aValueforCookie');
        $this->manager->eraseCookie('myCookie');
        $this->assertFalse(isset($this->cookieStoreAdapter->getStore()['myCookie']));
    }
    function test_eraseCookie_shouldThrowCookieStoreException_whenCookieStoreHasNotTheCookie()
    {
        $this->expectException(CookieStoreException::class);
        $this->manager->eraseCookie('myCookie');
    }
    
    function test_getCookie_ShouldReturnAnEmptyThrowCookieStoreException_whenCookieStoreHasNotTheCookie()
    {
        $this->expectException(CookieStoreException::class);
        $this->manager->getCookie('aCookieName');
    }
    
    function test_getCookie_ShouldReturnTheCookie_whenCookieFoundInCookieStore()
    {
        $this->cookieStoreAdapter->setCookie('aCookieName','myValue',360);
        $this->assertEquals($this->cookieStoreAdapter->getStore()['aCookieName'], $this->manager->getCookie('aCookieName'));
    }
    
    function test_setCookie_shouldSerializeValueInJson_whenValueIsAnArray()
    {
        $this->manager->setCookie('aCookieName',['a','b','c']);
        
        $this->assertEquals(json_encode(['a','b','c']),  $this->cookieStoreAdapter->getStore()['aCookieName']['value']);
    }
    
    function test_getDecodedValuesFromCookie_shoulThrowCookieStoreException_whenCookieStoreHasNotTheCookie()
    {
        $this->expectException(CookieStoreException::class);
        $this->manager->getDecodedValuesFromCookie("aCookieName");
    }
    
    function test_getDecodedValuesFromCookie_shouldReturnAnArrayWithValuesFromCookie_whenValueInCookieWasEncodeFromJsonEncode()
    {
         $this->manager->setCookie('aCookieName',['a','b','c']);
         $this->assertEquals(['a','b','c'],  $this->manager->getDecodedValuesFromCookie("aCookieName"));
    }
    function test_getDecodedValuesFromCookie_shouldReturnAnArrayWithValuesFromCookie_whenValueWasString()
    {
         $this->manager->setCookie('aCookieName','My Value');
         $this->assertEquals(['My Value'],  $this->manager->getDecodedValuesFromCookie("aCookieName"));
    }
    
    
}
