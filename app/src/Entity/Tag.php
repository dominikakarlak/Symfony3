<?php
/**
 * Tag entity.
 */

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Tag.
 *
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 * @ORM\Table(name="tags")
 *
 * @UniqueEntity(fields={"title"})
 */
class Tag
{
    /**
     * Primary key.
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;



    /**
     * Title.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=64,
     * )
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="64",
     * )
     */
    private $title;

    /**
     * Vinyls.
     *
     * @var \Doctrine\Common\Collections\ArrayCollection|\App\Entity\Vinyl[] Vinyls
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Vinyl", mappedBy="tags")
     *
     * @Assert\Type(type="Doctrine\Common\Collections\ArrayCollection")
     */
    private $vinyls;

    /**
     * Tag constructor.
     */
    public function __construct()
    {
        $this->vinyls = new ArrayCollection();
    }

    /**
     * Getter for Id.
     *
     * @return int|null Result
     */
    public function getId(): ?int
    {
        return $this->id;
    }




    /**
     * Getter for Title.
     *
     * @return string|null Title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter for Title.
     *
     * @param string $title Title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Getter for vinyls.
     *
     * @return \Doctrine\Common\Collections\Collection|\App\Entity\Vinyl[] Vinyls collection
     */
    public function getVinyls(): Collection
    {
        return $this->vinyls;
    }

    /**
     * Add vinyl to collection.
     *
     * @param \App\Entity\Vinyl $vinyl Vinyl entity
     */
    public function addVinyl(Vinyl $vinyl): void
    {
        if (!$this->vinyls->contains($vinyl)) {
            $this->vinyls[] = $vinyl;
            $vinyl->addTag($this);
        }
    }

    /**
     * Remove Vinyl from collection.
     *
     * @param \App\Entity\Vinyl $vinyl Vinyl entity
     */
    public function removeVinyl(Vinyl $vinyl): void
    {
        if ($this->vinyls->contains($vinyl)) {
            $this->vinyls->removeElement($vinyl);
            $vinyl->removeTag($this);
        }
    }
}