<?php
declare(strict_types=1);

namespace App\Model\User;

use App\Model\BaseRepository;
use App\Model\User\Exceptions\UserNotFoundException;
use Kdyby\Doctrine\ResultSet;

class UserRepository extends BaseRepository
{

	protected function getEntityName(): string
	{
		return User::class;
	}

	public function getAll(): ResultSet
	{
		$query = new UserQuery();

		$data = $this->repository->fetch($query);

		if ($data->getTotalCount() === 0) {
			throw new UserNotFoundException;
		}

		return $data;
	}

	/**
	 * @param string $email
	 *
	 * @return User
	 * @throws UserNotFoundException
	 */
	public function getByEmail(string $email): User
	{
		$query = (new UserQuery())->byEmail($email);
		$res = $this->repository->fetchOne($query);

		if ($res === null) {
			throw UserNotFoundException::byEmail($email);
		}
		return $res;
	}

	/**
	 * @param string $username
	 *
	 * @return User
	 * @throws UserNotFoundException
	 */
	public function getByUsername(string $username): User
	{
		$query = (new UserQuery())->byUsername($username);
		$res = $this->repository->fetchOne($query);

		if ($res === null) {
			throw UserNotFoundException::byUsername($username);
		}
		return $res;
	}

}
