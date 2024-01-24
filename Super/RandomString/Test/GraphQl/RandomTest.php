<?php
namespace Super\RandomString\Test\GraphQl;

use Exception;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\CustomerAuthUpdate;
use Magento\Customer\Model\CustomerRegistry;
use Magento\Framework\ObjectManagerInterface;
use Magento\Integration\Api\CustomerTokenServiceInterface;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\TestCase\GraphQlAbstract;
use Throwable;

class RandomTest extends GraphQlAbstract
{
    private CustomerTokenServiceInterface $customerTokenService;

    private CustomerRegistry $customerRegistry;

    private CustomerAuthUpdate $customerAuthUpdate;

    private CustomerRepositoryInterface $customerRepository;

    private ObjectManagerInterface $objectManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->objectManager = Bootstrap::getObjectManager();
        $this->customerTokenService = $this->objectManager->get(CustomerTokenServiceInterface::class);
        $this->customerRegistry = $this->objectManager->get(CustomerRegistry::class);
        $this->customerAuthUpdate = $this->objectManager->get(CustomerAuthUpdate::class);
        $this->customerRepository = $this->objectManager->get(CustomerRepositoryInterface::class);
    }

    /**
     * @throws Exception
     */
    public function testGetCustomer()
    {
        $currentEmail = 'roni_cost@example.com';
        $currentPassword = 'roni_cost@example.com';
        $firstname = 'Veronica';
        $lastname = 'Costello';
        $query = <<<QUERY
            query {
                customer {
                    entity_id
                    firstname
                    lastname
                    email
                    random_string
                }
            }
            QUERY;
        $response = $this->graphQlQuery(
            $query,
            [],
            '',
            $this->getCustomerAuthHeaders($currentEmail, $currentPassword)
        );
        $this->assertNull($response['customer']['entity_id']);
        $this->assertEquals($firstname, $response['customer']['firstname']);
        $this->assertEquals($lastname, $response['customer']['lastname']);
        $this->assertEquals($currentEmail, $response['customer']['email']);
        $this->assertEquals($currentEmail, $response['customer']['random_string']);
    }
}
