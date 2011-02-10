<?php
/**
 * decaf_upload_precompressor
 *
 * @author Sven Kesting <sk@decaf.de>
 * @author <a href="http://www.decaf.de">www.decaf.de</a>
 * @package redaxo4
 * @version $Id: config.inc.php 20 2010-11-29 14:31:27Z sk $
 */

$mypage = 'decaf_upload_precompressor';

$REX['ADDON']['rxid'][$mypage]    = "839";
$REX['ADDON']['page'][$mypage]    = $mypage;
$REX['ADDON']['version'][$mypage] = "1.0.1";
$REX['ADDON']['author'][$mypage]  = "Sven Kesting <sk@decaf.de>, DECAF";
$REX['ADDON']['perm'][$mypage]    = "admin[]";



if ($REX['REDAXO'])
{
  // looad localized strings
  if ($REX['LANG'] == 'default')
  {
    $be_lang = 'de_de_utf8';
  } 
  else {
    $be_lang = $REX['LANG'];
  }

  $dcf_I18N = new i18n($be_lang, $REX['INCLUDE_PATH'].'/addons/'.$mypage.'/lang/');
  $dcf_I18N->loadTexts();

  $REX['ADDON']['name'][$mypage]    = $dcf_I18N->msg("dcf_precomp_menu");

  // include extension point only in backend
  require_once($REX['INCLUDE_PATH']."/addons/".$mypage."/extensions/extension.".$mypage.".inc.php");

}