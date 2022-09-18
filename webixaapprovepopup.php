<?php
/**
 * 2007-2021 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2021 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

if (!defined('_PS_VERSION_')) {
    exit;
}

class WebixaApprovePopup extends Module implements WidgetInterface
{
    public function __construct()
    {
        $this->name = 'webixaapprovepopup';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Webixa';
        $this->need_instance = true;

        parent::__construct();

        $this->displayName = $this->l('Popup dla zalogowanych klientów');
        $this->description = $this->l('Popup pokazujący się dla klinetów z kontem gdzie nie są oznaczeni jako firma');

        $this->ps_versions_compliancy = [
            'min' => '1.7.3',
            'max' => _PS_VERSION_,
        ];

        $this->templateFilePl = 'popup_pl.tpl';
        $this->templateFileEn = 'popup_en.tpl';
    }

    public function install()
    {
        return parent::install() &&
            $this->registerHook('displayHeader');
    }

    public function renderWidget($hookName, array $configuration)
    {
        $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));

        if ($configuration['language'] == "en") {
            return $this->fetch($this->templateFileEn);
        }
        if ($configuration['language'] == "pl") {
            return $this->fetch($this->templateFilePl);
        }
    }

    public function getWidgetVariables($hookName, array $configuration)
    {
        return [];
    }

    public function hookDisplayHeader($params)
    {
        $this->context->controller->addCSS(sprintf("%sviews/css/popup_style.css", $this->_path));

        $lang = $this->context->language->id;
        $isSubmit = Tools::getValue('company', null) == "on" && Tools::getValue('terms', null) == "on";

        $isCustomerCompany = $this->context->customer->is_company == 1;
        $isLogged = $this->context->customer->isLogged();

        $langPL = Language::getIdByIso('pl');
        $langEN = Language::getIdByIso('en');

        /**
         * Check if user is logged in, is a company and haven't submitted new terms
         */
        if ($isLogged && !$isCustomerCompany && !$isSubmit) {
            if ($lang == $langPL) {
                return $this->display(__FILE__, $this->templateFilePl);
            } else if ($lang == $langEN) {
                return $this->display(__FILE__, $this->templateFileEn);
            }
        }

        /**
         * If terms accepted mark customer as company
         */
        if ($isLogged && !$isCustomerCompany && $isSubmit) {
            $this->context->customer->is_company = 1;
            $this->context->customer->save();
        }
    }
}



