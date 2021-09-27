<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Token types constants
 */
final class TokenTypes extends Enum
{
    const EMAIL_VERIFY = 'email-verify';
    const PASSWORD_RESET = 'password-reset';
    const PASSWORD_RECOVER = 'password-recover';
}
