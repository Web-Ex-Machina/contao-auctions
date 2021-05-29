<?php

declare(strict_types=1);

/**
 * Auctions for Contao Open Source CMS
 * Copyright (c) 2021-2021 Web ex Machina
 *
 * @category ContaoBundle
 * @package  Web-Ex-Machina/contao-auctions
 * @author   Web ex Machina <contact@webexmachina.fr>
 * @link     https://github.com/Web-Ex-Machina/contao-auctions/
 */

/*
 * Table tl_wem_auction_offer.
 */
$GLOBALS['TL_DCA']['tl_wem_auction_offer'] = [
    // Config
    'config' => [
        'dataContainer' => 'Table',
        'ptable' => 'tl_wem_auction',
        'switchToEdit' => true,
        'enableVersioning' => true,
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'pid' => 'index',
            ],
        ],
    ],

    // List
    'list' => [
        'sorting' => [
            'mode' => 4,
            'fields' => ['provider DESC'],
            'headerFields' => ['title'],
            'panelLayout' => 'filter;sort,search,limit',
            'child_record_callback' => ['AltradLogin\DataContainer\AppProviderContainer', 'listItems'],
            'child_record_class' => 'no_padding',
        ],
        'global_operations' => [
            'all' => [
                'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"',
            ],
        ],
        'operations' => [
            'edit' => [
                'label' => &$GLOBALS['TL_LANG']['tl_wem_auction_offer']['edit'],
                'href' => 'act=edit',
                'icon' => 'edit.gif',
            ],
            'delete' => [
                'label' => &$GLOBALS['TL_LANG']['tl_wem_auction_offer']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\''.$GLOBALS['TL_LANG']['MSC']['deleteConfirm'].'\'))return false;Backend.getScrollOffset()"',
            ],
            'show' => [
                'label' => &$GLOBALS['TL_LANG']['tl_wem_auction_offer']['show'],
                'href' => 'act=show',
                'icon' => 'show.gif',
            ],
        ],
    ],

    // Palettes
    'palettes' => [
        'default' => '{title_legend},provider',
    ],

    // Fields
    'fields' => [
        'id' => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'pid' => [
            'foreignKey' => 'tl_wem_auction_offer.title',
            'sql' => "int(10) unsigned NOT NULL default '0'",
            'relation' => ['type' => 'belongsTo', 'load' => 'eager'],
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'createdAt' => [
            'default' => time(),
            'flag' => 8,
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],

        'provider' => [
            'label' => &$GLOBALS['TL_LANG']['tl_wem_auction_offer']['provider'],
            'exclude' => true,
            'filter' => true,
            'inputType' => 'select',
            'eval' => ['chosen' => true, 'tl_class' => 'w50', 'submitOnChange' => true],
            'foreignKey' => 'tl_wem_provider.title',
            'sql' => "int(10) unsigned NOT NULL default '0'",
            'relation' => ['type' => 'hasOne', 'load' => 'eager'],
        ],

        'groups' => [
            'label' => &$GLOBALS['TL_LANG']['tl_wem_auction_offer']['groups'],
            'exclude' => true,
            'filter' => true,
            'inputType' => 'select',
            'options_callback' => ['AltradLogin\DataContainer\AppProviderContainer', 'getGroups'],
            'eval' => ['chosen' => true, 'tl_class' => 'w50', 'multiple' => true],
            'sql' => 'blob NULL',
        ],

        'emails' => [
            'label' => &$GLOBALS['TL_LANG']['tl_wem_auction_offer']['emails'],
            'exclude' => true,
            'inputType' => 'listWizard',
            'eval' => ['tl_class' => 'clr'],
            'sql' => 'blob NULL',
        ],
        'domains' => [
            'label' => &$GLOBALS['TL_LANG']['tl_wem_auction_offer']['domains'],
            'exclude' => true,
            'inputType' => 'listWizard',
            'eval' => ['tl_class' => 'clr'],
            'sql' => 'blob NULL',
        ],
        'ips' => [
            'label' => &$GLOBALS['TL_LANG']['tl_wem_auction_offer']['ips'],
            'exclude' => true,
            'inputType' => 'listWizard',
            'eval' => ['tl_class' => 'clr'],
            'sql' => 'blob NULL',
        ],

        'sendNotification' => [
            'label' => &$GLOBALS['TL_LANG']['tl_wem_auction_offer']['sendNotification'],
            'exclude' => true,
            'inputType' => 'select',
            'options_callback' => [AltradLogin\DataContainer\AppProviderContainer::class, 'getSendCodeNotifications'],
            'eval' => ['includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'clr'],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
    ],
];

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 */
class tl_wem_auction_offer extends Backend
{
    /**
     * Import the back end user object.
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }
}
