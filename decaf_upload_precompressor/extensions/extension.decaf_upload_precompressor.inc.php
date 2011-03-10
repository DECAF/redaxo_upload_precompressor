<?php
/**
 * decaf_upload_precompressor
 *
 * @author Sven Kesting <sk@decaf.de>
 * @author <a href="http://www.decaf.de">www.decaf.de</a>
 * @package redaxo4
 * @version $Id: extension.decaf_upload_precompressor.inc.php 78 2010-12-20 16:47:31Z sk $
 */
if (!isset($RETURN)) 
{
  $RETURN = '';
}

rex_register_extension('MEDIA_ADDED', 'decaf_upload_precompressor', $RETURN);

function decaf_upload_precompressor($params)
{
  global $REX;
  $scalable_mime_types = array('image/jpeg', 'image/jpg', 'image/pjpeg');
  $mypage = 'decaf_upload_precompressor';
  unregister_rex_extension('MEDIA_ADDED', 'decaf_upload_precompressor'); // unregister this extension point, it's called twice from REDAXO-Core...

  if (file_exists($REX['INCLUDE_PATH']. '/addons/'.$mypage.'/config/config.ini.php'))
  {
    $dcf_precomp_config = parse_ini_file($REX['INCLUDE_PATH']. '/addons/'.$mypage.'/config/config.ini.php', true);
    if (in_array($params['type'], $scalable_mime_types) && $params['ok'])
    {
      // check if image needs scaling
      if ($params['width'] > $dcf_precomp_config['dcf_precomp']['max_pixel'] || $params['height'] > $dcf_precomp_config['dcf_precomp']['max_pixel'])
      {
        if ($params['width'] > $params['height'])
        {
          $ratio = $dcf_precomp_config['dcf_precomp']['max_pixel'] / $params['width'];
        }
        else
        {
          $ratio = $dcf_precomp_config['dcf_precomp']['max_pixel'] / $params['height'];
        }

        $newwidth = round($params['width'] * $ratio);
        $newheight = round($params['height']  * $ratio);

        // Load
        $image = imagecreatetruecolor($newwidth, $newheight);
        $source = imagecreatefromjpeg($REX['MEDIAFOLDER'].'/'.$params['filename']);

        // Resize
        imagecopyresampled($image, $source, 0, 0, 0, 0, $newwidth, $newheight, $params['width'], $params['height']);

        // save Image
        imagejpeg($image, $REX['MEDIAFOLDER'].'/'.$params['filename'], $dcf_precomp_config['dcf_precomp']['jpg_quality']);

        // update db entry
        $size = @getimagesize($REX['MEDIAFOLDER'].'/'.$params['filename']);
        $filesize = @filesize($REX['MEDIAFOLDER'].'/'.$params['filename']);

        $FILESQL = rex_sql::factory();
        // $FILESQL->debugsql = 1;
        $FILESQL->setTable($REX['TABLE_PREFIX'].'file');
        $FILESQL->setWhere('filename="'. $params['filename'].'"');
        $FILESQL->setValue('filesize',$filesize);
        $FILESQL->setValue('width',$size[0]);
        $FILESQL->setValue('height',$size[1]);
        $FILESQL->update();

        // $params['msg'] = $params['msg'] . "\n" . "Das Bild wurde kleiner skaliert";
      }
    }
  }
  return $params;
}


/**
 * Workaround for another redaxo featurebug - extension point "MEDIA_ADDED"
 * is registered twice in core.
 */
function unregister_rex_extension($ext_point, $funcname)
{
  global $REX;
  for($i=0; $i<count($REX['EXTENSIONS'][$ext_point]); $i++)
  {
    if ($REX['EXTENSIONS'][$ext_point][$i][0] == $funcname)
    {
      unset($REX['EXTENSIONS'][$ext_point][$i]);
    }
  }
}
