<?php
declare(strict_types = 1);

namespace App\Entity;

use Ramsey\Uuid\UuidInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Dto;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post implements \JsonSerializable
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
     * @ORM\Column(type="string", length=256)
     */
    private $title;

    /**
     *
     * @ORM\Column(type="string", length=1024)
     */
    private $content;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="posts")
     */
    private $event;

    public static function createFromDto(Dto\Post $dto): self
    {
        $post = new static();
        $post->setTitle($dto->title);
        $post->setContent($dto->content);
        
        return $post;
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

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function setEvent(Event $event): void
    {
        $this->event = $event;
    }

    public function jsonSerialize(): array
    {
        return ['id' => $this->id, 'title' => $this->title, 'content' => $this->content, 'event' => $this->event];
    }
}

