<?php

namespace App\Controller;

use App\Entity\Commune;
use App\Entity\Media;
use App\Repository\CommuneRepository;
use App\Repository\MediaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CommuneController extends AbstractController
{

    protected function serializeJson($objet){
        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getNom();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([$normalizer], [$encoder]);
        $jsonContent = $serializer->serialize($objet, 'json');
        return $jsonContent;
    }
    /**
     * @Route("/commune", name="commune")
     * @param CommuneRepository $communeRepository
     * @param Request $request
     * @return JsonResponse
     */
    public function communes(CommuneRepository $communeRepository, Request $request)
    {
        $filter = [];
        $em = $this->getDoctrine()->getManager();
        $metadata = $em->getClassMetadata(Commune::class)->getFieldNames();
        foreach ($metadata as $value){
            if ($request->query->get($value)){
                $filter[$value] = $request->query->get($value);
            }
        }
        return JsonResponse::fromJsonString($this->serializeJson($communeRepository->findBy($filter)), Response::HTTP_OK);
    }

    /**
     * @Route("api/commune/create", name="commune_create", methods={"POST"})
     * @param Request $request
     * @param CommuneRepository $communeRepository
     * @return JsonResponse
     */
    public function communeCreate(Request $request, CommuneRepository $communeRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $commune = new Commune();
        $data = json_decode($request->request->getContent(),true);
        $commune->setNom($data['nom'])
            ->setCode($data['code'])
            ->setCodeDepartement('codeDepartement')
            ->setCodeRegion('codeRegion')
            ->setPopulation('population')
            ->setCodesPostaux(['codesPostaux']);
        if ($data['media']) {
            $arrayMedia = $data['media'];
            for ($i = 0;$i < count($arrayMedia);$i++){
                $dataMedia = $arrayMedia[$i];
                $media = new Media();
                $media->setCommune($commune)
                    ->setUrl($dataMedia['url']);
                $entityManager->persist($media);
            }
        }
        $entityManager->persist($commune);
        $entityManager->flush();
        return JsonResponse::fromJsonString($this->serializeJson($commune), Response::HTTP_OK);
    }

    /**
     * @Route("api/commune/update", name="commune_update", methods={"PUT"})
     * @param Request $request
     * @return JsonResponse
     */
    public function communeUpdate(Request $request, CommuneRepository $communeRepository, MediaRepository $mediaRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $data = json_decode($request->request->getContent(),true);
        $commune = $communeRepository->findOneBy(['id' => $data['id']]);

         if (isset($data['nom'])) {
             $commune->setNom($data['nom']);
         }
         if (isset($data['code'])){
             $commune->setCode($data['code']);
         }
         if (isset($data['codeDepartement'])){
             $commune->setCodeDepartement('codeDepartement');
         }
         if (isset($data['codeRegion'])){
             $commune->setCodeRegion('codeRegion');
         }
        if (isset($data['population'])){
            $commune->setPopulation('population');
        }
        if (isset($data['codesPostaux'])){
            $commune->setCodeRegion('codeRegion');
        }
        if (isset($data['codesPostaux'])) {
            $commune->setCodesPostaux(['codesPostaux']);
        }


        if ($data['media']) {
            for ($i =0; $i < count($data['media']); $i++){
                $dataMedia = $data['media'[$i]];
                $media = $mediaRepository->findOneBy(['id' => data['media'] ]);
                $media->setVideo($dataMedia['url']);
                $media->setImage($dataMedia['url']);
                $media->setArticle($dataMedia['url']);
                $entityManager->persist($media);

            }


        }
        $entityManager->persist($commune);
        $entityManager->flush();
        return JsonResponse::fromJsonString($this->serializeJson($commune), Response::HTTP_OK);
    }
    /**
     * @Route("/api/commune/delete", name="communeDelete", methods={"DELETE"})
     * @param Request $request
     * @param CommuneRepository $communeRepository
     * @return JsonResponse
     */
    public function communeDelete(Request $request, CommuneRepository $communeRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $item = json_decode($request->getContent(),true);
        $commune = $communeRepository->find($item['id']);
        if ($commune){
            $em->remove($commune);
            $em->flush();
            return new JsonResponse("Commune deleted", Response::HTTP_OK);
        }else {
            return new JsonResponse("Bad request", Response::HTTP_BAD_REQUEST);
        }

    }
}
