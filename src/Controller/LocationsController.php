<?php

namespace App\Controller;

use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LocationsController extends AbstractController
{
    /**
     * @Route("/location/{locationId}", name="locations")
     */
    public function location(EntityManagerInterface $manager, int $locationId = null)
    {
        $location = current($manager
            ->createQuery("SELECT l, p, s, d FROM App:Location l LEFT JOIN l.parent p LEFT JOIN l.prev s LEFT JOIN l.next d WHERE l.id=:id")
            ->setMaxResults(1)
            ->execute(['id' => $locationId], Query::HYDRATE_ARRAY));

        if (!$location) {
            throw $this->createNotFoundException();
        }
        $location['children'] = $manager
            ->createQuery("SELECT l FROM App:Location l WHERE l.parent=:id")
            ->execute(['id' => $locationId], Query::HYDRATE_ARRAY);

        return $this->json($location);
    }
}
