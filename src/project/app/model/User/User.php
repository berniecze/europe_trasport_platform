<?php
declare(strict_types=1);

namespace App\Model\User;

use App\Model\Traits\PrimaryKey;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User
{

	use PrimaryKey;

	/**
	 * @var string
	 * @ORM\Column(name="username", type="string", nullable=false)
	 */
	private $username;

	/**
	 * @var string
	 * @ORM\Column(name="password", type="string", nullable=false)
	 */
	private $password;

	/**
	 * @var string|null
	 * @ORM\Column(name="email", type="string", nullable=true)
	 */
	private $email;

	/**
	 * @var string
	 * @ORM\Column(name="role", type="string", nullable=false)
	 */
	private $role;

	public function getUsername(): string
	{
		return $this->username;
	}

	public function setUsername(string $username): void
	{
		$this->username = $username;
	}

	public function getPassword(): string
	{
		return $this->password;
	}

	public function setPassword(string $password): void
	{
		$this->password = $password;
	}

	public function getEmail(): ?string
	{
		return $this->email;
	}

	public function setEmail(?string $email): void
	{
		$this->email = $email;
	}

	public function getRole(): string
	{
		return $this->role;
	}

	public function setRole(string $role): void
	{
		$this->role = $role;
	}
}
