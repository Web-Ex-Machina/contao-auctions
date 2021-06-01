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
use WEM\AuctionsBundle\Model\AuctionOffer as AuctionOfferModel;

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

            // Catch Ajax
            $this->handleAjaxRequests();

            $this->Template->moduleID = $this->id;

            // Load a user
            $this->Template->hasUser = false;
            $arrUser = $this->loadUser();
            if (null !== $arrUser) {
                $this->Template->hasUser = true;
                $this->Template->userFirstname = $arrUser[0];
                $this->Template->userLastname = $arrUser[1];
                $this->Template->userCity = $arrUser[2];
                $this->Template->userPhone = $arrUser[3];
                $this->Template->userEmail = $arrUser[4];
            }

            // Load previous offers
            $objOffers = AuctionOfferModel::findItems(['pid' => $this->wem_auction]);

            $this->Template->noOffers = false;
            $arrOffers = [];
            if (!$objOffers || 0 === $objOffers->count()) {
                $this->Template->noOffers = true;
            } else {
                while ($objOffers->next()) {
                    $arrOffers[] = $objOffers->row();
                }

                $this->Template->offers = $this->parseItems($arrOffers);
            }
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
        if (\Input::post('TL_AJAX') && $this->id === \Input::post('module')) {
            try {
                switch (\Input::post('action')) {
                    case 'addOffer':
                        if (!\Input::post('amount')) {
                            throw new \Exception('Pas de montant envoyé');
                        }

                        // Get the highest offer and check that the new amount is above it
                        $objOffer = AuctionOfferModel::findItems(['pid' => $this->wem_auction], 1, 0, 'amount DESC');

                        if (\Input::post('amount') <= $objOffer->amount) {
                            throw new \Exception('Veuillez saisir un montant plus élevé que la meilleure offre actuelle');
                        }

                        $arrUser = $this->loadUser();

                        if (null === $arrUser) {
                            throw new \Exception('Pas de compte trouvé');
                        }

                        $objOffer = new AuctionOfferModel();
                        $objOffer->tstamp = time();
                        $objOffer->createdAt = time();
                        $objOffer->pid = $this->wem_auction;
                        $objOffer->amount = number_format(\Input::post('amount'), 2);
                        $objOffer->firstname = $arrUser[0];
                        $objOffer->lastname = $arrUser[1];
                        $objOffer->city = $arrUser[2];
                        $objOffer->phone = $arrUser[3];
                        $objOffer->email = $arrUser[4];
                        $objOffer->save();

                        $arrResponse = ['status' => 'success', 'html' => $this->parseItem($objOffer->row())];
                    break;

                    case 'getOffers':
                        // Load previous offers
                        $c = ['pid' => $this->wem_auction];

                        if (\Input::post('timestamp')) {
                            $c['createdAtAfter'] = (int) \Input::post('timestamp');
                        }

                        $objOffers = AuctionOfferModel::findItems($c);

                        if (!$objOffers || 0 === $objOffers->count()) {
                            $arrResponse = ['status' => 'success', 'html' => 'Pas d\'offres à ce jour'];
                        } else {
                            while ($objOffers->next()) {
                                $arrOffers[] = $objOffers->row();
                            }

                            $arrResponse = ['status' => 'success', 'html' => $this->parseItems($arrOffers)];
                        }
                    break;

                    case 'createUser':
                        if (!\Input::post('firstname')) {
                            throw new \Exception('Veuillez saisir un prénom');
                        }
                        if (!\Input::post('lastname')) {
                            throw new \Exception('Veuillez saisir un nom de famille');
                        }
                        if (!\Input::post('city')) {
                            throw new \Exception('Veuillez saisir une ville');
                        }
                        if (!\Input::post('email')) {
                            throw new \Exception('Veuillez saisir une adresse email');
                        }
                        if (!\Validator::isEmail(\Input::post('email'))) {
                            throw new \Exception('Veuillez saisir une adresse email valide');
                        }

                        if (
                            !$this->wem_auction_createUserNotification
                            || null === ($objNotification = \NotificationCenter\Model\Notification::findByPk($this->wem_auction_createUserNotification))
                        ) {
                            throw new \Exception($GLOBALS['TL_LANG']['WEMAUTH']['ERR']['noCreateUserNotification']);
                        }

                        $arrTokens = [
                            'admin_email' => \Config::get('adminEmail'),
                            'recipient' => \Input::post('email'),
                            'user_firstname' => \Input::post('firstname'),
                            'user_lastname' => \Input::post('lastname'),
                            'user_city' => \Input::post('city'),
                            'user_email' => \Input::post('email'),
                            'user_phone' => \Input::post('phone'),
                        ];
                        $objNotification->send($arrTokens);

                        $strName = 'wem_auction_user';
                        $strValue = sprintf('%s::%s::%s::%s::%s',
                            \Input::post('firstname'),
                            \Input::post('lastname'),
                            \Input::post('city'),
                            \Input::post('phone') ?: '',
                            \Input::post('email'),
                        );

                        \System::setCookie($strName, $strValue, strtotime('+30 days'));

                        $arrResponse = ['status' => 'success', 'msg' => 'Votre compte a été créé avec succès !'];

                        break;

                    case 'logoutUser':
                        $strName = 'wem_auction_user';
                        \System::setCookie($strName, "", strtotime('-1 day'));

                        $arrResponse = ['status' => 'success', 'msg' => 'Votre compte a été déconnecté'];
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

    protected function loadUser()
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
            $limit = \count($arrItems);
            if ($limit < 1) {
                return [];
            }

            $count = 0;
            $arrElements = [];
            foreach ($arrItems as $arrItem) {
                $arrElements[] = $this->parseItem($arrItem, $strTemplate, ((1 === ++$count) ? ' first' : '').(($count === $limit) ? ' last' : '').((0 === ($count % 2)) ? ' odd' : ' even'), $count);
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
    protected function parseItem($arrItem, $strTemplate = 'wem_auction_offer_row_default', $strClass = '', $intCount = 0)
    {
        try {
            /* @var \PageModel $objPage */
            global $objPage;

            /** @var \FrontendTemplate|object $objTemplate */
            $objTemplate = new \FrontendTemplate($strTemplate);
            $objTemplate->setData($arrItem);
            $objTemplate->class = (('' !== $arrItem['cssClass']) ? ' '.$arrItem['cssClass'] : '').$strClass;
            $objTemplate->count = $intCount;

            $objTemplate->createdAt = date('d/m/Y à H:i', (int) $objTemplate->createdAt);
            $objTemplate->tstamp = date('d/m/Y à H:i', (int) $objTemplate->tstamp);

            return $objTemplate->parse();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
