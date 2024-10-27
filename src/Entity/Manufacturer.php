<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The manufacturer class
 */
#[ORM\Entity]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Patch(),
        new Delete()
    ],
    paginationClientItemsPerPage: true

)]
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
    #[Assert\NotBlank]
    #[
        Groups(['product.read'])
    ]
    private string $name = '';

    /**
     * The manufacturer description
     */
    #[ORM\Column(type: "text")]
    #[Assert\NotBlank]
    private string $description = '';

    /**
     * The manufacturer country code
     */
    #[ORM\Column(length: 3)]
    #[Assert\NotBlank]
    private string $countryCode = '';

    /**
     * The manufacturer listed date
     */
    #[Assert\NotNull]
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
    // public function setProducts($products)
    // {
    //     $this->products = $products;

    //     return $this;
    // }
}
