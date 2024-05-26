<?php

namespace App\Entity;

class Author extends Entity
{
    protected ?int $id = null;
    protected ?string $last_name = '';
    protected ?string $first_name = '';
    protected ?string $nickname = '';
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(?string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(?string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(?string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        if ($this->nickname) {
            return $this->nickname;
        } else {
            return $this->first_name." ".$this->last_name;
        }
    }
}
