<?php
namespace Jme\MainBundle\Tests\Entity;

use Jme\MainBundle\Component\Test\ServiceTestCase,
    Jme\MainBundle\Entity\User,
    Symfony\Component\Validator\ValidatorFactory;

class UserEntityTest extends ServiceTestCase
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
        $user = new User('jme', 'my-password', 'janimatti.ellonen@gmail.com');

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
        $user = new User('jme', 'my-password', 'janimatti.ellonen@gmail..com');

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
        $user = new User('jme', 'my-password', null);

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
        $user = new User('jme', 'my-password', 'janimatti.ellonen@gmail.com');
        $user2 = new User('jme', 'my-password', 'janimatti.ellonen@gmail.com');

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
        $user = new User('jm', 'my-password', 'janimatti.ellonen@gmail.com');

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
        $user = new User(str_repeat('j', 33), 'my-password', 'janimatti.ellonen@gmail.com');

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
        $user = new User('jme', 'pwd', 'janimatti.ellonen@gmail.com');

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
        $user = new User('jme', str_repeat('j', 129), 'janimatti.ellonen@gmail.com');

        $errors = $this->validator->validate($user);

        $this->assertCount(1, $errors);
    }



}
