<?php
namespace Super\RandomString\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

class Random extends AbstractDb
{
	public function __construct(
		Context $context
	){
		parent::__construct($context);
	}

    protected function _construct(): void
    {
		$this->_init(
            'random_table',
            'id_random'
        );
	}
}
