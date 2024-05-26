<?php

namespace App\Entity;
use \DateTime;



class Rating extends Entity
{
    protected ?int $id = null;
    protected ?int $rate = 0;
    protected ?int $user_id = 0;
    protected ?int $book_id = 0;
    protected DateTime $created_at;
    protected User $user;


    public function __construct()
    {
        $this->created_at = date_create();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(?int $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(?int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getBookId(): ?int
    {
        return $this->book_id;
    }

    public function setBookId(?int $book_id): self
    {
        $this->book_id = $book_id;

        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTime $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    

    /*
        Pourrait être déplacé dans une classe RateValidator
    */
    public function validate(): array
    {
        $errors = [];
        if (empty($this->getRate())) {
            $errors['rate'] = 'Cliquer sur une étoile.';
        }
        if (empty($this->getBookId())) {
            $errors['book_id'] = 'Le champ book_id ne doit pas être vide';
        }
        if (empty($this->getUserId())) {
            $errors['user_id'] = 'Le champ user_id ne doit pas être vide';
        }
        return $errors;
    }


}
