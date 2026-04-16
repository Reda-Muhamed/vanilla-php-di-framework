<?php
declare(strict_types=1);
namespace Domain;
enum UserRole: string {
    case PATIENT = 'patient';
    case ADMIN = 'admin';
}
