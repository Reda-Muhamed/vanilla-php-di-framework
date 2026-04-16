<?php

declare(strict_types=1);


namespace Repositories;

use Contracts\AppointmentRepositoryInterface;
use PDO;
use Domain\Appointment;

class AppointmentRepository implements AppointmentRepositoryInterface
{
    public function __construct(private PDO $db) {}


    public function getByPatientId(int $patientId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM appointments WHERE patient_id = :patient_id ORDER BY appointment_date DESC");
        $stmt->execute(['patient_id' => $patientId]);

        $rows = $stmt->fetchAll();
        $appointments = [];

        foreach ($rows as $row) {
            $appointments[] = new Appointment(
                id: $row['id'],
                patientId: $row['patient_id'],
                appointmentDate: $row['appointment_date'],
                status: $row['status'],
                notes: $row['notes'],
                createdAt: $row['created_at']
            );
        }

        return $appointments;
    }

    public function create(Appointment $appointment): bool
    {
        $stmt = $this->db->prepare("INSERT INTO appointments (patient_id, appointment_date, status, notes) VALUES (:patient_id, :appointment_date, :status, :notes)");

        return $stmt->execute([
            'patient_id' => $appointment->patientId,
            'appointment_date' => $appointment->appointmentDate,
            'status' => $appointment->status,
            'notes' => $appointment->notes
        ]);
    }
}
