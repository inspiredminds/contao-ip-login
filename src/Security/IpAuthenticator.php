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
use InspiredMinds\ContaoIpLoginBundle\Exception\InvalidIpAuthenticationException;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class IpAuthenticator extends AbstractAuthenticator
{
    /**
     * @var Security
     */
    private $security;

    /**
     * @var ContaoFramework
     */
    private $framework;

    /**
     * @var array<string>
     */
    private $allowedIps;

    /**
     * @var array<string>
     */
    private $ignoredPaths;

    /**
     * @var string|null
     */
    private $requestCondition;

    public function __construct(Security $security, ContaoFramework $framework, array $allowedIps, array $ignoredPaths, string|null $requestCondition)
    {
        $this->security = $security;
        $this->framework = $framework;
        $this->allowedIps = $allowedIps;
        $this->ignoredPaths = $ignoredPaths;
        $this->requestCondition = $requestCondition;
    }

    public function supports(Request $request): bool
    {
        // Check for any ignored paths
        foreach ($this->ignoredPaths as $ignoredPath) {
            if (preg_match('@'.$ignoredPath.'@', $request->getPathInfo())) {
                return false;
            }
        }

        // Check for request condition
        if (null !== $this->requestCondition) {
            if (!(new ExpressionLanguage())->evaluate($this->requestCondition, ['request' => $request])) {
                return false;
            }
        }

        // If there already is a user logged in, don't to anything
        if ($this->security->getUser()) {
            return false;
        }

        // Support only allowed IPs
        return IpUtils::checkIp($request->getClientIp(), $this->allowedIps);
    }

    public function authenticate(Request $request): Passport
    {
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

                if (IpUtils::checkIp($request->getClientIp(), $memberIps)) {
                    return new SelfValidatingPassport(new UserBadge($member->username));
                }
            }
        }

        throw new InvalidIpAuthenticationException('Invalid IP for IP login.');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): Response|null
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response|null
    {
        return null;
    }
}
