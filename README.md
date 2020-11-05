[![](https://img.shields.io/packagist/v/inspiredminds/contao-ip-login.svg)](https://packagist.org/packages/inspiredminds/contao-ip-login)
[![](https://img.shields.io/packagist/dt/inspiredminds/contao-ip-login.svg)](https://packagist.org/packages/inspiredminds/contao-ip-login)

Contao IP Login
===================

Contao extension to allow front end members to be automatically logged in by IP.
An **Allow IP based auto login** and **Allowed IPs** setting is provided for members 
in the back end. The **Allowed IPs** setting offers a set of IPs or Subnets which are
configured via the bundle configuration:

```yml
# config/config.yml
contao_iplogin:
    # All allowed IPs
    allowed_ips:
        - '239.27.9.125'
        - '245.107.230.190'
        - '46.78.101.0/24'
        - 'c43c:2fa4:3833:b00a:4270:3a4a:3:69e7'
        - '85ca:d480:ef8f:f834:d788:6ce2:d031:4e1'
        - '7d45:d6aa:48fd:e386:1b23:e502:f9db:913b'
    # These paths are ignored from the automatic IP based login
    ignored_paths:
        - '/login$'
        - '/logout$'
    # Additional conditions on the request can be set
    request_condition: "'GET' === request.getMethod() && !request.isXmlHttpRequest()"
```
