<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'auth';

// Route for admin
$route['admin'] = 'auth/admin_login';

// Route for guru
$route['guru'] = 'auth/guru_login';

// Route for siswa
$route['siswa'] = 'auth/siswa_login';

// Room & Paket & Ujian routes
$route['room'] = 'room/index';
$route['room/create'] = 'room/create';
$route['room/edit/(:num)'] = 'room/edit/$1';
$route['room/delete/(:num)'] = 'room/delete/$1';
$route['room/detail/(:num)'] = 'room/detail/$1';
$route['room/preview/(:num)'] = 'room/preview/$1';
$route['room/reset_hasil/(:num)'] = 'room/reset_hasil/$1';

$route['paket'] = 'paket/index';
$route['paket/upload'] = 'paket/upload';
$route['paket/approve/(:num)'] = 'paket/approve/$1';
$route['paket/reject/(:num)'] = 'paket/reject/$1';
$route['paket/edit/(:num)'] = 'paket/edit/$1';

$route['ujian/list'] = 'ujian/list';
$route['ujian/start/(:num)'] = 'ujian/start/$1';
$route['ujian/save_answer'] = 'ujian/save_answer';
$route['ujian/end'] = 'ujian/end';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
