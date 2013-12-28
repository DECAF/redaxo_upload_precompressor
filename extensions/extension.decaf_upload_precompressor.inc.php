<?php
/**
 * decaf_upload_precompressor
 *
 * @author DECAF
 * @version $Id$
 */
if (!isset($RETURN)) 
{
  $RETURN = '';
}


if ( ($REX['VERSION'] < 4) || ($REX['VERSION'] == 4 && $REX['SUBVERSION'] < 5) )
{
  rex_register_extension('MEDIA_ADDED', 'decaf_upload_precompressor', array()); // REX < 4.5
}
else
{
  rex_register_extension('MEDIA_ADDED', 'decaf_upload_precompressor', array(), REX_EXTENSION_EARLY); // REX >= 4.5, use early EP
}


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
 * Workaround: Due to EP "MEDIA_ADDED" registered twice in core, dismiss ours.
 */
function unregister_rex_extension($ext_point, $funcname)
{
  global $REX;

  $extensions = &$REX['EXTENSIONS'][$ext_point]; // REX < 4.5
  if (isset($extensions[-1]) && is_array($extensions[-1])) {
    $extensions = &$REX['EXTENSIONS'][$ext_point][-1]; // REX >= 4.5
  }

  for($i=0; $i<count($extensions); $i++)
  {
    if ($extensions[$i][0] == $funcname)
    {
      unset($extensions[$i]);
    }
  }
}
