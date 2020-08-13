<?php
declare(strict_types=1);

namespace App\Model\Blacklist;

use App\Model\Traits\PrimaryKey;
use App\Model\Transport\Transport;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="blacklist")
 */
class Blacklist
{
	use PrimaryKey;

	/**
	 * @var Transport|null
	 * @ORM\ManyToOne(targetEntity="App\Model\Transport\Transport")
	 * @ORM\JoinColumn(name="transport_id", referencedColumnName="id", nullable=true)
	 */
	private $transport;

	/**
	 * @var DateTime
	 * @ORM\Column(name="from_date", type="datetime", nullable=false)
	 */
	private $fromDate;

	/**
	 * @var DateTime
	 * @ORM\Column(name="to_date", type="datetime", nullable=false)
	 */
	private $toDate;

	/**
	 * @return Transport|null
	 */
	public function getTransport(): ?Transport
	{
		return $this->transport;
	}

	public function setTransport(?Transport $transport): void
	{
		$this->transport = $transport;
	}

	public function getFromDate(): DateTime
	{
		return $this->fromDate;
	}

	public function setFromDate(DateTime $fromDate): void
	{
		$this->fromDate = $fromDate;
	}

	public function getToDate(): DateTime
	{
		return $this->toDate;
	}

	public function setToDate(DateTime $toDate): void
	{
		$this->toDate = $toDate;
	}
}
