<?php
namespace Jme\MainBundle\Component\Test;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Jme\Component\Test\Fixtures\FixtureFactory;
use Jme\UserBundle\Entity\User;


//require_once($_SERVER['KERNEL_DIR'] . "/AppKernel.php");

class ServiceTestCase extends WebTestCase
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
     * @var Client
     */
    protected $client;

    /**
     * @return Client
     */
    public function getClient()
    {
        if (null === $this->client) {
            $this->client = static::createClient();
        }

        return $this->client;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        if (!isset($this->container)) {
            $this->createClient();
            $this->container = static::$kernel->getContainer();
        }

        return $this->container;
    }

    protected function setUpFixtures()
    {
        $this->fixtureFactory = new FixtureFactory($this->getEntityManager(), $this->getContainer());
        $this->fixtureFactory->setUpFixtures();
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        if (!isset($this->entityManager)) {
            $this->entityManager  = $this->getContainer()->get('doctrine.orm.entity_manager');
            $this->schemaTool     = $this->generateSchema();
        }
        return $this->entityManager;
    }

    /**
     * @return FixtureFactory
     */
    public function getFixtureFactory()
    {
        if (!isset($this->fixtureFactory)) {
            $this->setUpFixtures();
        }

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
     * @return User
     */
    protected function createRegisteredUser()
    {
        return $this->getFixtureFactory()->createUser();
    }

    /**
     * @param User $user
     */
    protected function authenticateUser(User $user)
    {
        $client  = $this->getClient();
        $crawler = $client->request('GET', '/login');

        $crawler = $client->submit(
            $crawler->selectButton('_submit')->form(),
            array(
                '_username' => $user->getUsername(),
                '_password' => 'test',
            )
        );
        // NO LOGIN AUTHENTICATION ERRORS?
        $this->assertFalse(strpos($crawler->filter('a')->attr('href'), 'login_failure') !== false);

        $crawler = $client->followRedirect(); // every time you redirect you must follow the redirect.
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
    }

    /**
     * @param User $user
     * @return Client
     */
    protected function createAuthorizedClient(User $user)
    {
        $client = $this->getClient();
        $container = $client->getContainer();

        $session = $container->get('session');
        /** @var $userManager \FOS\UserBundle\Doctrine\UserManager */
        $userManager = $container->get('fos_user.user_manager');
        /** @var $loginManager \FOS\UserBundle\Security\LoginManager */
        $loginManager = $container->get('fos_user.security.login_manager');
        $firewallName = $container->getParameter('fos_user.firewall_name');

        //$user = $userManager->findUserBy(array('username' => 'myusername'));
        $loginManager->loginUser($firewallName, $user);

        // save the login token into the session and put it in a cookie
        $container->get('session')->set('_security_' . $firewallName,
            serialize($container->get('security.context')->getToken()));
        $container->get('session')->save();
        $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));

        return $client;
    }

    /**
     * @return User
     */
    protected function createAndAuthenticateUser()
    {
        $user = $this->createRegisteredUser();

        $this->getEntityManager()->flush();

        $this->authenticateUser($user);

        return $user;
    }
}