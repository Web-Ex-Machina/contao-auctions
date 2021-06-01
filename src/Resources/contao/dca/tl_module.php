<?php

declare(strict_types=1);

/**
 * ALTRAD modules for Contao Open Source CMS
 * Copyright (c) 2019-2020 Web ex Machina
 *
 * @category ContaoBundle
 * @package  Web-Ex-Machina/altrad-v3-module
 * @author   Web ex Machina <contact@webexmachina.fr>
 * @link     https://github.com/Web-Ex-Machina/altrad-v3-module/
 */

$GLOBALS['TL_DCA']['tl_module']['palettes']['wem-auctions_display-auction'] = '{title_legend},name,type;{config_legend},wem_auction,wem_auction_createUserNotification';

$GLOBALS['TL_DCA']['tl_module']['fields']['wem_auction'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['wem_auction'],
    'exclude' => true,
    'inputType' => 'select',
    'options_callback' => [WEM\AuctionsBundle\DataContainer\ModuleContainer::class, 'getAuctions'],
    'eval' => ['includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'w50'],
    'sql' => "varchar(32) NOT NULL default ''",
];
$GLOBALS['TL_DCA']['tl_module']['fields']['wem_auction_createUserNotification'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_module']['wem_auction_createUserNotification'],
    'exclude' => true,
    'inputType' => 'select',
    'options_callback' => [WEM\AuctionsBundle\DataContainer\ModuleContainer::class, 'getCreateUserNotifications'],
    'eval' => ['includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'clr'],
    'sql' => "int(10) unsigned NOT NULL default '0'",
];