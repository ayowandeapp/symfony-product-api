<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Manufacturer;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ApiResource]
class Product
{
    /**
     * The product id
     */
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    /**
     * The MPN name
     */
    #[ORM\Column]
    private ?string $npm = null;

    /**
     * The product name
     */
    #[ORM\Column]
    private ?string $name = null;

    /**
     * The manufacturer description
     */
    #[ORM\Column(type: "text")]
    private ?string $description = null;

    /**
     * The product issue date
     */
    #[ORM\Column(type: "datetime")]

    private ?\DateTimeInterface $issueDate = null;

    /**
     * The manufacturer of the product
     */
    #[ORM\ManyToOne(targetEntity: Manufacturer::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Manufacturer $manufacturer = null;

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of npm
     */
    public function getNpm()
    {
        return $this->npm;
    }

    /**
     * Set the value of npm
     *
     * @return  self
     */
    public function setNpm($npm)
    {
        $this->npm = $npm;

        return $this;
    }

    /**
     * Get the value of description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of issueDate
     */
    public function getIssueDate()
    {
        return $this->issueDate;
    }

    /**
     * Set the value of issueDate
     *
     * @return  self
     */
    public function setIssueDate($issueDate)
    {
        $this->issueDate = $issueDate;

        return $this;
    }

    /**
     * Get the value of manufacturer
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    /**
     * Set the value of manufacturer
     *
     * @return  self
     */
    public function setManufacturer($manufacturer)
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
