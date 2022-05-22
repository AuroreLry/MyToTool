<?php

namespace App\Entity;

use App\Repository\ListeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ListeRepository::class)]
class Liste
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $nom;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'taskList')]
    #[ORM\JoinColumn(nullable: true)]
    private $user;

    #[ORM\OneToMany(mappedBy: 'liste', targetEntity: Task::class, orphanRemoval: true) ]
    private $listsoftasks;

    public function __construct()
    {
        $this->listsoftasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getListsoftasks(): Collection
    {
        return $this->listsoftasks;
    }

    public function addListsoftask(Task $listsoftask): self
    {
        if (!$this->listsoftasks->contains($listsoftask)) {
            $this->listsoftasks[] = $listsoftask;
            $listsoftask->setListe($this);
        }

        return $this;
    }

    public function removeListsoftask(Task $listsoftask): self
    {
        if ($this->listsoftasks->removeElement($listsoftask)) {
            // set the owning side to null (unless already changed)
            if ($listsoftask->getListe() === $this) {
                $listsoftask->setListe(null);
            }
        }

        return $this;
    }


}
