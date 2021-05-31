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

namespace WEM\AuctionsBundle\Module;

use WEM\AuctionsBundle\Model\Auction as AuctionModel;

class DisplayAuction extends \Module
{
    /**
     * Template.
     *
     * @var string
     */
    protected $strTemplate = 'mod_wem_auctions_display_auction';

    /**
     * Display a wildcard in the back end.
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE === 'BE') {
            $this->Template = new \BackendTemplate('be_wildcard');

            $this->Template->wildcard = '### '.utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['wem-auctions_display-auction'][0]).' ###';
            $this->Template->title = $this->headline;
            $this->Template->id = $this->id;
            $this->Template->link = $this->name;
            $this->Template->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id='.$this->id;

            return $this->Template->parse();
        }

        \System::loadLanguageFile('errors');

        return parent::generate();
    }

    /**
     * Generate the module.
     */
    protected function compile(): void
    {
        try {
            // Load the auction
            $this->objAuction = AuctionModel::findByPk($this->wem_auction);

            // Load a user
            $this->Template->hasUser = false;
            $arrUser = $this->loadUser();
            if(null !== $arrUser)
            {
                $this->Template->hasUser = true;
                $this->Template->userFirstname = $arrUser[0];
                $this->Template->userLastname = $arrUser[1];
                $this->Template->userCity = $arrUser[2];
                $this->Template->userPhone = $arrUser[3];
                $this->Template->userEmail = $arrUser[4];
            }

            // Load previous offers
            $objOffers = $this->

        } catch (\Exception $e) {
            $this->Template->hasError = true;
            $this->Template->error = $e->getMessage();
            $this->Template->trace = $e->getTrace();
        }
    }

    /**
     * Handle Ajax requests.
     */
    protected function handleAjaxRequests(): void
    {
        // Catch AJAX Requests
        if (\Input::post('TL_WEM_AJAX') && $this->id === \Input::post('module')) {
            try {
                switch (\Input::post('action')) {
                    case 'addOffer':

                    break;

                    case 'getOffers':

                    break;

                    case 'createUser':

                    break;

                    default:
                        throw new \Exception(sprintf($GLOBALS['TL_LANG']['WEMAUCTIONS']['ERR']['unknownAjaxRequest'], \Input::post('action')));
                }
            } catch (\Exception $e) {
                $arrResponse = ['status' => 'error', 'msg' => $e->getMessage(), 'trace' => $e->getTrace()];
            }

            $arrResponse['token'] = \RequestToken::get();
            echo json_encode($arrResponse);
            die;
        }
    }

    protected function loadUser(): void
    {
        if ($_COOKIE['wem_auction_user']) {
            $arrUser = explode('::', $_COOKIE['wem_auction_user']);
            return $arrUser;
        }

        return null;
    }

    /**
     * Parse multiple items.
     *
     * @param array  $arrItems    [Array of Logs]
     * @param string $strTemplate [Logs template]
     *
     * @return array
     */
    protected function parseItems($arrItems, $strTemplate = 'wem_auction_offer_row_default')
    {
        try {
            $limit = count($arrItems);
            if ($limit < 1) {
                return [];
            }

            $count = 0;
            $arrElements = [];
            foreach ($arrItems as $arrItem) {
                $arrElements[] = $this->parseItem($arrItem, $strTemplate, ((1 == ++$count) ? ' first' : '').(($count == $limit) ? ' last' : '').((0 == ($count % 2)) ? ' odd' : ' even'), $count);
            }

            return $arrElements;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Parse an item.
     *
     * @param array  $arrItem     [Contract as Array]
     * @param string $strTemplate [Template]
     * @param string $strClass    [CSS Class]
     * @param int    $intCount    [Iterator]
     *
     * @return string
     */
    public function parseItem($arrItem, $strTemplate = 'wem_auction_offer_row_default', $strClass = '', $intCount = 0)
    {
        try {
            /* @var \PageModel $objPage */
            global $objPage;

            /** @var \FrontendTemplate|object $objTemplate */
            $objTemplate = new \FrontendTemplate($strTemplate);
            $objTemplate->setData($arrItem);
            $objTemplate->class = (('' != $arrItem['cssClass']) ? ' '.$arrItem['cssClass'] : '').$strClass;
            $objTemplate->count = $intCount;

            $objTemplate->createdAt = date('d/m/Y à H:i', $objTemplate->createdAt);
            $objTemplate->tstamp = date('d/m/Y à H:i', $objTemplate->tstamp);

            return $objTemplate->parse();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
