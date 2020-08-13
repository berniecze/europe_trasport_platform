<?php
declare(strict_types=1);

namespace App\Model\Page;

use App\Model\Destination\Destination;
use App\Model\Traits\PrimaryKey;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="page")
 */
class  Page
{
	public const TEMPLATE_WITHOUT_FORM = 'centered';
	public const TEMPLATE_WITH_FORM    = 'centeredSearch';

	const TEMPLATE_ONE_COLUMN = 'one_column';

	use PrimaryKey;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=255, nullable=false)
	 */
	private $name;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=255, nullable=false)
	 */
	private $url;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=255, nullable=false)
	 */
	private $template;

	/**
	 * @var bool
	 * @ORM\Column(type="boolean", nullable=false)
	 */
	private $active;

	/**
	 * @var string
	 * @ORM\Column(type="text", nullable=false)
	 */
	private $content;

	/**
	 * @var string|null
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $seoDescription;

	/**
	 * @var string|null
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $title;

	/**
	 * @var string|null
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $seoKeywords;

	/**
	 * @var bool
	 * @ORM\Column(type="boolean", nullable=false)
	 */
	private $showSearchForm = false;

	/**
	 * @var Destination|null
	 * @ORM\ManyToOne(targetEntity="App\Model\Destination\Destination")
	 * @ORM\JoinColumn(name="search_default_from", referencedColumnName="id", nullable=true)
	 */
	private $searchDefaultFrom;

	/**
	 * @var Destination|null
	 * @ORM\ManyToOne(targetEntity="App\Model\Destination\Destination")
	 * @ORM\JoinColumn(name="search_default_to", referencedColumnName="id", nullable=true)
	 */
	private $searchDefaultTo;

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function getUrl(): string
	{
		return $this->url;
	}

	public function setUrl(string $url): void
	{
		$this->url = $url;
	}

	public function getTemplate(): string
	{
		return $this->template;
	}

	public function setTemplate(string $template): void
	{
		$this->template = $template;
	}

	public function isActive(): bool
	{
		return $this->active;
	}

	public function setActive(bool $active): void
	{
		$this->active = $active;
	}

	public function getContent(): string
	{
		return $this->content;
	}

	public function setContent(string $content): void
	{
		$this->content = $content;
	}

	public function getSeoDescription(): ?string
	{
		return $this->seoDescription;
	}

	public function setSeoDescription(?string $seoDescription): void
	{
		$this->seoDescription = $seoDescription;
	}

	public function getTitle(): ?string
	{
		return $this->title;
	}

	public function setTitle(?string $title): void
	{
		$this->title = $title;
	}

	public function getSeoKeywords(): ?string
	{
		return $this->seoKeywords;
	}

	public function setSeoKeywords(?string $seoKeywords): void
	{
		$this->seoKeywords = $seoKeywords;
	}

	public function isShowSearchForm(): bool
	{
		return $this->showSearchForm;
	}

	public function setShowSearchForm(bool $showSearchForm): void
	{
		$this->showSearchForm = $showSearchForm;
	}

	public function getSearchDefaultFrom(): ?Destination
	{
		return $this->searchDefaultFrom;
	}

	public function setSearchDefaultFrom(?Destination $searchDefaultFrom): void
	{
		$this->searchDefaultFrom = $searchDefaultFrom;
	}

	public function getSearchDefaultTo(): ?Destination
	{
		return $this->searchDefaultTo;
	}

	public function setSearchDefaultTo(?Destination $searchDefaultTo): void
	{
		$this->searchDefaultTo = $searchDefaultTo;
	}
}
