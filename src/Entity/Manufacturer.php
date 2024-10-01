<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * The manufacturer class
 */
#[ORM\Entity]
#[ApiResource]
class Manufacturer
{
    /**
     * The manufacturer id
     */
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;

    /**
     * The manufacturer name
     */
    #[ORM\Column]
    private ?string $name = null;

    /**
     * The manufacturer description
     */
    #[ORM\Column(type: "text")]
    private ?string $description = null;

    /**
     * The manufacturer country code
     */
    #[ORM\Column(length: 3)]
    private ?string $countryCode = null;

    /**
     * The manufacturer listed date
     */
    #[ORM\Column(type: "datetime")]

    private ?\DateTimeInterface $listedDate = null;

    /**
     * Avalable products from this manufacturer
     * @var Collection<int, Product>
     */
    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'manufacturer', cascade: ['persist', 'remove'])]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    /**
     * Get the manufacturer name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the manufacturer name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the manufacturer description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the manufacturer description
     *
     * @return  self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the manufacturer country code
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Set the manufacturer country code
     *
     * @return  self
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Get the manufacturer listed date
     */
    public function getListedDate()
    {
        return $this->listedDate;
    }

    /**
     * Set the manufacturer listed date
     *
     * @return  self
     */
    public function setListedDate($listedDate)
    {
        $this->listedDate = $listedDate;

        return $this;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of products
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set the value of products
     *
     * @return  self
     */
    public function setProducts($products)
    {
        $this->products = $products;

        return $this;
    }
}
