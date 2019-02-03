<?php
declare(strict_types = 1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class Post
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
     * @Assert\NotBlank(message="Content cannot be blank")
     * @Assert\NotNull(message="Content is required")
     * @Assert\Length(max=1024, maxMessage="Content cannot be longer than 1024 characters")
     */
    public $content;
}