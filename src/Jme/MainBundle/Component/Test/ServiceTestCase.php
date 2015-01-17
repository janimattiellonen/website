<?php
namespace Jme\MainBundle\Component\Test;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase,
    AppKernel,
    Doctrine\ORM\Tools\SchemaTool,
    Doctrine\ORM\EntityManager,
    Xi\Fixtures\FixtureFactory;

require_once($_SERVER['KERNEL_DIR'] . "/AppKernel.php");

class ServiceTestCase extends \PHPUnit_Framework_Testcase
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * @var SchemaTool
     */
    protected $tool;

    /**
     * @var AppKernel
     */
    protected $kernel;

    public function setUp()
    {
        $this->kernel = new AppKernel('test', true);
        $this->kernel->boot();

        $this->container = $this->kernel->getContainer();
        $this->entityManager = $this->container->get('doctrine')->getManager();
        $this->fixtureFactory = new FixtureFactory($this->entityManager);

        $this->generateSchema();

        parent::setUp();
    }

    /**
     * Creates a Client.
     *
     * @param array $options An array of options to pass to the createKernel class
     * @param array $server  An array of server parameters
     *
     * @return Client A Client instance
     */
    protected function createClient(array $options = array(), array $server = array())
    {
        $client = $this->kernel->getContainer()->get('test.client');
        $client->setServerParameters($server);

        return $client;
    }

    protected function getFixtureFactory()
    {
        return $this->fixtureFactory;
    }

    protected function generateSchema()
    {
        // Get the metadatas of the application to create the schema.
        $metadatas = $this->getMetadatas();

        if ( ! empty($metadatas)) {
            // Create SchemaTool
            $this->tool = new SchemaTool($this->entityManager);
            $this->tool->dropDatabase();
            $this->tool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
        } else {
            throw new \Doctrine\DBAL\Schema\SchemaException('No Metadata Classes to process.');
        }
    }

    /**
     * Overwrite this method to get specific metadatas.
     *
     * @return Array
     */
    protected function getMetadatas()
    {
        return $this->entityManager->getMetadataFactory()->getAllMetadata();
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    public function tearDown()
    {
        // Shutdown the kernel.
        $this->kernel->shutdown();

        parent::tearDown();
    }
}