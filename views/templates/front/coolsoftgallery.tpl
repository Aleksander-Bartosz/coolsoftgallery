{*
* 2007-2015 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{extends 'page.tpl'}
{block name='content'}
<section id="main">
    <div class="container custom_gallery_page">
        <div class="row">
            <div class="custom_text_one">
                {$custom_text1 nofilter}
            </div>
        </div>
        <div class="row">
            <ul class="lightgallery_styles" id="lightgallery">
                {foreach from=$gallery_images item=image}
                  <li data-src="{$image}" class="col-lg-4 col-md-12 mb-4">
                    <a href="">
                        <img class="img-fluid mb-4" src="{$image}">
                    </a>
                 </li>
                {/foreach}
            </ul>
        </div>
        <div class="row">
            <div class="custom_text_two">
                {$custom_text2 nofilter}
            </div>
        </div>

    </div>
</section>
{/block}