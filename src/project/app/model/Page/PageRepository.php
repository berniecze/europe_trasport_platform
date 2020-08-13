<?php
declare(strict_types=1);

namespace App\Model\Page;

use App\Model\BaseRepository;
use App\Model\Page\Exceptions\PageNotFoundException;
use Kdyby\Doctrine\ResultSet;

class PageRepository extends BaseRepository
{

	protected function getEntityName(): string
	{
		return Page::class;
	}

	/**
	 * @return ResultSet
	 * @throws PageNotFoundException
	 */
	public function getAll(): ResultSet
	{
		$query = (new PageQuery());

		$data = $this->repository->fetch($query);

		if ($data->getTotalCount() === 0) {
			throw new PageNotFoundException;
		}

		return $data;
	}

	/**
	 * @param string $name
	 *
	 * @return Page
	 * @throws PageNotFoundException
	 */
	public function getByUrl(string $name): Page
	{
		$query = (new PageQuery())->byUrl($name);

		$data = $this->repository->fetchOne($query);

		/** @var Page|null $data */
		if ($data === null) {
			throw new PageNotFoundException();
		}

		return $data;
	}

	/**
	 * @param int $id
	 *
	 * @return Page
	 * @throws PageNotFoundException
	 */
	public function getById(int $id): Page
	{
		$query = (new PageQuery())->byId($id);

		$data = $this->repository->fetchOne($query);

		/** @var Page|null $data */
		if ($data === null) {
			throw new PageNotFoundException();
		}

		return $data;
	}

}
