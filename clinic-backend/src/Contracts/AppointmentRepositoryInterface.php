<?php

declare(strict_types=1);

namespace Contracts;

use Domain\Appointment;

interface AppointmentRepositoryInterface
{
    public function getByPatientId(int $patientId): array;


    public function create(Appointment $appointment): bool;
}
