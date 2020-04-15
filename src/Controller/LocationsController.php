<?php

namespace App\Controller;

use App\Entity\Location;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class LocationsController extends AbstractController
{
    private function locationRef(?Location $location): ?array
    {
        if ($location) {
            return [
                'id'          => $location->getId(),
                'type'        => $location->getType(),
                'label'       => $location->getLabel(),
                'description' => $location->getShortDescription(),
                'attributes'  => $location->getAttributes()
            ];
        }

        return null;
    }

    private function locationExt(?Location $location, array $children): ?array
    {
        if ($location) {
            return [
                'id'          => $location->getId(),
                'type'        => $location->getType(),
                'label'       => $location->getLabel(),
                'editable'    => $this->isGranted('edit', $location),
                'description' => $location->getDescription(),
                'attributes'  => $location->getAttributes(),
                'parent'      => $this->locationRef($location->getParent()),
                'prev'        => $this->locationRef($location->getPrev()),
                'next'        => $this->locationRef($location->getNext()),
                'children'    => array_map([$this, 'locationRef'], $children)
            ];
        }

        return null;
    }

    /**
     * @Route("/location/{locationId}", name="locations")
     * @param EntityManagerInterface $manager
     * @param int|null $locationId
     *
     * @return JsonResponse
     */
    public function location(EntityManagerInterface $manager, int $locationId = null)
    {
        $locationEntity = current($manager
            ->createQuery("SELECT l, p, s, d FROM App:Location l LEFT JOIN l.parent p LEFT JOIN l.prev s LEFT JOIN l.next d WHERE l.id=:id")
            ->setMaxResults(1)
            ->execute(['id' => $locationId]));

        if (!$locationEntity instanceof Location) {
            throw $this->createNotFoundException();
        }

        return $this->json($this->locationExt(
            $locationEntity,
            $manager
                ->createQuery("SELECT l FROM App:Location l WHERE l.parent=:id AND l.prev IS NULL")
                ->execute(['id' => $locationId])
        ));
    }
}
