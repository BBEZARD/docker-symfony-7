<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

	public function testGetRolesTypeUser()
	{
		$user = new User();
		$user
			->setFullname('bruno')
			->setEmail('bruno.bezard@gmail.com')
			->setRoles(['ROLE_ADMIN'])
			;
		$result = $user->getRoles();

		$this->assertSame(['ROLE_ADMIN', 'ROLE_USER'], $result);
	}

}
