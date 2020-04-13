<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocationRepository")
 */
class Location
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\Column(type="simple_array", length=65535, nullable=true)
     */
    private $attributes = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $size;

    /**
     * @ORM\Column(type="integer")
     */
    private $contains;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Location")
     */
    private $parent;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Location")
     */
    private $prev;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Location")
     */
    private $next;

    /**
     * @ORM\Column(type="text", nullable=true, length=16777215)
     */
    private $description;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getAttributes(): ?array
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function getContains(): ?int
    {
        return $this->contains;
    }

    public function setContains(int $contains): self
    {
        $this->contains = $contains;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getPrev(): ?self
    {
        return $this->prev;
    }

    public function setPrev(?self $prev): self
    {
        $this->prev = $prev;

        return $this;
    }

    public function getNext(): ?self
    {
        return $this->next;
    }

    public function setNext(?self $next): self
    {
        $this->next = $next;

        // set (or unset) the owning side of the relation if necessary
        $newPrev = null === $next ? null : $this;
        if ($next->getPrev() !== $newPrev) {
            $next->setPrev($newPrev);
        }

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
