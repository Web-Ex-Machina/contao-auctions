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

use WEM\AuctionsBundle\Model\Auction as AuctionModel;

class ModuleContainer
{
    public function getAuctions($dc)
    {
        $objItems = AuctionModel::findAll();

        if (!$objItems || 0 === $objItems->count()) {
            return [];
        }

        $arrData = [];
        while ($objItems->next()) {
            $arrData[$objItems->id] = $objItems->title;
        }

        return $arrData;
    }
}
