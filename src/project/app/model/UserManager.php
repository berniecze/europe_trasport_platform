<?php

namespace App\Model;

use App\Model\User\Exceptions\UserNotFoundException;
use App\Model\User\UserRepository;
use Kdyby\Doctrine\EntityManager;
use Nette;
use Nette\Security\Passwords;

/**
 * Users management.
 */
final class UserManager implements Nette\Security\IAuthenticator
{
	use Nette\SmartObject;

	/**
	 * @var UserRepository
	 */
	private $repository;

	/**
	 * @var EntityManager
	 */
	private $entityManager;

	public function __construct(UserRepository $repository, EntityManager $entityManager)
	{
		$this->repository = $repository;
		$this->entityManager = $entityManager;
	}

	/**
	 * Performs an authentication.
	 *
	 * @param array $credentials
	 *
	 * @return Nette\Security\Identity
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials): Nette\Security\Identity
	{
		list($username, $password) = $credentials;

		try {
			$user = $this->repository->getByUsername($username);
			if (!Passwords::verify($password, $user->getPassword())) {
				throw new Nette\Security\AuthenticationException('The password is incorrect.',
					self::INVALID_CREDENTIAL);
			}

			if (Passwords::needsRehash($user->getPassword())) {
				$user->setPassword(Passwords::hash($password));
				$this->entityManager->persist($user);
				$this->entityManager->flush();
			}
		} catch (UserNotFoundException $exception) {
			throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);
		}

		$userData = [
			'username' => $user->getUsername(),
			'email'    => $user->getEmail(),
			'role'     => $user->getRole(),
		];
		return new Nette\Security\Identity($user->getId(), $user->getRole(), $userData);
	}

	/**
	 * @param string $username
	 * @param string $email
	 * @param string $password
	 *
	 * @throws \Exception
	 */
	public function add(string $username, string $email, string $password): void
	{
		try {
			$user = new User\User();
			$user->setUsername($username);
			$user->setPassword(Passwords::hash($password));
			$user->setEmail($email);
			$this->entityManager->persist($user);
			$this->entityManager->flush();

		} catch (\Exception $e) {
			throw $e;
		}
	}
}
