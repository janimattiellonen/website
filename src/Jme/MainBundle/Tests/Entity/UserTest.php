<?php
namespace Jme\MainBundle\Tests\Entity;

use Jme\MainBundle\Component\Test\ServiceTestCase,
    Jme\MainBundle\Entity\User,
    Symfony\Component\Validator\ValidatorFactory;

class UserTest extends ServiceTestCase
{
    /**
     * @var \Symfony\Component\Validator\Validator
     */
    protected $validator;

    public function setUp()
    {
        parent::setUp();

        $this->validator = $this->getContainer()->get('validator');
    }

    /**
     * @test
     *
     * @group user
     * @group entity
     * @group user-entity
     */
    public function userEntityIsValid()
    {
        $user = $this->createUser();
        $errors = $this->validator->validate($user);

        $this->assertCount(0, $errors);
    }

    /**
     * @test
     *
     * @group user
     * @group entity
     * @group user-entity
     */
    public function catchesInvalidEmailAddress()
    {
        $user = $this->createUser();
        $user->setEmail('janimatti.ellonen@gmail..com');
        $errors = $this->validator->validate($user);

        $this->assertCount(1, $errors);
    }

    /**
     * @test
     *
     * @group user
     * @group entity
     * @group user-entity
     */
    public function catchesEmptyEmailAddress()
    {
        $user = $this->createUser();
        $user->setEmail(null);

        $errors = $this->validator->validate($user);

        $this->assertCount(1, $errors);
    }

    /**
     * @test
     *
     * @group user
     * @group entity
     * @group user-entity
     */
    public function catchesDuplicateEmailAddress()
    {

        $user = $this->createUser();
        $user2 = $this->createUser();

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $errors = $this->validator->validate($user2);

        $this->assertCount(1, $errors);
    }


    /**
     * @test
     *
     * @group user
     * @group entity
     * @group user-entity
     */
    public function catchesTooShortUsername()
    {
        $user = $this->createUser();
        $user->setUsername('jm');

        $errors = $this->validator->validate($user);

        $this->assertCount(1, $errors);
    }

    /**
     * @test
     *
     * @group user
     * @group entity
     * @group user-entity
     */
    public function catchesTooLongUsername()
    {
        $user = $this->createUser();
        $user->setUsername(str_repeat('j', 256));

        $errors = $this->validator->validate($user);

        $this->assertCount(1, $errors);
    }

    /**
     * @test
     *
     * @group user
     * @group entity
     * @group user-entity
     */
    public function catchesTooShortPassword()
    {
        $user = $this->createUser();
        $user->setPassword('pwd');

        $errors = $this->validator->validate($user);

        $this->assertCount(1, $errors);
    }

    /**
     * @test
     *
     * @group user
     * @group entity
     * @group user-entity
     */
    public function catchesTooLongPassword()
    {
        $user = $this->createUser();
        $user->setPassword(str_repeat('j', 256));

        $errors = $this->validator->validate($user);

        $this->assertCount(1, $errors);
    }

    /**
     * @return User
     */
    protected function createUser()
    {
        $user = new User();
        $user->setUsername('jme');
        $user->setPassword('password');
        $user->setEmail('janimatti.ellonen@gmail.com');

        return $user;
    }
}
