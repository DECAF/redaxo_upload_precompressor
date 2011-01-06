<?php
/**
 * decaf_upload_precompressor
 *
 * @author Sven Kesting <sk@decaf.de>
 * @author <a href="http://www.decaf.de">www.decaf.de</a>
 * @package redaxo4
 * @version $Id: help.inc.php 79 2010-12-20 16:50:05Z sk $
 */

$mypage = 'decaf_upload_precompressor';

$base_path = $REX['INCLUDE_PATH'] .'/addons/'.$mypage;

?>
<style type="text/css" media="screen">
  div#decaf_piwik_tracker_help p { margin-bottom: 12px; margin-left: 10px;}
  div#decaf_piwik_tracker_help h1 { margin-bottom: 10px; font-size: 150%;}
  div#decaf_piwik_tracker_help h2 { margin-top: 20px; margin-bottom: 10px; font-size: 120%;}
  div#decaf_piwik_tracker_help ul { margin-bottom: 10px; padding-left: 40px; }
</style>

<div id="decaf_piwik_tracker_help">

<h1>README</h1>

<h2>AddOn: Upload Precompressor [ID=839]</h2>

<h2>About decaf&#95;upload&#95;precompressor</h2>

<p>This REDAXO-Addon hooks into the MEDIA_ADDED extension point and check if the dimensions of uploaded images are way overboard (Clients tend to clutter the files folder with really large JPGs).</p>

<p>Images exceding the configured dimensions will be resized using GD.</p>

<h2>IMPORTANT</h2>

<p>This Addon will scale the original images (destructive scaling). The main purpose is to preserve harddisk space, so no backups are kept.</p>

<p>If you are unsure what this addon does it is probably not for you.</p>

<p>You have been warned.</p>

<h2>Changelog</h2>

<ul>
<li><strong>1.0.0:</strong> 

<ul>
<li>Initial release</li>
</ul></li>
</ul>



</div>