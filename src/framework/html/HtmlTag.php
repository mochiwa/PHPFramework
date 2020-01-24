<?php

namespace Framework\Html;

/**
 * This class is responsible to generate html tag
 * with children and attribute
 *
 * @author mochiwa
 */
class HtmlTag {
    /**
     * The name of the tag like body , input ,a,...
     * @var string
     */
    private $name;
    /**
     * The id used in HTML
     * @var string 
     */
    protected $htmlId;
    /**
     * List of attribute like class="", href="",...
     * @var Array array with Attribute objects @see Attribute
     */
    protected $attributes;
    /**
     * set if this tag is an empty tag like <input> , <br> , ....
     * @var type 
     */
    private $isEmptyTag;
    
    /**
     * All HtmlTag children between this tag,
     * The list works like a stack FIFO
     * @var array of self 
     */
    protected $children;
    
    protected function __construct(string $name,array $attributes=[],bool $isEmpty=false) {
        $this->name=$name;
        $this->isEmptyTag=$isEmpty;
        $this->children=[];
        $this->attributes=[];
        foreach ($attributes as $attribute) {
            $this->addAttribute($attribute);
        }
    }
    
    /**
     * Make a tag
     * @param string $name
     * @param array $attributes
     * @return \self
     */
    public static function make(string $name,array $attributes=[]) : self
    {
        return new self($name,$attributes);
    }
    
    /**
     * An empty HTML tag is a tag without closing tag like <br> or <input>
     * @param string $name
     * @param array $attributes
     * @return \self
     */
    public static function empty(string $name,array $attributes=[]) :self
    {
        return new self($name,$attributes,true);
    }
    
    /**
     * Build the html tag
     * @return string
     */
    public function toHtml():string
    {
        $openTag=$this->buildOpenTag();
        
        if($this->isEmptyTag){
            return $openTag;
        }
        
        $content='';
        foreach ($this->children as $child)
        {
            $content.= $child instanceof self ? $child->toHtml() : $child;
        }
        $closingTag='</'.$this->name.'>';
        return $openTag . $content .$closingTag;
    }
   
    /**
     * Build the open tag with all attributes from self::attributes
     * separated by space
     * @return string
     */
    private function buildOpenTag():string
    {
        $openTag='<'.$this->name;
        foreach ($this->attributes as $attribute){
            $openTag.=' '.$attribute->format();
        }
        $openTag.='>';
        return $openTag;
    }
    
    /**
     * append html tag between open and close tag
     * @param self $content
     * @return \self
     */
    public function addChild(self $content):self
    {
        $this->children[]=$content;
        
        return $this;
    }
    
    public function addChildOnTop(self $content):self
    {
        array_unshift($this->children, $content);
        return $this;
    }
    /**
     * append text between open and close tag
     * @param string $content
     * @return \self
     */
    public function addText(string $content):self
    {
        $this->children[]= htmlentities($content);
        return $this;
    }
    
    /**
     * Append style to the html tag
     * @param string $style
     * @return \self
     */
    public function addStyle(string $style):self
    {
        if(!key_exists('class', $this->attributes))
        {
            $this->attributes['class']=new Attribute('class');
        }
        $this->attributes['class']->addContent($style);
        return $this;
    }
    
    /**
     * Append a attribute , if the attribute already exist then is not replaced,
     * use setAttribute instead.
     * @see Attribute
     * @param \Framework\Html\Attribute $attribute
     * @return \self
     */
    public function addAttribute(Attribute $attribute):self
    {
        if(!in_array($attribute->name(), $this->attributes)){
            $this->attributes[$attribute->name()]=$attribute;
        }
        return $this;
    }
    /**
     * Set an attribute if the attribute already exist then replace it
     * @param \Framework\Html\Attribute $attribute
     * @return \self
     */
    public function setAttribute(Attribute $attribute):self
    {
        $this->attributes[$attribute->name()]=$attribute;
        return $this;
    }
    
    /**
     * Set the id of the tag, the Id attribute is always
     * recreate into a new Attribute.
     * @param string $id
     */
    public function setId(string $id) :self
    {
        $this->htmlId=$id;
        $this->attributes['id']=new Attribute('id',[$id]);
        return $this;
    }
    
    /**
     * Return the id , if tag has not id return empty string
     * @return string
     */
    public function getId():string
    {
        if(key_exists('id', $this->attributes))
        {
            return $this->attributes['id']->getContents();
        }
        return '';
    }
    /**
     * Return true if this tag has the attribute
     * @param string $attributeName
     * @return bool
     */
    public function hasAttribute(string $attributeName):bool
    {
        return key_exists($attributeName, $this->attributes);
    }
    /**
     * Return the count of children contained
     * @return int
     */
    public function childrenCount():int
    {
        return sizeof($this->children);
    }
    
    /**
     * Search child that have the id attribute
     * @param string $id
     * @return \Framework\Html\HtmlTag|null
     */
    public function searchChildById(string $id) : ?HtmlTag
    {
        foreach ($this->children as $child)
        {
            if($child instanceof self)
            {
                if($child->getId()===$id)
                {
                   return $child; 
                }
                elseif($child->childrenCount())
                {
                    $tmp= $child->searchChildById($id);
                    if($tmp!==null)
                        return $tmp;
                }
            }
        }
        return null;
    }
    
    /**
     * Set the tag name
     * @param string $name
     */
    protected function setName(string $name)
    {
        $this->name=$name;
    }
}
