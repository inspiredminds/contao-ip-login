<?php

declare(strict_types=1);

/*
 * This file is part of the ContaoIpLoginBundle.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoIpLoginBundle\EventListener\DataContainer;

use Contao\CoreBundle\ServiceAnnotation\Callback;
use Terminal42\ServiceAnnotationBundle\ServiceAnnotationInterface;

/**
 * @Callback(table="tl_member", target="fields.allowed_ips.options")
 */
class MemberAllowedIpsOptionsCallback implements ServiceAnnotationInterface
{
    private $allowedIps;

    public function __construct(array $allowedIps)
    {
        $this->allowedIps = $allowedIps;
    }

    public function __invoke(): array
    {
        return $this->allowedIps;
    }
}
