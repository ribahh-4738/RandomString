<?php
namespace Super\RandomString\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

class Grid extends Container
{
    protected function _construct()
    {
        $this->_controller = 'adminhtml_grid';
        $this->_blockGroup = 'Super_RandomString';
        $this->_headerText = __('Grid');
        parent::_construct();
        $this->buttonList->remove('add');
    }
}
