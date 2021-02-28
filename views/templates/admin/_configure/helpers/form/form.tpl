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

{extends file="helpers/form/form.tpl"}

{block name="field"}
	{if $input.type == 'text_read_only'}
		<div class="col-lg-9">
			 <div class="form-group row form-inline col-lg-9">
					<input type="text" readonly class="input-group" id="copyField" value="{$module_link}">
					<button type="button" class="btn btn-info btn-clipboard">Copy</button>
			</div>
	</div>
	<script>
		$(document).ready(function(){
			$('.btn-clipboard').on('click' , function() {

				var copyText = document.getElementById("copyField");
				/* Select the text field */
				copyText.select();
				copyText.setSelectionRange(0, 99999); /* For mobile devices */
				/* Copy the text inside the text field */
				document.execCommand("copy");

			});

		});
	</script>
	{/if}
	{if $input.type == 'file'}
		{$smarty.block.parent}

    <div class="col-lg-12">
      <p class="text-center">{l s='Images list' mod='test_module'}</p>
			<div class="row image-grid">
				{foreach $links_img key=k item=v}
				<div class="col-sm-3 col-md-3">
					<div class="panel panel-default">
						<div class="panel-body ">
								<button type="button" class="close delete_ajax_close" aria-label="Close" data-src="{$v}">
									<span aria-hidden="true">&times;</span>
								</button>
								<img class="img-responsive center-block images_gallery_bo" src="{$k}">
						</div>
					</div>
				</div>
				{/foreach}
			<div>
    </div>

		{else}
			{$smarty.block.parent}
		{/if}
{/block}

