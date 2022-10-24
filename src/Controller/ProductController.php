<?php

namespace App\Controller;

use App\DTO\LowestPriceEnquiry;
use App\Filter\LowestPriceFilter;
use App\Filter\PromotionsFilterInterface;
use App\Service\Serializer\DTOSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ProductController extends AbstractController
{
    #[Route('/products/{id}/lowest-price', name: 'lowest-price', methods: 'POST')]
    public function lowestPrice(
        Request $request,
        int $id,
        DTOSerializer $serializer,
        LowestPriceFilter $promotionsFilter
    ): Response
    {
        if ($request->headers->has('force_fail')) {

            return new JsonResponse(
                ['error' => 'Promotions Engine failure message'],
                $request->headers->get('force_fail')
            );
        }

        $lowestPriceEnquiry = $serializer->deserialize($request->getContent(), LowestPriceEnquiry::class, 'json');

        $modifiedEnquiry = $promotionsFilter->apply($lowestPriceEnquiry);

        $responseContent = $serializer->serialize($modifiedEnquiry, 'json');

        return new Response($responseContent, 200);
    }







    #[Route('/product', name: 'app_product')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ProductController.php',
        ]);
    }
}
