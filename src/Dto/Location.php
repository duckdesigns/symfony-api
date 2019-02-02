<?php
declare(strict_types = 1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class Location
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
     * @Assert\NotBlank(message="Latitude cannot be blank")
     * @Assert\NotNull(message="Latitude is required")
     */
    public $latitude;

    /**
     *
     * @Assert\NotBlank(message="Longitude cannot be blank")
     * @Assert\NotNull(message="Longitude is required")
     */
    public $longitude;
}