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
| example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
| https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
| $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
| $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
| $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples: my-controller/index -> my_controller/index
|   my-controller/my-method -> my_controller/my_method
*/
$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;

/*
| -------------------------------------------------------------------------
| Sample REST API Routes
| -------------------------------------------------------------------------
*/
$route['api/example/users/(:num)'] = 'api/example/users/id/$1'; // Example 4
$route['api/example/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api/example/users/id/$1/format/$3$4'; // Example 8


# TEST
$route['test']         = 'TestController/index';
$route['repair_detik'] = 'Dashboard/repair_detik';
# TEST

# DASHBOARD
$route['dashboard']    = 'Dashboard/index';
$route['card_a']       = 'Dashboard/card_a';
$route['card_b']       = 'Dashboard/card_b';
$route['card_c']       = 'Dashboard/card_c';
$route['card_a_modal'] = 'Dashboard/card_a_modal';
$route['card_b_modal'] = 'Dashboard/card_b_modal';
$route['card_c_modal'] = 'Dashboard/card_c_modal';
# DASHBOARD

# CABANG
$route['list_cabang']    = 'setup/setup_cabang';
$route['tambah_cabang']  = 'setup/tambah_cabang';
$route['store_cabang']   = 'setup/store_cabang';
$route['edit_cabang']    = 'setup/edit_cabang';
$route['update_cabang']  = 'setup/update_cabang';
$route['destroy_cabang'] = 'setup/destroy_cabang';
# END CABANG

# PARAMETER
$route['list_parameter']               = 'parameter/list_parameter';
$route['edit_parameter/(:any)/(:num)'] = 'parameter/edit_parameter/$1/$2';
$route['update_parameter']             = 'parameter/update_parameter';
$route['destroy_parameter']            = 'parameter/destroy_parameter';
# END PARAMETER

# USER
$route['list_user']            = 'setup/setup_user';
$route['tambah_user']          = 'setup/add_user';
$route['list_karyawan_branch'] = 'setup/list_karyawan_branch';
$route['detail_karyawan_nik']  = 'setup/detail_karyawan_nik'; 
$route['edit_user']            = 'setup/edit_user';
$route['update_user']          = 'setup/update_user';
$route['destroy_user']         = 'setup/destroy_user';
$route['update_pass']          = 'setup/update_pass';
$route['update_tipe']          = 'setup/update_tipe';
# END USER

# KARYAWAN
$route['list_karyawan']          = 'karyawan/index';
$route['store_karyawan']         = 'karyawan/store_karyawan';
$route['edit_karyawan/(:num)']   = 'karyawan/edit_karyawan/$1';
$route['update_karyawan']        = 'karyawan/update_karyawan';
$route['destroy_karyawan']       = 'karyawan/destroy_karyawan';
$route['detail_karyawan/(:any)'] = 'karyawan/detail_karyawan/$1';
# END KARYAWAN

# LAPORAN
$route['get_rekap_bulanan']     = 'Laporan/get_rekap_bulanan';
# END LAPORAN

# CUTI & IZIN
$route['list_cuti_cabang']    = 'CutiController/index';
$route['get_list_regis_cuti'] = 'CutiController/list_regis_cuti';

$route['list_cuti_pending']   = 'CutiController/list_pending';
$route['list_cuti_terima']    = 'CutiController/list_terima';

$route['create_cuti']         = 'CutiController/create';
$route['get_karyawan_cabang'] = 'CutiController/get_karyawan_cabang';
$route['get_cuti_remain']     = 'CutiController/get_cuti_remain';
$route['store_cuti']          = 'CutiController/store2';
$route['terima_cuti']         = 'CutiController/terima';
$route['tolak_cuti']          = 'CutiController/tolak2';
# END CUTI & IZIN

# MUTASI JABATAN
$route['store_jabatan'] = 'Mutasi/store_jabatan';
$route['update_resign'] = 'Resign/update_resign';
# END MUTASI JABATAN

# HALAMAN DOA
$route['doa'] = 'DoaController/index';
# END HALAMAN DOA

# LEMBUR
$route['lembur']        = 'LemburController/index';
$route['lembur_data']   = 'LemburController/data';
$route['lembur_create'] = 'LemburController/create';
$route['lembur_store']  = 'LemburController/store';
$route['lembur_destroy']  = 'LemburController/destroy';
# END LEMBUR

$route['absensi/hitung_array_import_fp']  = 'Absensi/hitung_array_import_fp';
$route['absensi/import/(:any)']  = 'Absensi/import/$1';