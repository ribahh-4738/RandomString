<?php
namespace Super\RandomString\Model\ResourceModel\Random;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
	protected $_idFieldName = 'id_random';

	protected $_eventPrefix = 'super_randomstring_random_collection';

	protected $_eventObject = 'random_collection';

    protected function _construct(): void
    {
		$this->_init(
            'Super\RandomString\Model\Random',
            'Super\RandomString\Model\ResourceModel\Random'
        );
	}
}
