<?php
namespace Super\RandomString\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Super\RandomString\Model\RandomFactory;

class Tab extends \Magento\Framework\View\Element\Template
{
    protected $randomFactory;

    public function __construct(
        Context $context,
        RandomFactory $randomFactory
    ){
        $this->_randomFactory = $randomFactory;
        parent::__construct($context);
    }

    public function sayHello()
    {
        return __('Hello World');
    }

    public function getRandomCollection(){
        $post = $this->_randomFactory->create();
        return $post->getCollection();
    }
}
