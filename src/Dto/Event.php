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
     * @Assert\Length(max=255, maxMessage="Title cannot be longer than 255 characters")
     */
    public $title;

    /**
     *
     * @Assert\Valid
     */
    public $location;
}