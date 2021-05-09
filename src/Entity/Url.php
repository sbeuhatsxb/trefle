<?php

namespace App\Entity;

use App\Repository\UrlRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=UrlRepository::class)
 */
#[ApiResource(
    routePrefix: '/v1',
    collectionOperations: [
        'get' => ['method' => 'get'],
    ],
    itemOperations: [
        'get' => ['method' => 'get'],
    ],
)]
class Url
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['read:Plant'])]
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['read:Plant'])]
    private $url;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }
}
