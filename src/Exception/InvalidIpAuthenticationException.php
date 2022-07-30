<?php

declare(strict_types=1);

/*
 * This file is part of the ContaoIpLoginBundle.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoIpLoginBundle\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class InvalidIpAuthenticationException extends AuthenticationException
{
}
