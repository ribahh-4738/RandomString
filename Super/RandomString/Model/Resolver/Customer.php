<?php
namespace Super\RandomString\Model\Resolver;

use Magento\Authorization\Model\UserContextInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use Magento\Framework\GraphQl\Query\Resolver\ValueFactory;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Webapi\ServiceOutputProcessor;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Psr\Log\LoggerInterface;
use Super\RandomString\Model\RandomFactory;

class Customer implements ResolverInterface
{
    private ValueFactory $valueFactory;

    private CustomerFactory $customerFactory;

    protected ServiceOutputProcessor $serviceOutputProcessor;

    protected ExtensibleDataObjectConverter $dataObjectConverter;

    protected LoggerInterface $logger;

    protected CustomerRepositoryInterface $customerRepository;

    protected RandomFactory $_randomFactory;

    public function __construct(
        ValueFactory $valueFactory,
        CustomerFactory $customerFactory,
        ServiceOutputProcessor $serviceOutputProcessor,
        ExtensibleDataObjectConverter $dataObjectConverter,
        CustomerRepositoryInterface $customerRepository,
        RandomFactory $randomFactory,
        LoggerInterface $logger
    ) {
        $this->valueFactory = $valueFactory;
        $this->customerFactory = $customerFactory;
        $this->serviceOutputProcessor = $serviceOutputProcessor;
        $this->dataObjectConverter = $dataObjectConverter;
        $this->customerRepository = $customerRepository;
        $this->_randomFactory = $randomFactory;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(
        Field $field,
              $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) : Value
    {
        global $e;
        if ((!$context->getUserId()) || $context->getUserType() == UserContextInterface::USER_TYPE_GUEST) {
            throw new GraphQlAuthorizationException(
                __(
                    'Maybe Customer is not logged in',
                    [\Magento\Customer\Model\Customer::ENTITY]
                )
            );
        }
        try {
            $data = $this->getCustomerData($context->getUserId());
            $result = function () use ($data) {
                return !empty($data) ? $data : [];
            };
            return $this->valueFactory->create($result);
        } catch (NoSuchEntityException $exception) {
            throw new GraphQlNoSuchEntityException(__($exception->getMessage()));
        } catch (LocalizedException $exception) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()));
        }
    }

    /**
     *
     * @throws NoSuchEntityException|LocalizedException
     */
    private function getCustomerData($customerId) : array
    {
        try {
            $customerData = [];
            $customerColl = $this->customerFactory->create()->getCollection()
                ->addFieldToFilter("entity_id", ["eq"=>$customerId]);
            foreach ($customerColl as $customer) {
                $customerData[] = $customer->getData();
            }
            $random_string = $this->generateRandomString(10);
            $customerData[0]['random_string'] = $random_string;
            $data = [
                'random_string' => $random_string
            ];
            $random = $this->_randomFactory->create();
            $random->addData($data)->save();
            return $customerData[0] ?? [];
        }
        catch (NoSuchEntityException $e) {
            return [];
        }
        catch (LocalizedException $e) {
            throw new NoSuchEntityException(__($e->getMessage()));
        }
    }

    private function generateRandomString($length = 1): string
    {
        $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $randomString = "";
        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        return $randomString;
    }
}
