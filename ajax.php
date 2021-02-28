<?php
/**
 * Blog for PrestaShop module by Aleksander CoolSoft-Web from PrestaHome.
 *
 * @author    Aleksander CoolSoft-Web
 * @copyright Copyright (c) 2008-2021 Aleksander CoolSoft-Web-
 * @license   You only can use module, nothing more!
 */
include dirname(__FILE__).'/../../config/config.inc.php';
include dirname(__FILE__).'/../../init.php';

if(isset($_POST['token']) && $_POST['image_delete'] ) {
  unlink(dirname(__FILE__).DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.$_POST['image_delete']);
  echo 'succes';
}
