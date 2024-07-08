<?php

namespace App\Listener;

use App\Entity\Articles;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleEntityListener
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Articles $articles, LifecycleEventArgs $event): void
    {
        $articles->computeSlug($this->slugger);
        if ($articles->getStatus() === $articles::STATUS_PUBLISHED && !$articles->getPublishedAt()) {
            $articles->setPublishedAt(new \DateTime());
        } elseif ($articles->getStatus() === $articles::STATUS_DRAFT) {
            $articles->setPublishedAt(null);
        }
    }

    public function preUpdate(Articles $articles, LifecycleEventArgs $event): void
    {
        $articles->computeSlug($this->slugger);
        if ($articles->getStatus() === $articles::STATUS_PUBLISHED && !$articles->getPublishedAt()) {
            $articles->setPublishedAt(new \DateTime());
        } elseif ($articles->getStatus() === $articles::STATUS_DRAFT) {
            $articles->setPublishedAt(null);
        }
    }
}
