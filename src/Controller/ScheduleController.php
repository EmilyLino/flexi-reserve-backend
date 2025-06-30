<?php

namespace App\Controller;

use App\Entity\Reservation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Schedule;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ScheduleRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class ScheduleController extends AbstractController
{
    /**
     * @Route("/api/schedule", methods={"GET"}, name="app_schedule")
     */
    public function list(Request $request,ManagerRegistry $doctrine): JsonResponse
    {
        try{
            $repository = $doctrine->getRepository(Schedule::class);
            $status     = $request->query->get('status');
            $statusToSearch = !empty($status)? $status : ['DELETED', 'ACTIVE'];
            $schedule   = $repository->findBy(['status' => $statusToSearch]);
            
            $data = [];
            foreach ($schedule as $key => $value) {
                $data[] = [
                    "id_schedule" => $value->getId(),
                    "hour_ini" => date_format($value->getHourIni(), 'H:i'),
                    "hour_fin" => date_format($value->getHourFin(), 'H:i'),
                    "status"=> $value->getStatus(),
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
     * @Route("/api/schedule", methods={"POST"})
     */
    public function save(Request $request,ScheduleRepository $scheduleRepository): JsonResponse{
        try{
            $input = json_decode($request->getContent(), true);
            $hourIni = $input["hour_ini"];

            $hourIni = DateTime::createFromFormat('H:i', $input['hour_ini']);
            $hourFin = DateTime::createFromFormat('H:i', $input['hour_fin']);

            if (!$hourIni || !$hourFin) {
                throw new \Exception('Invalid time format (expected HH:MM).');
            }

            $existSchedule = $scheduleRepository->findOneBy([
                                "hour_ini" => $hourIni,
                                "hour_fin" => $hourFin,
                                "status" => "ACTIVE"]);

            if(isset($existSchedule))
            {
                throw new \Exception("Ya existe el horario seleccionado");
            }

            $schedule = new Schedule();
            $schedule->setHourIni($hourIni);
            $schedule->setHourFin($hourFin);
            $schedule->setCreationDate(new \DateTime('now'));
            $schedule->setStatus("ACTIVE");

            $scheduleRepository->add($schedule, true);
            return new JsonResponse(["message"=> "Se ha procesado correctamente"], 201);
        }catch(\Exception $e){
            return new JsonResponse(["message"=>$e->getMessage()], 500);
        }
    }

    /**
     * @Route("/api/schedule/delete/{id}", methods={"POST"})
     */
    public function delete(int $id,EntityManagerInterface $entityManager): JsonResponse{
        try{
            $scheduleRepository = $entityManager->getRepository(Schedule::class);
            $rvRepository = $entityManager->getRepository(Reservation::class);
            $schedule = $scheduleRepository->findOneBy(["id"=> $id]);

            $existReservation = $rvRepository->findOneBy([
                'schedule_id'=>$id,
                'status'=> 'ACTIVE'
            ]);
            
            if(isset($existReservation))
            {
                throw new \Exception("Existen reservaciones activas en este horario.");
            }

            $schedule->setStatus("DELETED");

            $entityManager->persist($schedule);
            $entityManager->flush();
            return new JsonResponse(["message"=> "Se ha procesado correctamente"], 201);
        }catch(\Exception $e){
            return new JsonResponse(["message"=>$e->getMessage()], 500);
        }
    }

    /**
     * @Route("/api/schedule/edit/{id}", methods={"POST"})
     */
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): JsonResponse{
        try{
            $input = json_decode($request->getContent(), true);
            $scheduleRepository = $entityManager->getRepository(Schedule::class);
            $rvRepository = $entityManager->getRepository(Reservation::class);
            $schedule = $scheduleRepository->findOneBy(["id"=> $id]);

            $hourIni = DateTime::createFromFormat('H:i', $input['hour_ini']);
            $hourFin = DateTime::createFromFormat('H:i', $input['hour_fin']);

            $existReservation = $rvRepository->findOneBy([
                'schedule_id'=>$id,
                'status'=> 'ACTIVE'
            ]);

            if(isset($existReservation))
            {
                throw new \Exception("Existen reservaciones activas en este horario.");
            }


            $existSchedule = $scheduleRepository->findOneByHoursExceptId($hourIni, $hourFin, $id);

            if(isset($existSchedule))
            {
                throw new \Exception("Ya existe el horario ingresado");
            }

            $schedule->setHourIni($hourIni);
            $schedule->setHourFin($hourFin);

            $entityManager->persist($schedule);
            $entityManager->flush();
            return new JsonResponse(["message"=> "Se ha procesado correctamente"], 201);
        }catch(\Exception $e){
            return new JsonResponse(["message"=>$e->getMessage()], 500);
        }
    }
}
