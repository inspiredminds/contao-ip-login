<?php

declare(strict_types=1);

/*
 * This file is part of the ContaoIpLoginBundle.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_member']['fields']['allow_ip_login'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50 clr'],
    'sql' => ['type' => 'boolean', 'default' => false],
];

$GLOBALS['TL_DCA']['tl_member']['fields']['allowed_ips'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['multiple' => true, 'tl_class' => 'clr'],
    'sql' => ['type' => 'blob', 'notnull' => false],
];

PaletteManipulator::create()
    ->addField('allow_ip_login', 'login_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('allowed_ips', 'login_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToSubpalette('login', 'tl_member')
;
