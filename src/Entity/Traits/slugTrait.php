<?php
namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait slugTrait
{
    #[ORM\Column]
    private ?string $slug = null;
    public function getslug(): ?string
    {
        return $this->slug;
    }

    public function setslug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}