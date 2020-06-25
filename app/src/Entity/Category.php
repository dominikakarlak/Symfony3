<?php
/**
 *  Category entity
 */
namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 *
 * Class Category
 *
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @ORM\Table(name="categories")
 *
 * @UniqueEntity(fields={"name"})
 */
class Category
{
    /**
     * Primary key
     *
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Name.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     *
     *
     *
     */
    private $name;

    /**
     * Vinyls.
     * @var \Doctrine\Common\Collections\ArrayCollection\App\Entity\Vinyl[] $vinyls Vinyls
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Vinyl",
     *     mappedBy="category"
     * )
     *
     *
     */
    private $vinyls;

    /**
     * Category constructor.
     *
     *
     */

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
     * Getter for Name.
     *
     * @return string|null Category
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for Name.
     *
     * @param string $name Name
     * @return Category
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
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
            $vinyl->setCategory($this);
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
            if ($vinyl->getCategory() === $this) {
                $vinyl->setCategory(null);
            }
        }

        return $this;
    }
}
