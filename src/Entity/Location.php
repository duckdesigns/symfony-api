<?php
declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\UuidInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Dto;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\LocationRepository")
 */
class Location implements \JsonSerializable
{

    /**
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     *
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     *
     * @ORM\Column(type="string", length=255)
     */
    private $latitude;

    /**
     *
     * @ORM\Column(type="string", length=255)
     */
    private $longitude;

    /**
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="location")
     */
    private $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public static function createFromDto(Dto\Location $dto): self
    {
        $location = new static();
        $location->setTitle($dto->title);
        $location->setLatitude($dto->latitude);
        $location->setLongitude($dto->longitude);
        
        return $location;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getLatitude(): string
    {
        return $this->latitude;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    public function getLongitude(): string
    {
        return $this->longitude;
    }

    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function jsonSerialize(): array
    {
        return [
                    'id' => $this->id,
                    'title' => $this->title,
                    'latitude' => $this->latitude,
                    'longitude' => $this->longitude,
                    'events' => $this->events];
    }
}

