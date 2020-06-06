<?php

declare(strict_types=1);

/*
 * This file is part of the ContaoIpLoginBundle.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoIpLoginBundle\Security;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Date;
use Contao\MemberModel;
use Contao\StringUtil;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class IpAuthenticator extends AbstractGuardAuthenticator
{
    private $security;
    private $framework;
    private $allowedIps;

    public function __construct(Security $security, ContaoFramework $framework, array $allowedIps)
    {
        $this->security = $security;
        $this->framework = $framework;
        $this->allowedIps = $allowedIps;
    }

    public function supports(Request $request): bool
    {
        // If there already is a user logged in, don't to anything
        if ($this->security->getUser()) {
            return false;
        }

        // Support only allowed IPs
        return IpUtils::checkIp($request->getClientIp(), $this->allowedIps);
    }

    public function getCredentials(Request $request): ?string
    {
        return $request->getClientIp();
    }

    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        if (null === $credentials) {
            return null;
        }

        $this->framework->initialize();

        /** @var MemberModel $memberAdapter */
        $memberAdapter = $this->framework->getAdapter(MemberModel::class);
        /** @var Date $dateAdapter */
        $dateAdapter = $this->framework->getAdapter(Date::class);
        /** @var StringUtil $stringUtil */
        $stringUtil = $this->framework->getAdapter(StringUtil::class);

        $t = $memberAdapter->getTable();
        $time = $dateAdapter->floorToMinute();
        $where = ["$t.login = '1' AND $t.allow_ip_login = 1 AND ($t.start = '' OR $t.start <= '$time') AND ($t.stop = '' OR $t.stop > '".($time + 60)."') AND $t.disable = ''"];

        if (null !== ($members = $memberAdapter->findBy($where, []))) {
            foreach ($members as $member) {
                $memberIps = $stringUtil->deserialize($member->allowed_ips, true);

                if (IpUtils::checkIp($credentials, $memberIps)) {
                    return $userProvider->loadUserByUsername($member->username);
                }
            }
        }

        return null;
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return null;
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }

    public function start(Request $request, AuthenticationException $authException = null): ?Response
    {
        return null;
    }
}
