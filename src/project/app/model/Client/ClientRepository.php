<?php
declare(strict_types=1);

namespace App\Model\Client;

use App\Model\BaseRepository;
use App\Model\Client\Exceptions\ClientNotFoundException;
use Kdyby\Doctrine\ResultSet;

class ClientRepository extends BaseRepository
{

	protected function getEntityName(): string
	{
		return Client::class;
	}

	public function getAll(): ResultSet
	{
		$query = new ClientQuery();

		$data = $this->repository->fetch($query);

		if ($data->getTotalCount() === 0) {
			throw new ClientNotFoundException;
		}

		return $data;
	}
}
