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
 * Backend modules
 */
array_insert(
    $GLOBALS['BE_MOD'],
    array_search('content', $GLOBALS['BE_MOD']) + 1,
    [
        'wemauctionssection' => [
            'wemauctions' => [
                'tables' => ['tl_wem_auction', 'tl_wem_auction_offer'],
            ],
        ],
    ]
);

/*
 * Frontend modules
 */
array_insert($GLOBALS['FE_MOD'], 2, [
    'wemauctionssection' => [
        'wem-auctions_display-auction' => WEM\AuctionsBundle\Module\DisplayAuction::class,
    ],
]);

/*
 * Models
 */
$GLOBALS['TL_MODELS'][WEM\AuctionsBundle\Model\Auction::getTable()] = 'WEM\AuctionsBundle\Model\Auction';
$GLOBALS['TL_MODELS'][WEM\AuctionsBundle\Model\AuctionOffer::getTable()] = 'WEM\AuctionsBundle\Model\AuctionOffer';

/*
 * Notifications
 */
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['wemauctions_notifications'] = [
    'wemauctions_createUser' => [
        'recipients' => ['recipient', 'admin_email'],
        'email_text' => ['action', 'code', 'user_*'],
        'email_html' => ['action', 'code', 'user_*'],
        'email_sender_name' => ['admin_email'],
        'email_sender_address' => ['admin_email'],
        'email_recipient_cc' => ['admin_email'],
        'email_recipient_bcc' => ['admin_email'],
        'email_replyTo' => ['admin_email'],
    ],
];