<?php
namespace Framework\Html;
/**
 * Represent an attribute of an HTML tag
 * like class="" or href="" ...
 *
 * @author mochiwa
 */
class Attribute {
    /**
     * The name of the attribute
     * @var string
     */
    private $name;
    
    /**
     * list of content between ""
     * @var array 
     */
    private $contents;
    
    /**
     * The name will be transform to lower and only alpha char and - will stay
     * other character will be deleted. also each element of contents will be 
     * pass through the htmlEntities
     * @param string $name
     * @param array $contents
     */
    function __construct(string $name,array $contents=[]) {
        $this->name=preg_replace('/[^a-z\-]/', '', strtolower($name));
        $this->contents=[];
        foreach ($contents as $content) {
            $this->addContent($content);
        }
    }
    
    /**
     * Create an attribute with only one value
     * useful for id or name for example
     * @param string $name
     * @param string $value
     * @return \self
     */
    public static function oneContent(string $name,string $value):self
    {
        return new self($name,[$value]);
    }
    
    /**
     * Return this attribute formatted for an HTML tag
     * @return string
     */
    public function format():string
    {
        return $this->name.'="'. implode(' ', $this->contents).'"';
    }
    
    /**
     * Append a content that will be placed between "";
     * the content will be escaped from the HTML special char.
     * Content will not be appended if it already present
     * @param string $content
     * @return \self
     */
    public function addContent(string $content) : self
    {
        if(!in_array($content, $this->contents)){
            $this->contents[]= htmlentities($content);
        }
        return $this;
    }
    
    /** 
     * Return the name of the attribute
     * @return string
     */
    public function name():string
    {
        return $this->name;
    }
    
    public function getContents():string
    {
        return implode(' ', $this->contents);
    }
    
}
