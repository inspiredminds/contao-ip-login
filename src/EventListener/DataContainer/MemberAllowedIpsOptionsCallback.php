<?php

declare(strict_types=1);

/*
 * This file is part of the Contao IP Login extension.
 *
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoIpLoginBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;

#[AsCallback('tl_member', 'fields.allowed_ips.options')]
class MemberAllowedIpsOptionsCallback
{
    public function __construct(private readonly array $allowedIps)
    {
    }

    public function __invoke(): array
    {
        return $this->allowedIps;
    }
}
