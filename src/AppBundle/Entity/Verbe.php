<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Verbe
 *
 * @ORM\Table(name="verbe")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VerbeRepository")
 */
class Verbe
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="baseVerbale", type="string", length=100)
     */
    private $baseVerbale;

    /**
     * @var string
     *
     * @ORM\Column(name="preterit", type="string", length=100)
     */
    private $preterit;

    /**
     * @var string
     *
     * @ORM\Column(name="participePasse", type="string", length=100)
     */
    private $participePasse;

    /**
     * @var string
     *
     * @ORM\Column(name="traduction", type="string", length=100)
     */
    private $traduction;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set baseVerbale
     *
     * @param string $baseVerbale
     *
     * @return Verbe
     */
    public function setBaseVerbale($baseVerbale)
    {
        $this->baseVerbale = $baseVerbale;

        return $this;
    }

    /**
     * Get baseVerbale
     *
     * @return string
     */
    public function getBaseVerbale()
    {
        return $this->baseVerbale;
    }

    /**
     * Set preterit
     *
     * @param string $preterit
     *
     * @return Verbe
     */
    public function setPreterit($preterit)
    {
        $this->preterit = $preterit;

        return $this;
    }

    /**
     * Get preterit
     *
     * @return string
     */
    public function getPreterit()
    {
        return $this->preterit;
    }

    /**
     * Set participePasse
     *
     * @param string $participePasse
     *
     * @return Verbe
     */
    public function setParticipePasse($participePasse)
    {
        $this->participePasse = $participePasse;

        return $this;
    }

    /**
     * Get participePasse
     *
     * @return string
     */
    public function getParticipePasse()
    {
        return $this->participePasse;
    }

    /**
     * Set traduction
     *
     * @param string $traduction
     *
     * @return Verbe
     */
    public function setTraduction($traduction)
    {
        $this->traduction = $traduction;

        return $this;
    }

    /**
     * Get traduction
     *
     * @return string
     */
    public function getTraduction()
    {
        return $this->traduction;
    }
}

