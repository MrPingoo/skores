<?php

namespace App\Controller;

use App\Entity\Sport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Doctrine\DBAL\Driver\Connection;

/**
 * @Route("/api/sport")
 */
class SportController extends AbstractController
{
    /**
     * @Route("/", name="sport_index", methods={"GET"})
     */
    public function index(Connection $connection): Response
    {
        $queryBuilder = $connection->createQueryBuilder();
        $data = $queryBuilder->select('*')->from('sport')->execute()->fetchAll();

        $serializer = $this->get("serializer");

        return new Response($serializer->serialize($data, "json"));
    }

    /**
     * @Route("/new", name="sport_new", methods={"POST"})
     */
    public function new(Request $request, Connection $connection): Response
    {
        $label = $request->get('label');
        $queryBuilder = $connection->createQueryBuilder();
        $data = $queryBuilder
            ->insert('sport')
            ->values(
                array(
                    'label' => '?',
                    'date' => 'NOW()'
                )
            )
            ->setParameter(0, $label)
            ->execute()
        ;

        $serializer = $this->get("serializer");

        return new Response($serializer->serialize($data, "json"));
    }

    /**
     * @Route("/{id}", name="sport_show", methods={"GET"})
     */
    public function show(Request $request, Connection $connection): Response
    {
        $id = $request->get('id');
        $queryBuilder = $connection->createQueryBuilder();
        $data = $queryBuilder->select('*')->from('sport')->where('id = ?')->setParameter(0, $id)->setMaxResults(1)->execute()->fetchAll();

        $serializer = $this->get("serializer");

        return new Response($serializer->serialize($data, "json"));
    }

    /**
     * @Route("/{id}/edit", name="sport_edit", methods={"PUT"})
     */
    public function edit(Request $request, Connection $connection): Response
    {
        $id = $request->get('id');
        $label = $request->get('label');

        $queryBuilder = $connection->createQueryBuilder();
        $data = $queryBuilder
            ->update('sport')
            ->set('label', '?')
            ->where('id = ?')
            ->setParameter(0, $label)
            ->setParameter(1, $id)
            ->execute()
        ;

        $serializer = $this->get("serializer");

        return new Response($serializer->serialize($data, "json"));
    }

    /**
     * @Route("/{id}", name="sport_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Connection $connection): Response
    {
        $id = $request->get('id');
        $queryBuilder = $connection->createQueryBuilder();
        $data = $queryBuilder
            ->delete('sport')
            ->where('id = ?')
            ->setParameter(0, $id)
            ->execute()
        ;

        $serializer = $this->get("serializer");

        return new Response($serializer->serialize($data, "json"));
    }
}
