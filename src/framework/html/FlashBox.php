<?php

namespace Framework\Html;

use Framework\Html\Factory\AbstractFlashBoxFactory;

/**
 * Description of FlashBox
 *
 * @author mochiwa
 */
class FlashBox extends HtmlTag{
    private $factory;
    
    public function __construct(AbstractFlashBoxFactory $factory=null){
        parent::__construct('div');
        if($factory===null){
            $factory=new Factory\DefaultFlashBoxFactory();
        }
        $this->factory=$factory;
        $this->addStyle($this->factory->getBoxStyle());
        $this->addChild(HtmlTag::make('p')->addStyle($this->factory->getMessageStyle())->addText($this->factory->getMessage()));
    }
}
