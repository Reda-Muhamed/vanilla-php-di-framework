<?php
declare(strict_types=1);
namespace Domain;
enum AppointmentStatus: string {
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';
}