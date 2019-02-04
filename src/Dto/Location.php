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
     * @Assert\Length(max=256, maxMessage="Title cannot be longer than 256 characters")
     */
    public $title;

    /**
     *
     * @Assert\NotBlank(message="Latitude cannot be blank")
     * @Assert\NotNull(message="Latitude is required")
     * @Assert\Range(min=-90.00000, max=90.00000, minMessage="Latitude must be between -90.00000 and +90.00000",
     *                        maxMessage="Latitude must be between -90.00000 and +90.00000",
     *                        invalidMessage="Value needs to be a float with a precision of 5 digits")
     * @Assert\Regex(pattern="/^-?\d+\.\d{5}$/", message="Value needs to be a float with a precision of 5 digits")
     */
    public $latitude;

    /**
     *
     * @Assert\NotBlank(message="Longitude cannot be blank")
     * @Assert\NotNull(message="Longitude is required")
     * @Assert\Range(min=-180, max=180, minMessage="Latitude must be between -180.00000 and +180.00000",
     *                        maxMessage="Latitude must be between -180.00000 and +180.00000",
     *                        invalidMessage="Value needs to be a float with a precision of 5 digits")
     * @Assert\Regex(pattern="/^-?\d+\.\d{5}$/", message="Value needs to be a float with a precision of 5 digits")
     */
    public $longitude;
}