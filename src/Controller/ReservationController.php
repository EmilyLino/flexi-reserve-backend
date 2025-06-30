<?php

namespace App\Controller;

use App\Entity\Area;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Reservation;
use App\Entity\Schedule;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ReservationRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class ReservationController extends AbstractController
{
    /**
     * @Route("/api/reservation", methods={"GET"},name="app_reservation")
     */
    public function list(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        try{
            $rvRepository = $entityManager->getRepository(Reservation::class);
            $user_id     = $request->query->get('user_name');
            $areaId       = $request->query->getInt('area_id');

            $reservations = $rvRepository->findByFilters($user_id, $areaId);
            
            $data = [];
            foreach ($reservations as $key => $value) {
                $data[] = [
                    "id_reservation" => $value->getId(),
                    "event_name" => $value->getEventName(),
                    "event_description" => $value->getEventDescription(),
                    "status" => $value->getStatus(),
                    "date" => date_format($value->getdate(), 'd/m/y'),
                    "schedule" => array(
                                "id_schedule" => $value->getScheduleId()->getId(),
                                "hour_ini" => date_format($value->getScheduleId()->getHourIni(), 'H:i'),
                                "hour_fin" => date_format($value->getScheduleId()->getHourFin(), 'H:i')
                    ),
                    "area" => array(
                                "area_name" => $value->getAreaId()->getAreaName()
                    )
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
     * @Route("/api/reservation", methods={"POST"})
     */
    public function save(Request $request, EntityManagerInterface $entityManager): JsonResponse{
        try{
            $rvRepository = $entityManager->getRepository(Reservation::class);
            $areaRepository = $entityManager->getRepository(Area::class);
            $scheduleRepository = $entityManager->getRepository(Schedule::class);
            $userRepository = $entityManager->getRepository(User::class);

            $input    = json_decode($request->getContent(), true);
            $date     = DateTime::createFromFormat('d/m/Y', $input['date']);
            $area     = $areaRepository->find($input["area_id"]);
            $schedule = $scheduleRepository->find($input["schedule_id"]);
            $user     = $userRepository->findOneBy(['user_name' => $input["user_name"]]);

            $existReservation = $rvRepository->existReservation(
                                                $area->getId(), 
                                                $date,
                                                $schedule->getHourIni(),
                                                $schedule->getHourFin()
                                );
            if($existReservation > 0)
            {
                throw new \Exception("El horario solicitado ya está reservado.");
            }

            $reservation = new Reservation();
            $reservation->setEventName($input["event_name"]);
            $reservation->setEventDescription($input["event_description"]);
            $reservation->setDate($date);
            $reservation->setStatus("ACTIVE");
            $reservation->setAreaId($area);
            $reservation->setScheduleId($schedule);
            $reservation->setUserId($user);

            $rvRepository->add($reservation, true);
            return new JsonResponse(["message"=> "Se ha procesado correctamente"], 201);
        }catch(\Exception $e){
            return new JsonResponse(["message"=>$e->getMessage()], 500);
        }
    }

    /**
     * @Route("/api/reservation/delete/{id}", methods={"POST"})
     */
    public function delete(int $id,EntityManagerInterface $entityManager): JsonResponse{
        try{
            $rvRepository = $entityManager->getRepository(Reservation::class);
            $reservation = $rvRepository->findOneBy(["id"=> $id]);
            $reservation->setStatus("DELETED");

            $entityManager->persist($reservation);
            $entityManager->flush();
            return new JsonResponse(["message"=> "Se ha procesado correctamente"], 201);
        }catch(\Exception $e){
            return new JsonResponse(["message"=>$e->getMessage()], 500);
        }
    }

}
