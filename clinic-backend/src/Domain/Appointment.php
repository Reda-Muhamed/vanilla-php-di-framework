<?php

declare(strict_types=1);

namespace Domain;

class Appointment
{
    public function __construct(
        public ?int $id,
        public int $patientId,
        public string $appointmentDate,
        public string $status = 'pending',
        public ?string $notes = null,
        public ?string $createdAt = null
    ) {}

    // Business rule: Can an appointment be cancelled?
    public function canBeCancelled(): bool
    {
        return $this->status !== AppointmentStatus::CANCELLED->value;
    }
}