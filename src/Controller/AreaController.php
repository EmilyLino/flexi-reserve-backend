<?php

namespace App\Controller;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Area;
use App\Entity\Reservation;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\AreaRepository;
use Doctrine\ORM\EntityManagerInterface;

class AreaController extends AbstractController
{

    /**
     * @Route("/api/area", methods={"GET"}, name="app_area")
     */
    public function list(Request $request,ManagerRegistry $doctrine): JsonResponse
    {   
        try{
            $repository     = $doctrine->getRepository(Area::class);
            $status         = $request->query->get('status');
            $statusToSearch = !empty($status)? $status : ['DELETED', 'ACTIVE'];
            $areas          = $repository->findBy(['status' => $statusToSearch]);
            
            $data = [];
            foreach ($areas as $key => $value) {
                $data[] = [
                    "id_area" => $value->getId(),
                    "area_name" => $value->getAreaName(),
                    "area_description" => $value->getAreaDescription(),
                    "creation_user" => $value->getCreationUser(),
                    "status" => $value->getStatus()
                ];
            }

            return new JsonResponse([
                "message"=> "Se ha procesado correctamente",
                "data"=> $data,
                "status"=>200
            ], 200);

        }catch(\Exception $e){
            return new JsonResponse(["message"=>$e->getMessage()], 500);
        }
    }

    /**
     * @Route("/api/area", methods={"POST"})
     */
    public function save(Request $request,AreaRepository $areaRepository): JsonResponse{
        try{
            $input = json_decode($request->getContent(), true);
            $areaName = $input["area_name"];
            
            $existAreaName = $areaRepository->findOneBy(["area_name" => $areaName]);

            if(isset($existAreaName))
            {
                throw new \Exception("Ya existe el area");
            }

            $area = new Area();

            $area->setAreaName($input["area_name"]);
            $area->setAreaDescription($input["area_description"]);
            $area->setCreationDate(new \DateTime('now'));
            $area->setStatus("ACTIVE");

            $areaRepository->add($area, true);
            return new JsonResponse(["message"=> "Se ha procesado correctamente"], 201);
        }catch(\Exception $e){
            return new JsonResponse(["message"=>$e->getMessage()], 500);
        }
    }

    /**
     * @Route("/api/area/delete/{id}", methods={"POST"})
     */
    public function delete(int $id,EntityManagerInterface $entityManager): JsonResponse{
        try{
            $areaRepository = $entityManager->getRepository(Area::class);
            $rvRepository = $entityManager->getRepository(Reservation::class);
            $area = $areaRepository->findOneBy(["id"=> $id]);
            $existReservation = $rvRepository->findOneBy([
                'area_id'=>$id,
                'status'=> 'ACTIVE'
            ]);
            
            if(isset($existReservation))
            {
                throw new \Exception("Existen reservaciones activas en esta area.");
            }

            $area->setStatus("DELETED");

            $entityManager->persist($area);
            $entityManager->flush();
            return new JsonResponse(["message"=> "Se ha procesado correctamente"], 201);
        }catch(Exception $e){
            return new JsonResponse(["message"=>$e->getMessage()], 500);
        }
    }

    /**
     * @Route("/api/area/edit/{id}", methods={"POST"})
     */
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): JsonResponse{
        try{
            $input = json_decode($request->getContent(), true);
            $areaRepository = $entityManager->getRepository(Area::class);
            $area = $areaRepository->findOneBy(["id"=> $id]);

            $existAreaName = $areaRepository->findOneByNameExceptId($input["area_name"], $id);

            if(isset($existAreaName))
            {
                throw new \Exception("Ya existe el area");
            }

            $area->setAreaName($input["area_name"]);
            $area->setAreaDescription($input["area_description"]);

            $entityManager->persist($area);
            $entityManager->flush();
            return new JsonResponse(["message"=> "Se ha procesado correctamente"], 201);
        }catch(\Exception $e){
            return new JsonResponse(["message"=>$e->getMessage()], 500);
        }
    }
}
