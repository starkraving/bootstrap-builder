<?php
/*

Copyright 2011  Wild Mice Media

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA



if(!is_admin()) {*/

  // This section emulates the CodeIgniter bootstrap, without any application environment

  try {

    // backuping query string
    $query_string = urlencode($_SERVER['QUERY_STRING']);

    // destroying the $_GET array (but reconstructed when CI leaves)
    $_GET = array();

    // let's use CI a bit to recover our $_GET array
    $_SERVER['PATH_INFO'] = '/ci_mods/recover_get_array/'.$query_string;  

    // application environment
    define('ENVIRONMENT', 'production');

    // system folder
    $system_path = str_replace('\\','/',realpath(__DIR__.'/ci/system'));
    $system_path = rtrim($system_path, '/').'/';
    define('BASEPATH',$system_path);

    // name of system folder
    define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));

    // application folder
    $application_folder = str_replace('\\','/',realpath(__DIR__.'/ci/application'));
    define('APPPATH', $application_folder.'/');

    // name of this file
    define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

    // path to front controller (this file)
    define('FCPATH', str_replace(SELF, '', __FILE__));

    // PHP file extension
    define('EXT', '.php');

    // Set the current directory correctly for CLI requests
    if (defined('STDIN')) { chdir(dirname(__FILE__)); }

    // load bootstrap file
    require_once realpath(__DIR__.'/ci_mods/CodeIgniter.php');

    // if everything went well, set a status flag
    define('WP_CODE_IGNITER',TRUE);

  } catch (Exception $e) {
    // try/catch works best in object mode (which we cannot use here), so not all errors will be caught
    echo '<span style="font-weight:bold;">WP Code Igniter:</span> '.nl2br($e);
  }
  

/*}*/
?>