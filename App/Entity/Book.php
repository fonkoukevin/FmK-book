<?php

namespace App\Entity;



class Book extends Entity
 {
    protected ?int $id = null;
    protected ?string $title = '';
    protected ?string $description = '';
    protected ?string $image = '';
    protected ?int $type_id = 0;
    protected ?int $author_id = 0;
    protected Type $type;
    protected Author $author;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }


    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }


    public function getImage(): ?string
    {
        return $this->image;
    }


    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getTypeId(): ?int
    {
        return $this->type_id;
    }

    public function setTypeId(?int $type_id): self
    {
        $this->type_id = $type_id;

        return $this;
    }

    public function getAuthorId(): ?int
    {
        return $this->author_id;
    }

    public function setAuthorId(?int $author_id): self
    {
        $this->author_id = $author_id;

        return $this;
    }

  
    public function getType(): Type
    {
        return $this->type;
    }

    public function setType(Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function setAuthor(Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getImagePath():string
    {
        if (!empty($this->getImage())) {
            return _BOOKS_IMAGES_FOLDER_.$this->getImage();
        } else {
            return _ASSETS_IMAGES_FOLDER_.'default-book.jpg';
        }
    }

    /*
        Devrait être déplacé dans une classe BookValidator
    */
    public function validate():array
    {
        $errors = [];
        if (empty($this->getTitle())) {
            $errors['title'] = 'Le champ titre ne doit pas être vide';
        }
        return $errors;
    }



 }