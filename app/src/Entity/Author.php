<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class Author.
 *
 * @ORM\Entity(repositoryClass="App\Repository\AuthorRepository")
 * @ORM\Table(name="authors")
 */
class Author
{
    /**
     * Primary key.
     *
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     *
     *@Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="100",
     *)
     *
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Vinyl::class, mappedBy="author")
     */
    private $vinyls;

    public function __construct()
    {
        $this->vinyls = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for name.
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Getter for vinyls.
     *
     * @return Collection|Vinyl[]
     */
    public function getVinyls(): Collection
    {
        return $this->vinyls;
    }

    /**
     * @param Vinyl $vinyl
     * @return $this
     */
    public function addVinyl(Vinyl $vinyl): self
    {
        if (!$this->vinyls->contains($vinyl)) {
            $this->vinyls[] = $vinyl;
            $vinyl->setAuthor($this);
        }

        return $this;
    }

    /**
     * @param Vinyl $vinyl
     * @return $this
     */
    public function removeVinyl(Vinyl $vinyl): self
    {
        if ($this->vinyls->contains($vinyl)) {
            $this->vinyls->removeElement($vinyl);
            // set the owning side to null (unless already changed)
            if ($vinyl->getAuthor() === $this) {
                $vinyl->setAuthor(null);
            }
        }

        return $this;
    }
}
