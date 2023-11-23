<?php

namespace App\Controller;

use App\Entity\DTO\Collection\DateTimeCustomCollectionDTO;
use App\Service\Closer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class DateHandlingController extends AbstractController
{
    public function __construct(private Closer $closer)
    {
        
    }
    #[Route('/make/suggestions', name: 'app_suggestion_maker',methods:["POST"])]
    public function index(
        #[MapRequestPayload()] DateTimeCustomCollectionDTO $dateTimeCollection
    ): Response
    {
        $dateTimeCollection->sortOlderToYounger();
        // $suggestions = $this->closer->groupDateTimeArray();
        return $this->json([]);
    }
}
