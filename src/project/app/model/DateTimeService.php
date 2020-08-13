<?php
declare(strict_types=1);

namespace App\Model;

use DateTime;
use DateTimeImmutable;

class DateTimeService
{

	/**
	 * @return DateTimeImmutable
	 * @throws \Exception
	 */
	public function getActualDateTime(): DateTimeImmutable
	{
		return new DateTimeImmutable();
	}

	/**
	 * @return DateTimeImmutable
	 * @throws \Exception
	 */
	public function getActualDate(): DateTimeImmutable
	{
		return new DateTimeImmutable('00:00:00');
	}

	public static function convertDateTimeToDate(DateTimeImmutable $dateTime): DateTimeImmutable
	{
		$stringTime = $dateTime->format('Y-m-d');

		return DateTimeImmutable::createFromFormat('Y-m-d H:i:s', sprintf('%s 00:00:00', $stringTime));
	}

	public static function convertImmutableToMutable(DateTimeImmutable $dateTime): DateTime
	{
		return DateTime::createFromFormat(
			'Y-m-d\TH:i:sP',
			$dateTime->format('Y-m-d\TH:i:sP'),
			$dateTime->getTimezone()
		);
	}
}
