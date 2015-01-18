<?php
namespace Jme\Component\Test\Fixtures;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Xi\Fixtures\FixtureFactory as BaseFixtureFactory;

class FixtureFactory extends BaseFixtureFactory
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @param EntityManager $em
     * @param Container $container
     */
    public function __construct(EntityManager $em, Container $container)
    {
        parent::__construct($em);

        $this->container = $container;

        $this->setEntityNamespace('Jme');
        $this->persistOnGet();
    }

    /**
     * @return FixtureFactory
     */
    public function setUpFixtures()
    {
        $this->define('UserBundle\Entity\User')
            ->sequence('username', 'johndoe-%d')
            ->sequence('usernameCanonical', 'johndoe-%d')
            ->sequence('email', 'john.doe%d@example.com')
            ->field('enabled', true)
            ->field('locked', false)
            ->field('expired', false)
            ->field('credentialsExpired', false)
            ->field('salt', 'my-salt')
            ->field('password', 'my-password');
    }

    /**
     * @param string $username
     * @return object
     */
    public function createUser()
    {
        return $this->get('UserBundle\Entity\User', [
            'enabled'               => true,
            'locked'                => false,
            'expired'               => false,
            'credentialsExpired'    => false,
            'password'              => 'password',
            'salt'                  => 'salt',
        ]);
    }

    /**
     * @param int $holeAmount
     *
     * @return Course
     */
    public function createCourseWithHoles($holeAmount)
    {
        $course = $this->get('CourseBundle\Entity\Course');

        $layout = $this->get('CourseBundle\Entity\Layout', [
            'course' => $course,
        ]);

        $version = $this->get('CourseBundle\Entity\Version', [
            'holes' => $this->generateHoles($holeAmount),
            'layout' => $layout
        ]);

        return $course;
    }

    /**
     * Generates $amount amount of hole entities with random pars and length.
     *
     * @param int $amount
     * @param Version $version
     *
     * @return array
     */
    public function generateHoles($amount, Version $version = null)
    {
        $holes = [];

        foreach (range(1, $amount) as $number) {
            $holes[] = $this->get('CourseBundle\Entity\Hole', [
                'number' => $number,
                'par' => rand(3, 5),
                'length' => rand(80, 190),
                'version' => (null !== $version ? $version : null),
            ]);
        }

        return $holes;
    }

    /**
     * @return object
     */
    public function createFeedback()
    {
        return $this->get('FeedbackBundle\Entity\Feedback', [
            'topic'      => 'Courses',
            'name'       => 'John Doe',
            'url'        => 'http://discgolfscores.net/testing',
            'comment'    => 'PHPUnit testing feedback comment',
        ]);
    }
}