<?php
declare(strict_types = 1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class Event
{

    /**
     *
     * @Assert\NotBlank(message="Title cannot be blank")
     * @Assert\NotNull(message="Title is required")
     * @Assert\Length(max=256, maxMessage="Title cannot be longer than 256 characters")
     */
    public $title;
}