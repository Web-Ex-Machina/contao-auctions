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

namespace WEM\AuctionsBundle\DataContainer;

class AuctionOfferContainer
{
    /**
     * Auto-generate an article alias if it has not been set yet.
     *
     * @throws Exception
     *
     * @return string
     */
    public function listItems($r)
    {
        return sprintf('%s - %s par %s %s (%s / %s)',
            $r['amount'],
            date('d/m/Y à H:i', (int) $r['createdAt']),
            $r['firstname'],
            $r['lastname'],
            $r['phone'] ?: 'NR',
            $r['email']
        );
    }
}
