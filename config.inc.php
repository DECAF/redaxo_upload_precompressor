<?php
/**
 * decaf_upload_precompressor
 *
 * @author DECAF
 * @version $Id$
 */

$mypage = 'decaf_upload_precompressor';

$REX['ADDON']['rxid'][$mypage]    = "839";
$REX['ADDON']['page'][$mypage]    = $mypage;
$REX['ADDON']['version'][$mypage] = "1.1";
$REX['ADDON']['author'][$mypage]  = "DECAF";
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
  $I18N->appendFile($REX['INCLUDE_PATH'].'/addons/'.$mypage.'/lang/');
  
  $REX['ADDON']['name'][$mypage]    = $I18N->msg("dcf_precomp_menu");

  // include extension point only in backend
  require_once($REX['INCLUDE_PATH']."/addons/".$mypage."/extensions/extension.".$mypage.".inc.php");

}