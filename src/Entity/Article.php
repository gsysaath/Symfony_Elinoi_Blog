<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
  *  normalizationContext={"groups"={"article:read"}},
  *  denormalizationContext={"groups"={"article:write"}},
  *  collectionOperations={
  *      "get",
  *      "post"={"normalization_context"={"groups"={"article:collection:read"}}}
  *  },
  *  itemOperations={
  *                  "get"={"normalization_context"={"groups"={"article:collection:read"}}},
  *                  "put"={"normalization_context"={"groups"={"article:collection:read"}}},
  *                  "delete"
  *  }
 * )
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Author::class, inversedBy="articles_id")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"article:read", "article:write", "article:collection:read"})
     */
    private $author_id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"article:read", "article:write", "article:collection:read", "author:collection:read"})
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"article:collection:read", "article:write"})
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"article:read", "article:collection:read", "author:collection:read"})
     */
    private $created_at;
    
    /**
     * @ORM\Column(type="datetime")
     * @Groups({"article:read", "article:collection:read", "author:collection:read"})
     */
    private $updated_at;
    
    // TEST DE SET UP CREATED AT ET UPDATE AT
    /**
     * Gets triggered only on insert
     * @ORM\PrePersist
     */
    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();
    }
    /**
     * Gets triggered every time on update
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updated_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
      return $this->id;
    }

    public function getAuthorId(): ?Author
    {
        return $this->author_id;
    }

    public function setAuthorId(?Author $author_id): self
    {
        $this->author_id = $author_id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

}
