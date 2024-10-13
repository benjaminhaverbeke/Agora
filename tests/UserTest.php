<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function testSomething(): void
    {

        self::bootKernel();
        $container = static::getContainer();

        $user = new User();
        $user->setUsername('George')
            ->setPassword('test')
            ->setCreatedAt(new \DateTimeImmutable())
            ->setEmail('george@gmail.com');


        $errors = $container->get('validator')->validate($user);
        $this->assertCount(0, $errors);

    }
}
