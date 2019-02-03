<?php
declare(strict_types = 1);

namespace App\Entity;

use Ramsey\Uuid\UuidInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Dto;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 * @Hateoas\Relation("self", href = "expr('/api/users/' ~ object.getId())")
 * 
 */
class Event implements \JsonSerializable
{

    /**
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     *
     */
    private $id;

    /**
     *
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Location", inversedBy="events")
     */
    private $location;

    /**
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="event")
     */
    private $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public static function createFromDto(Dto\Event $dto): self
    {
        $event = new static();
        $event->setTitle($dto->title);
        
        return $event;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function setLocation(Location $location): void
    {
        $this->location = $location;
    }

    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function jsonSerialize(): array
    {
        return ['id' => $this->id, 'title' => $this->title, 'location' => $this->location];
    }
}

