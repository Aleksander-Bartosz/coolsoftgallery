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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2021 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class CoolSoftGallery extends Module
{
    public function __construct()
    {
        $this->name = 'coolsoftgallery';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'CoolSoft-Web';
        $this->need_instance = 0;
        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('coolsoftgallery');
        $this->description = $this->l('Module allow to  create custom page with gallery');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed: actionAdminControllerSetMedia
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {

        if (!parent::install() &&
        !$this->registerHook('actionObjectLanguageAddAfter') &&
        !$this->registerHook('displayBackOfficeHeader') &&
        !$this->installFixtures()
        ) {
            return false;
        }
        return true;
    }

    protected function installFixtures()
    {
        $languages = Language::getLanguages(false);

        foreach ($languages as $lang) {
            $this->installFixture((int)$lang['id_lang'] );
        }

        return true;
    }

    protected function installFixture($id_lang)
    {
        $values['GALLERY_IMAGES'] = '';
        $values['CUSTOM_PAGE_TEXT1'][(int)$id_lang] = '';
        $values['CUSTOM_PAGE_TEXT2'][(int)$id_lang] = '';

        Configuration::updateValue('GALLERY_IMAGES', $values['GALLERY_IMAGES']);
        Configuration::updateValue('CUSTOM_PAGE_TEXT1', $values['CUSTOM_PAGE_TEXT1'], true);
        Configuration::updateValue('CUSTOM_PAGE_TEXT2', $values['CUSTOM_PAGE_TEXT2'], true);
    }

    public function uninstall()
    {
        Configuration::deleteByName('CUSTOM_PAGE_TEXT1');
        Configuration::deleteByName('CUSTOM_PAGE_TEXT2');
        Configuration::deleteByName('GALLERY_IMAGES');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        return $this->postProcess().$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->trans('Settings', array(), 'Admin.Global'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'text_read_only',
                        'label' => $this->l('Module Link '),
                        'name' => 'MODULE_LINK',
                        'lang' => true,
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Text 1'),
                        'lang' => true,
                        'name' => 'CUSTOM_PAGE_TEXT1',
                        'cols' => 40,
                        'rows' => 10,
                        'class' => 'rte',
                        'autoload_rte' => true,
                    ),
                    array(
                        'type' => 'file',
                        'label' => $this->l('Gallery fields'),
                        'name' => 'GALLERY_IMAGES',
                        'multiple' => true,
                        'lang' => true,
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Text 2'),
                        'lang' => true,
                        'name' => 'CUSTOM_PAGE_TEXT2',
                        'cols' => 40,
                        'rows' => 10,
                        'class' => 'rte',
                        'autoload_rte' => true,

                    )
                ),
                'submit' => array(
                    'title' => $this->trans('Save', array(), 'Admin.Actions')
                )
            ),
        );

        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->default_form_language = $lang->id;
        $helper->module = $this;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitCoolSoftGalleryModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'uri' => $this->getPathUri(),
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
            'links_img' => $this->imgUrls(),
            'module_link' => $this->context->link->getModuleLink('coolsoftgallery', 'gallery', [])
        );

        return $helper->generateForm(array($fields_form));
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {

        $languages = Language::getLanguages(false);
        $fields = array();

        foreach ($languages as $lang) {
            $fields['CUSTOM_PAGE_TEXT1'][$lang['id_lang']] = Tools::getValue('CUSTOM_PAGE_TEXT1_'.$lang['id_lang'], Configuration::get('CUSTOM_PAGE_TEXT1', $lang['id_lang']));
            $fields['CUSTOM_PAGE_TEXT2'][$lang['id_lang']] = Tools::getValue('CUSTOM_PAGE_TEXT2_'.$lang['id_lang'], Configuration::get('CUSTOM_PAGE_TEXT2', $lang['id_lang']));
        }

        return $fields;
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        if (Tools::isSubmit('submitCoolSoftGalleryModule')) {
            $languages = Language::getLanguages(false);
            $values = array();

            foreach ($languages as $lang) {
                $values['CUSTOM_PAGE_TEXT1'][$lang['id_lang']] = Tools::getValue('CUSTOM_PAGE_TEXT1_'.$lang['id_lang']);
                $values['CUSTOM_PAGE_TEXT2'][$lang['id_lang']] = Tools::getValue('CUSTOM_PAGE_TEXT2_'.$lang['id_lang']);
            }



            if ( Tools::getValue('GALLERY_IMAGES') && !empty($_POST) ) {

               foreach ($_FILES['GALLERY_IMAGES']['name'] as $key=>$val) {

                    if( @is_array(getimagesize($_FILES['GALLERY_IMAGES']['tmp_name'][$key])) &&  $_FILES['GALLERY_IMAGES']['size'][$key] < 4000000 ) {
                        $ext = substr($val, strrpos($val, '.') + 1);
                        $file_name = md5($val).'.'.$ext;
                        move_uploaded_file($_FILES['GALLERY_IMAGES']['tmp_name'][$key], dirname(__FILE__).DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.$file_name);
                    } else {
                        return $this->displayError($this->trans('Files are not image type or are to big.', array(), 'Admin.Notifications.Success'));
                    }

                }
            }
            Configuration::updateValue('CUSTOM_PAGE_TEXT1', $values['CUSTOM_PAGE_TEXT1'],true);
            Configuration::updateValue('CUSTOM_PAGE_TEXT2', $values['CUSTOM_PAGE_TEXT2'],true);

            return $this->displayConfirmation($this->trans('The settings have been updated.', array(), 'Admin.Notifications.Success'));
        }

        return '';
    }

    public function getConfigFieldsValues()
    {
        $languages = Language::getLanguages(false);
        $fields = array();

        foreach ($languages as $lang) {
            $fields['CUSTOM_PAGE_TEXT1'][$lang['id_lang']] = Tools::getValue('CUSTOM_PAGE_TEXT1_'.$lang['id_lang'], Configuration::get('CUSTOM_PAGE_TEXT1', $lang['id_lang']));
            $fields['CUSTOM_PAGE_TEXT2'][$lang['id_lang']] = Tools::getValue('CUSTOM_PAGE_TEXT2_'.$lang['id_lang'], Configuration::get('CUSTOM_PAGE_TEXT2', $lang['id_lang']));
        }

        return $fields;
    }

    public function imgUrls()
    {
        $fileListAll = scandir(dirname(_PS_MODULE_DIR_).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'coolsoftgallery'.DIRECTORY_SEPARATOR.'img');
        $fileList = array_diff($fileListAll, array('.', '..'));

        $links = array();
        $linkList;
        foreach($fileList as $fileName){
            $links[] = "\modules\coolsoftgallery\img".DIRECTORY_SEPARATOR.$fileName;
            $linkList = array_fill_keys($links, $fileName);

        }
        return $linkList;
    }


    public function hookDisplayBackOfficeHeader ()
    {

        $this->context->controller->addJS($this->_path . 'views/js/coolsoftgallery.js');
    }

}
