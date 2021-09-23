<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
  *  normalizationContext={"groups"={"author:read"}},
  *  denormalizationContext={"groups"={"author:write"}},
  *  collectionOperations={
  *      "get",
  *      "post"={"normalization_context"={"groups"={"author:collection:read"}}}
  *  },
  *  itemOperations={
  *                  "get"={"normalization_context"={"groups"={"author:collection:read"}}},
  *                  "delete"
  *  }
 * )
 * @ORM\Entity(repositoryClass=AuthorRepository::class)
 */
class Author
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"author:read", "author:write", "author:collection:read","article:read","article:collection:read"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"author:read", "author:write", "author:collection:read", "article:collection:read"})
     */
    private $lastname;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="author_id")
     * @Groups({"author:write", "author:collection:read"})
     */
    private $articles_id;

    public function __construct()
    {
        $this->articles_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticlesId(): Collection
    {
        return $this->articles_id;
    }

    public function addArticlesId(Article $articlesId): self
    {
        if (!$this->articles_id->contains($articlesId)) {
            $this->articles_id[] = $articlesId;
            $articlesId->setAuthorId($this);
        }

        return $this;
    }

    public function removeArticlesId(Article $articlesId): self
    {
        if ($this->articles_id->removeElement($articlesId)) {
            // set the owning side to null (unless already changed)
            if ($articlesId->getAuthorId() === $this) {
                $articlesId->setAuthorId(null);
            }
        }

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }
}
