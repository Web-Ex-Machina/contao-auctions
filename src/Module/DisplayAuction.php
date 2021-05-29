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
}
