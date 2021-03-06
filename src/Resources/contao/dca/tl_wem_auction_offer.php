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
            'fields' => ['createdAt DESC'],
            'headerFields' => ['title'],
            'panelLayout' => 'filter;sort,search,limit',
            'child_record_callback' => ['WEM\AuctionsBundle\DataContainer\AuctionOfferContainer', 'listItems'],
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
        'default' => '{offer_legend},createdAt,amount;{user_legend},firstname,lastname,city,phone,email',
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
        'amount' => [
            'default' => '0.00',
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'rgxp' => 'digit', 'tl_class' => 'w50'],
            'sql' => "decimal(10,2) NOT NULL default '0.00'",
        ],
        'firstname' => [
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'feEditable' => true, 'feViewable' => true, 'feGroup' => 'personal', 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'lastname' => [
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'flag' => 1,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'feEditable' => true, 'feViewable' => true, 'feGroup' => 'personal', 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'city' => [
            'exclude' => true,
            'filter' => true,
            'search' => true,
            'sorting' => true,
            'inputType' => 'text',
            'eval' => ['maxlength' => 255, 'feEditable' => true, 'feViewable' => true, 'feGroup' => 'address', 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'phone' => [
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => ['maxlength' => 64, 'rgxp' => 'phone', 'decodeEntities' => true, 'feEditable' => true, 'feViewable' => true, 'feGroup' => 'contact', 'tl_class' => 'w50'],
            'sql' => "varchar(64) NOT NULL default ''",
        ],
        'email' => [
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'rgxp' => 'email', 'unique' => true, 'decodeEntities' => true, 'feEditable' => true, 'feViewable' => true, 'feGroup' => 'contact', 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
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
