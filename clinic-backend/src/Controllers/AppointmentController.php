<?php

declare(strict_types=1);

namespace Controllers;

use Repositories\AppointmentRepository;
use Domain\Appointment;
use HTTP\Response;
use Repositories\UserRepository;

class AppointmentController
{
    public function __construct(private AppointmentRepository $appointmentRepo, private UserRepository $userRepo) {}

    public function index()
    {
        $user = $_SERVER['AUTHENTICATED_USER'];
        $userId = $this->userRepo->findByEmail($user['email'])->id;

        $appointments = $this->appointmentRepo->getByPatientId($userId);

        Response::json([
            'status' => 'success',
            'data' => $appointments
        ]);
    }


    public function store()
    {
        $user = $_SERVER['AUTHENTICATED_USER'];
        $userId = $this->userRepo->findByEmail($user['email'])->id;

        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['appointment_date'])) {
            Response::json(['error' => 'Appointment date is required'], 400);
            return;
        }

        // Create a new Domain Entity
        $newAppointment = new Appointment(
            id: null,
            patientId: $userId,
            appointmentDate: $data['appointment_date'],
            notes: $data['notes'] ?? null
        );

        // Pass the entity to the repository
        $success = $this->appointmentRepo->create($newAppointment);

        if ($success) {
            Response::json(['status' => 'success', 'message' => 'Appointment booked successfully'], 201);
        } else {
            Response::json(['status' => 'error', 'message' => 'Failed to book appointment'], 500);
        }
    }
}
