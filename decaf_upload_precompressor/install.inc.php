<?php
/**
 * decaf_upload_precompressor
 *
 * @author Sven Kesting <sk@decaf.de>
 * @author <a href="http://www.decaf.de">www.decaf.de</a>
 * @package redaxo4
 * @version $Id: install.inc.php 20 2010-11-29 14:31:27Z sk $
 */

$mypage = 'decaf_upload_precompressor';

$base_path = $REX['INCLUDE_PATH'] .'/addons/'.$mypage;

if ($REX['REDAXO'])
{
  if ($lang == 'default')
  {
    $be_lang = 'de_de_utf8';
  } 
  else {
    $be_lang = $lang;
  }
  $dcf_I18N = new i18n($be_lang, $REX['INCLUDE_PATH'].'/addons/'.$mypage.'/lang/');
  $dcf_I18N->loadTexts();
}

$error = false;

// check if /config is writable
if (!is_writable($base_path.'/config/'))
{
  echo rex_warning($dcf_I18N->msg('dcf_precomp_config_dir_locked'));
  $error = true;
}
else 
{
  // check if config.ini exists
  $file = $base_path.'/config/config.ini.php';
  if (!file_exists($file)) 
  {
    $cfg = parse_ini_file($base_path.'/config/_config.ini.php');
    $tpl = rex_get_file_contents($base_path.'/config/_config.ini.php');
    // set defaults
    $search[]   = '@@max_pixel@@';
    $replace[]  = '1500';
    $search[]   = '@@jpg_quality@@';
    $replace[]  = '85';
    $config_str = str_replace($search, $replace, $tpl);
    file_put_contents($base_path.'/config/config.ini.php', $config_str);
  }
}

if (!$error) 
{
  $REX['ADDON']['install'][$mypage] = true;
}

?>
