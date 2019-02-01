<?php
declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Entity implements \JsonSerializable
{

    /**
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    private $title;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function getId()
    {
        return $this->id;
    }

    public function jsonSerialize()
    {
        return ['id' => $this->id, 'title' => $this->title];
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }
    
    // private $venue;
    
    // private $posts;
}

