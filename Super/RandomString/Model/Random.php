<?php
namespace Super\RandomString\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class Random extends AbstractModel implements IdentityInterface
{
	const CACHE_TAG = 'super_randomstring_random';

	protected $_cacheTag = 'super_randomstring_random';

	protected $_eventPrefix = 'super_randomstring_random';

	protected function _construct()
	{
		$this->_init('Super\RandomString\Model\ResourceModel\Random');
	}

	public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}

	public function getDefaultValues()
	{
		$values = [];
		return $values;
	}
}
