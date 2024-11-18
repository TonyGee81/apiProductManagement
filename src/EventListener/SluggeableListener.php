<?php

namespace App\EventListener;

use App\Entity\Interface\SlugInterface;
use App\Entity\Product;
use App\Service\SlugService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::prePersist)]
#[AsDoctrineListener(event: Events::preUpdate)]
class SluggeableListener
{
    private $slugService;

    public function __construct(SlugService $slugService)
    {
        $this->slugService = $slugService;
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof SlugInterface) {
            return;
        }

        $name = $entity->getName();

        if ($entity instanceof Product) {
            $name .= '-'.$entity->getDescription();
        }

        $entity->setSlug($this->slugService->slugify($name));
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof SlugInterface) {
            return;
        }

        $name = $entity->getName();

        if ($entity instanceof Product) {
            $name .= '-'.$entity->getDescription();
        }

        $entity->setSlug($this->slugService->slugify($name));
    }
}
