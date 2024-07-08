<?php

namespace App\Entity;

use App\Repository\ArticlesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ArticlesRepository::class)
 *
 * @ORM\HasLifecycleCallbacks()
 */
#[UniqueEntity(fields: ['title'], message: 'article.title.uniq')]
class Articles
{
    public const STATUS_DRAFT = 'DRAFT';
    public const STATUS_PUBLISHED = 'PUBLISHED';

    /**
     * @ORM\Id
     *
     * @ORM\GeneratedValue
     *
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    #[Assert\NotBlank(message: 'article.title.not_blank')]
    #[Assert\Type('string', message: 'article.title.type')]
    #[Assert\Length(min: 10, max: 75, minMessage: 'article.title.min_message', maxMessage: 'article.title.max_message')]
    private string $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\NotBlank(message: 'article.slug.not_blank')]
    #[Assert\Type('string', message: 'article.slug.type')]
    #[Assert\Length(min: 10, minMessage: 'article.slug.min_message')]
    private string $slug;

    /**
     * @ORM\Column(type="text")
     */
    #[Assert\NotBlank(message: 'article.shortDescription.not_blank')]
    #[Assert\Type('string', message: 'article.shortDescription.type')]
    #[Assert\Length(min: 50, minMessage: 'article.shortDescription.min_message')]
    private string $shortDescription;

    /**
     * @ORM\Column(type="text")
     */
    #[Assert\NotBlank(message: 'article.summary.not_blank')]
    #[Assert\Type('string', message: 'article.summary.type')]
    #[Assert\Length(min: 50, minMessage: 'article.summary.min_message')]
    private string $summary;

    /**
     * @ORM\Column(type="text")
     */
    #[Assert\NotBlank(message: 'article.content.not_blank')]
    #[Assert\Type('string', message: 'article.content.type')]
    #[Assert\Length(min: 50, minMessage: 'article.content.min_message')]
    private string $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTime $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTime $publishedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\NotBlank(message: 'article.mainPicture.not_blank')]
    #[Assert\Type('string', message: 'article.mainPicture.type')]
    #[Assert\Length(min: 10, minMessage: "L'image doit contenir au minimum {{ limit }} de longueur")]
    #[Assert\Regex(pattern: '/([\/|.|\w|\s|-])*\.(?:jpg|gif|png)/i', message: 'article.mainPicture.extension')]
    private string $mainPicture;

    /**
     * @ORM\ManyToMany(targetEntity=Categories::class, inversedBy="articles")
     */
    private $categories;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $status = Articles::STATUS_DRAFT;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\NotBlank(message: 'article.metaTitle.not_blank')]
    #[Assert\Type('string', message: 'article.metaTitle.type')]
    #[Assert\Length(min: 10, minMessage: 'article.metaTitle.min_message')]
    private $metaTitle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Assert\Type('string', message: 'article.mainPictureCaption.type')]
    private $main_picture_caption;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist()
     */
    public function setCreatedAtValue(): void
    {
        if (empty($this->createdAt)) {
            $this->createdAt = new \DateTime();
        }
    }

    /**
     * @ORM\PreFlush()
     */
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function computeSlug(SluggerInterface $slugger): void
    {
        $this->slug = (string) $slugger->slug($this->getSlug())->lower();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

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

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getMainPicture(): ?string
    {
        return $this->mainPicture;
    }

    public function setMainPicture(?string $mainPicture): self
    {
        $this->mainPicture = $mainPicture;

        return $this;
    }

    /**
     * @return Collection|Categories[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categories $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Categories $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function setMetaTitle(string $metaTitle): self
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    public function getMainPictureCaption(): ?string
    {
        return $this->main_picture_caption;
    }

    public function setMainPictureCaption(?string $main_picture_caption): self
    {
        $this->main_picture_caption = $main_picture_caption;

        return $this;
    }
}
