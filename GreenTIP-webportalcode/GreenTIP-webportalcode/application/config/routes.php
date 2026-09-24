<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	http://codeigniter.com/user_guide/general/routing.html
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
$route['default_controller'] = 'index';

$route['404_override'] = 'PageNotFound';
$route['admin/404_override'] = 'admin/PageNotFound';


$route['about-us-page-detail-for-mobile'] = 'page/aboutUspageDetailForMobile';



$route['page/(:any)'] = 'page/pageDetail/$1';
$route['unsubscribe'] = "index/unsubscribe";
$route['reset-password/(:any)'] = 'authentication/ResetPassword/$1';
$route['dashboard'] = 'user/dashboard';
$route['dashboard/(:any)'] = 'user/dashboard/$1';
$route['dashboard/(:any)/(:num)'] = 'user/dashboard/$1/$2';
$route['profile'] = 'user/profile';
$route['bulk-email-notification'] = 'user/bulk_email_notification';
$route['experts'] = 'experts/index';
$route['experts/(:num)'] = 'experts/index/$1';
$route['expert/view/(:num)'] = 'experts/view/$1';
$route['change-password'] = 'user/change_password';
$route['contact'] = 'index/Contact';
$route['user/manage_end_users/(:any)'] = 'user/manage_end_users/$1';
$route['user/manage_users/(:any)'] = 'user/manage_users/$1';
/*$route['sendemail'] = 'user/sendemail';*/
$route['sendemail'] = 'Bgemail/sendemail';
$route['admin'] = 'admin/Dashboard';
$route['admin/email-template/view/(:any)'] = 'admin/EmailTemplate/view/$1';
$route['admin/email-template/edit/(:any)'] = 'admin/EmailTemplate/edit/$1';
$route['admin/email-template'] = 'admin/EmailTemplate/index';
$route['admin/email-template/index'] = 'admin/EmailTemplate/index';
$route['admin/email-template/index/(:any)'] = 'admin/EmailTemplate/index/$1';

$route['admin/login'] = 'admin/authentication/login';
$route['admin/logout'] = 'admin/authentication/logout';
$route['admin/change-password'] = 'admin/authentication/change_password';
$route['admin/profile'] = 'admin/authentication/profile';

/*-------------------------Done monu kumar-----------------------------*/
$route['reports'] = 'user/reports';
$route['user/mdTotalQueriesReports/(:any)'] = 'user/mdTotalQueriesReports/$1';
$route['user/mdNewQueriesReports/(:any)'] = 'user/mdNewQueriesReports/$1';
$route['user/mdClosedQueriesReports/(:any)'] = 'user/mdClosedQueriesReports/$1';



/*
 * API v1 routes
 */
$route['api/v1/(:any)'] = 'v1/$1';
$route['api/v1/(:any)/(:any)'] = 'v1/$1/$2';
$route['api/v1/(:any)/(:any)/(:any)'] = 'v1/$1/$2/$3';
$route['translate_uri_dashes'] = FALSE;

$route['translate_uri_dashes'] = FALSE;