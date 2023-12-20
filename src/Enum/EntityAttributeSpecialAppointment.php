<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser\Enum;

enum EntityAttributeSpecialAppointment: string
{
    case None = 'none';
    case CreatedAt = 'created_at';
    case Email = 'email';
    case Enum = 'enum';
    case ExternalId = 'external_id';
    case Identification = 'identification';
    case Measurement = 'measurement';
    case PasswordHash = 'password_hash';
    case Phone = 'phone';
    case UpdatedAt = 'updated_at';
    case Url = 'url';
}
