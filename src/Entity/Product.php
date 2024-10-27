<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Manufacturer;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource(
    normalizationContext: ['groups' => 'product.read'],
    denormalizationContext: ['groups' => 'product.write']
)]
#[
    ApiFilter(
    SearchFilter::class,
    properties: [
        'name' => SearchFilter::STRATEGY_PARTIAL,
        'description' => SearchFilter::STRATEGY_PARTIAL,
        'manufacturer.countryCode' => SearchFilter::STRATEGY_EXACT,
    ]
),
    ApiFilter(
    OrderFilter::class,
    properties: [
        'issueDate'
    ]
)
]
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
    #[
        Assert\NotBlank,
        Groups(['product.read', 'product.write']),
    ]
    private string $npm = '';

    /**
     * The product name
     */
    #[ORM\Column]
    #[
        Assert\NotBlank,
        Groups(['product.read', 'product.write'])
    ]
    private string $name = '';

    /**
     * The manufacturer description
     */
    #[ORM\Column(type: "text")]
    #[
        Assert\NotBlank,
        Groups(['product.read', 'product.write'])
    ]
    private string $description = '';

    /**
     * The product issue date
     */
    #[ORM\Column(type: "datetime")]
    #[
        Assert\NotNull,
        Groups(['product.read'])
    ]

    private ?\DateTimeInterface $issueDate = null;

    /**
     * The manufacturer of the product
     */
    #[ORM\ManyToOne(targetEntity: Manufacturer::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    #[
        Groups(['product.read'])
    ]
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
