<?php

namespace App\Controller;

use App\Entity\Scene;
use App\Entity\User;
use App\Repository\SceneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ScenesController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var SceneRepository
     */
    private $sceneRepository;

    /**
     * ScenesController constructor.
     *
     * @param EntityManagerInterface $manager
     * @param SceneRepository $sceneRepository
     */
    public function __construct(EntityManagerInterface $manager, SceneRepository $sceneRepository)
    {
        $this->manager = $manager;
        $this->sceneRepository = $sceneRepository;
    }

    /**
     * @return User
     */
    protected function getUser(): User
    {
        $user = parent::getUser();
        if ($user instanceof User) {
            return $user;
        }
        throw $this->createAccessDeniedException();
    }


    private function sceneRef(?Scene $location): ?array
    {
        return $location
            ? [
                'id'               => $location->getId(),
                'type'             => $location->getType(),
                'label'            => $location->getLabel(),
                'shortDescription' => $location->getShortDescription(),
                'attributes'       => $location->getAttributes()
            ]
            : null;
    }

    private function sceneExt(Scene $location): ?array
    {
        $children = $this->manager
            ->createQuery("SELECT s FROM App:Scene s WHERE s.parent=:id AND s.prev IS NULL")
            ->execute(['id' => $location->getId()]);

        return [
            'id'               => $location->getId(),
            'type'             => $location->getType(),
            'label'            => $location->getLabel(),
            'editable'         => $this->isGranted('edit', $location),
            'description'      => $location->getDescription(),
            'shortDescription' => $location->getShortDescription(),
            'attributes'       => $location->getAttributes(),
            'parent'           => $this->sceneRef($location->getParent()),
            'prev'             => $this->sceneRef($location->getPrev()),
            'next'             => $this->sceneRef($location->getNext()),
            'children'         => array_map([$this, 'sceneRef'], $children)
        ];
    }

    /**
     * @Route("/scene/{sceneId}", name="getScene", methods={"GET"})
     * @param int $sceneId
     *
     * @return JsonResponse
     */
    public function getScene(int $sceneId = 1)
    {
        try {
            $scene = $this->manager
                ->createQuery("
                    SELECT sc, pa, pr, ne 
                    FROM App:Scene sc 
                    LEFT JOIN sc.parent pa 
                    LEFT JOIN sc.prev pr 
                    LEFT JOIN sc.next ne 
                    WHERE sc.id=:id")
                ->setMaxResults(1)
                ->setParameter('id', $sceneId)
                ->getSingleResult();
        } catch (NoResultException|NonUniqueResultException $e) {
            throw $this->createNotFoundException();
        }
        $this->denyAccessUnlessGranted('view', $scene);

        return $this->json($this->sceneExt($scene));
    }

    /**
     * @Route("/scene", name="putScene", methods={"PUT"})
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function putScene(Request $request)
    {
        $params = $request->request;
        $scene = $this->sceneRepository->find($params->getInt('id'));
        if (!$scene) {
            throw $this->createNotFoundException();
        }
        $this->denyAccessUnlessGranted('edit', $scene);
        $scene->setLabel($params->get('label'))
            ->setShortDescription($params->get('shortDescription'))
            ->setDescription($params->get('description'))
            ->setType($params->getAlnum('type'))
            ->setAttributes($params->get('attributes'));

        $this->manager->flush();

        return $this->getScene($scene->getId());
    }

    private function maybeFetchRelated(Request $request, string $field): ?Scene
    {
        $sceneId = $request->request->get($field)['id'] ?? null;

        return $sceneId ? $this->sceneRepository->find($sceneId) : null;
    }

    /**
     * @Route("/scene", name="postScene", methods={"POST"})
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function postScene(Request $request)
    {
        $params = $request->request;
        $scene = new Scene();
        $scene->setLabel($params->get('label'))
            ->setShortDescription($params->get('shortDescription'))
            ->setDescription($params->get('description'))
            ->setType($params->getAlnum('type'))
            ->setAttributes($params->get('attributes'))
            ->setParent($this->maybeFetchRelated($request, 'parent'))
            ->setOwner($this->getUser());

        $prev = $this->maybeFetchRelated($request, 'prev');
        if ($prev) {
            $next = $prev->getNext();
            if ($next) {
                $scene->setNext($next);
                $next->setPrev($scene);
            }
            $prev->setNext($scene);
            $scene->setPrev($prev);
        }

        $this->denyAccessUnlessGranted('create', $scene);
        $this->manager->persist($scene);
        $this->manager->flush();

        return $this->getScene($scene->getId());
    }
}
