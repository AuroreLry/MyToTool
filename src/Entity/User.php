<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $login;

    #[ORM\Column(type: 'string', length: 255)]
    private $password;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Liste::class, orphanRemoval: true)]
    private $taskList;

    public function __construct()
    {
        $this->taskList = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, Liste>
     */
    public function getTaskList(): Collection
    {
        return $this->taskList;
    }

    public function addTaskList(Liste $taskList): self
    {
        if (!$this->taskList->contains($taskList)) {
            $this->taskList[] = $taskList;
            $taskList->setUser($this);
        }

        return $this;
    }

    public function removeTaskList(Liste $taskList): self
    {
        if ($this->taskList->removeElement($taskList)) {
            // set the owning side to null (unless already changed)
            if ($taskList->getUser() === $this) {
                $taskList->setUser(null);
            }
        }

        return $this;
    }

}
