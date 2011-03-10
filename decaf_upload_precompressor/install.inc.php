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
  if ($REX['LANG'] == 'default')
  {
    $be_lang = 'de_de_utf8';
  } 
  else {
    $be_lang = $REX['LANG'];
  }
  $I18N->appendFile($REX['INCLUDE_PATH'].'/addons/'.$mypage.'/lang/');
}

$error = false;
$err_msg = array();

// check for REX version < 4.3
if ( ($REX['VERSION'] < 4) || ($REX['VERSION'] == 4 && $REX['SUBVERSION'] < 3) )
{
  $err_msg[] = $I18N->msg('dcf_precomp_rex_version');
  $error = true;
}
else {
  // check if /config is writable
  if (!is_writable($base_path.'/config/'))
  {
    $error = true;
    $err_msg[] = $I18N->msg('dcf_precomp_config_dir_locked');
  }

  $available_memory = getMemoryLimitInMb();
  if ($available_memory < 32 && $available_memory != -1) 
  {
    $err_msg[] = $I18N->msg('dcf_precomp_insufficient_memory');
    $error = true;
  }
}

if (!$error) 
{
  // check if config.ini exists
  $file = $base_path.'/config/config.ini.php';
  if (!file_exists($file)) 
  {
    $cfg = parse_ini_file($base_path.'/config/_config.ini.php');
    $tpl = rex_get_file_contents($base_path.'/config/_config.ini.php');
    // set defaults
    $search[]   = '@@max_pixel@@';
    $replace[]  = '1200';
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
else
{
  $REX['ADDON']['installmsg']['decaf_upload_precompressor'] = implode('<br />', $err_msg);
}


function getMemoryLimitInMb()
{
  $ml = @ini_get('memory_limit');
  if ($ml == 0) return -1;
  $unit = substr($ml,strlen($ml)-1, 1);
  switch ($unit) {
    case 'G' :
    case 'g' :
     $memory = (substr($ml,0, strlen($ml)-1) * 1024);
     break;
    case 'M' :
    case 'm' :
     $memory = substr($ml,0, strlen($ml)-1);
     break;
    case 'K' :
    case 'k' :
      $memory = round((substr($ml,0, strlen($ml)-1) / 1024), 0);
      break;
    default:
      $memory = round(($ml / 1024 / 1024), 0);
  }
  return $memory;
}
?>
