<?php
declare(strict_types=1);

namespace App\Model;

use Nette\Http\FileUpload;
use Nette\Utils\Finder;
use SplFileInfo;

class PhotoService
{

	private const DESTINATION_PHOTOS_DIR_RELATIVE = __DIR__ . '/../../www/images/destination/';
	private const TRANSPORT_PHOTOS_DIR_RELATIVE   = __DIR__ . '/../../www/images/transport/';

	/**
	 * @var string
	 */
	private $hostname;

	public function __construct(string $hostname)
	{
		$this->hostname = $hostname;
	}

	public function saveDestinationPhoto(FileUpload $file): string
	{
		$ext = pathinfo($file->getName(), PATHINFO_EXTENSION);

		$uploadedFileName = uniqid((string)rand(0, 20), true) . '.' . $ext;
		$path = self::DESTINATION_PHOTOS_DIR_RELATIVE . $uploadedFileName;
		$file->move($path);

		return $uploadedFileName;
	}

	public function saveTransportPhoto(FileUpload $file): string
	{
		$ext = pathinfo($file->getName(), PATHINFO_EXTENSION);

		$uploadedFileName = uniqid((string)rand(0, 20), true) . '.' . $ext;
		$path = self::TRANSPORT_PHOTOS_DIR_RELATIVE . $uploadedFileName;
		$file->move($path);

		return $uploadedFileName;
	}

	public function getDestinationPhoto(string $filename): ?string
	{
		$photo = null;
		foreach (Finder::findFiles($filename)->in(self::DESTINATION_PHOTOS_DIR_RELATIVE) as $key => $file) {
			$photo = $file;
		}
		if ($photo !== null) {
			/** @var SplFileInfo $photo */
			$filePath = explode('/project/www/', $photo->getRealPath());
			return sprintf('%s/%s', $this->hostname, $filePath[1]);
		}
		return null;
	}

	public function getTransportPhoto(string $filename): ?string
	{
		$photo = null;
		foreach (Finder::findFiles($filename)->in(self::TRANSPORT_PHOTOS_DIR_RELATIVE) as $key => $file) {
			$photo = $file;
		}
		if ($photo !== null) {
			/** @var SplFileInfo $photo */
			$filePath = explode('/project/www/', $photo->getRealPath());
			return sprintf('%s/%s', $this->hostname, $filePath[1]);
		}
		return null;
	}

	public function removeTransportPhoto(string $filename): void
	{
		foreach (Finder::findFiles($filename)->in(self::TRANSPORT_PHOTOS_DIR_RELATIVE) as $key => $file) {
			/** @var SplFileInfo $file */
		    unlink($file->getRealPath());
		}
	}

	public function removeDestinationPhoto(string $filename): void
	{
		foreach (Finder::findFiles($filename)->in(self::DESTINATION_PHOTOS_DIR_RELATIVE) as $key => $file) {
			unlink($file);
		}
	}
}
