<?php

namespace App\Entity;

use App\Repository\VinylRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Vinyl.
 *
 * @ORM\Entity(repositoryClass="App\Repository\VinylRepository")
 * @ORM\Table(name="vinyls")
 */
class Vinyl
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
     *     length=255,
     *)
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="255",
     * )
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="vinyls")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="text")
     *
     * @Assert\Type(type="text")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="1000",
     *
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\Type(type="intiger")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="4",
     *     max="4",
     *  )
     *
     */
    private $year;

    /**
     * @ORM\ManyToOne(targetEntity=Author::class, inversedBy="vinyls")
     * @ORM\JoinColumn(nullable=false)
     *
     *
     *
     *
     */
    private $author;

    /**
     * Tags.
     *
     * @var array
     *
     * @ORM\ManyToMany(
     *     targetEntity="App\Entity\Tag",
     *     inversedBy="vinyls",
     *     orphanRemoval=true
     * )
     * @ORM\JoinTable(name="vinyls_tags")
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="vinyl", orphanRemoval=true)
     */
    private $comments;



    /**
     * Vinyl constructor.
     */

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->comment = new ArrayCollection();
        $this->comments = new ArrayCollection();
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
     * Getter for Category.
     *
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }


    /**
     * Setter for Category.
     *
     * @param Category|null $category
     * @return $this
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Getter for description.
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Setter for description.
     *
     * @param string $description
     * @return $this
     */

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Getter for year.
     *
     * @return int|null
     */

    public function getYear(): ?int
    {
        return $this->year;
    }

    /**
     * Setter for year.
     *
     * @param int $year
     * @return $this
     */

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Getter for author.
     *
     * @return Author|null
     */

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    /**
     * Setter for author.
     *
     * @param Author|null $author
     * @return $this
     */
    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Getter for tags.
     *
     * @return \Doctrine\Common\Collections\Collection|\App\Entity\Tag[] Tags collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * Add tag to collection.
     *
     * @param \App\Entity\Tag $tag Tag entity
     */
    public function addTag(Tag $tag): void
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }
    }

    /**
     * Remove tag from collection.
     *
     * @param \App\Entity\Tag $tag Tag entity
     */
    public function removeTag(Tag $tag): void
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @param Comment $comment
     * @return $this
     */
    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setVinyl($this);
        }

        return $this;
    }

    /**
     * @param Comment $comment
     * @return $this
     */
    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getVinyl() === $this) {
                $comment->setVinyl(null);
            }
        }

        return $this;
    }






}