<?php

namespace App\Entity;


use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Dto\Application\Read\ApplicationReadDto;
use App\Dto\Application\Write\ApplicationListIndexDto;
use App\Dto\Application\Write\ApplicationWriteDto;
use App\Repository\ApplicationRepository;
use App\State\Processor\CreateApplicationProcessor;
use App\State\Processor\PatchApplicationProcessor;
use App\State\Processor\UpdateApplicationIndexProcessor;
use App\State\Provider\ApplicationHistoryProvider;
use App\State\Provider\ApplicationStateProvider;
use App\State\Provider\ChartApplicationsWeekProvider;
use App\State\Provider\RemindersStateProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/reminders',
            provider: RemindersStateProvider::class
        ),
        new Delete(),
        new Post(
            uriTemplate: '/application/create',
            input: ApplicationWriteDto::class,
            processor: CreateApplicationProcessor::class),
        new Patch(
            uriTemplate: '/application/update/{id}',
            input: ApplicationWriteDto::class,
            processor: PatchApplicationProcessor::class),

        new Patch(
            uriTemplate: '/application/update-index',
            input: ApplicationListIndexDto::class,
            processor: UpdateApplicationIndexProcessor::class
        ),
        new Get(
            uriTemplate: "/applications",
            output: ApplicationReadDto::class,
            provider: ApplicationStateProvider::class,
        ),
        new Get(
            uriTemplate: "/application/{id}/history",
            output: Interaction::class,
            provider: ApplicationHistoryProvider::class,
        ),
        new Get(
            uriTemplate: "/chart/week", provider: ChartApplicationsWeekProvider::class
        )
    ],
    paginationEnabled: false
)]
#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
class Application
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[ApiFilter(SearchFilter::class, strategy: 'partial')]
    private ?string $companyName = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $submitedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $webSite = null;

    #[ORM\ManyToOne(inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'applications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Status $status = null;

    #[ORM\Column]
    private ?int $listIndex = null;

    #[ORM\OneToMany(mappedBy: 'application', targetEntity: Interaction::class, orphanRemoval: true)]
    private Collection $interactions;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->interactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): self
    {
        $this->companyName = $companyName;

        return $this;
    }

    public function getSubmitedAt(): ?\DateTimeImmutable
    {
        return $this->submitedAt;
    }

    public function setSubmitedAt(?\DateTimeImmutable $submitedAt): self
    {
        $this->submitedAt = $submitedAt;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getWebSite(): ?string
    {
        return $this->webSite;
    }

    public function setWebSite(?string $webSite): self
    {
        $this->webSite = $webSite;

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

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getListIndex(): ?int
    {
        return $this->listIndex;
    }

    public function setListIndex(int $listIndex): self
    {
        $this->listIndex = $listIndex;

        return $this;
    }

    /**
     * @return Collection<int, Interaction>
     */
    public function getInteractions(): Collection
    {
        return $this->interactions;
    }

    public function addInteraction(Interaction $interaction): self
    {
        if (!$this->interactions->contains($interaction)) {
            $this->interactions->add($interaction);
            $interaction->setApplication($this);
        }

        return $this;
    }

    public function removeInteraction(Interaction $interaction): self
    {
        if ($this->interactions->removeElement($interaction)) {
            // set the owning side to null (unless already changed)
            if ($interaction->getApplication() === $this) {
                $interaction->setApplication(null);
            }
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

}
