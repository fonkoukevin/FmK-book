<?php

namespace App\Entity;
use \DateTime;



class Comment extends Entity
{
    protected ?int $id = null;
    protected ?string $comment = '';
    protected ?int $user_id = 0;
    protected ?int $book_id = 0;
    protected DateTime $created_at;
    protected User $user;

    public function __construct()
{
    // Spécifier le fuseau horaire (par exemple, 'Europe/Paris')
    $timezone = 'Europe/Paris';
    $this->created_at = new \DateTime('now', new \DateTimeZone($timezone));
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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

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
        Pourrait être déplacé dans une classe CommentValidator
    */
    public function validate(): array
    {
        $errors = [];
        if (empty($this->getComment())) {
            $errors['comment'] = 'Le champ commentaire ne doit pas être vide';
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
