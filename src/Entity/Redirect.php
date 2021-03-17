<?php

namespace App\Entity;

use App\Repository\RedirectRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RedirectRepository::class)
 */
class Redirect
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2000)
     */
    private $enlarged_link;

    /**
     * @ORM\Column(type="string", length=2000)
     */
    private $redirect_url;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEnlargedLink(): ?string
    {
        return $this->enlarged_link;
    }

    public function setEnlargedLink(string $enlarged_link): self
    {
        $this->enlarged_link = $enlarged_link;

        return $this;
    }

    public function getRedirectUrl(): ?string
    {
        return $this->redirect_url;
    }

    public function setRedirectUrl(string $redirect_url): self
    {
        $this->redirect_url = $redirect_url;

        return $this;
    }
}
