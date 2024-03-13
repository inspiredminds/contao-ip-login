<?php

declare(strict_types=1);

/*
 * This file is part of the Contao IP Login extension.
 *
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoIpLoginBundle\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class InvalidIpAuthenticationException extends AuthenticationException
{
}
