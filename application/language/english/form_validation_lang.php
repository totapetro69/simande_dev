<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2015, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

$lang['form_validation_required'] 			= "Bagian {field} wajib diisi";
$lang['form_validation_isset']				= "Bagian {field} harus mempunyai nilai.";
$lang['form_validation_valid_email']		= "Bagian {field} harus berisi email yang sah.";
$lang['form_validation_valid_emails'] 		= "Bagian {field} harus berisi seluruh email yang sah.";
$lang['form_validation_valid_url'] 			= "Bagian {field} harus berisi URL yang sah.";
$lang['form_validation_valid_ip'] 			= "Bagian {field} harus berisi IP yang sah.";
$lang['form_validation_min_length']			= "Panjang {field} setidaknya paling sedikit {param} karakter.";
$lang['form_validation_max_length']			= "Panjang {field} tidak boleh melebihi {param} karakter.";
$lang['form_validation_exact_length']		= "Panjang {field} harus tepat {param} karakter.";
$lang['form_validation_alpha']				= "Bagian {field} hanya boleh berisi abjad.";
$lang['form_validation_alpha_numeric']		= "Bagian {field} hanya boleh berisi angka-abjad.";
$lang['form_validation_alpha_dash']			= "Bagian {field} hanya boleh berisi angka-abjad, garis bawah _ , dan alangan -.";
$lang['form_validation_numeric']			= "Bagian {field} harus berisi angka.";
$lang['form_validation_is_numeric']			= "Bagian {field} harus berisi angka.";
$lang['form_validation_integer']			= "Bagian {field} harus berisi bilangan bulat.";
$lang['form_validation_regex_match']		= "Bagian {field} tidak sesuai format";
$lang['form_validation_matches']			= "Bagian {field} tidak sama dengan bagian {param}.";
$lang['form_validation_is_unique'] 			= "Bagian {field} harus unik.";
$lang['form_validation_is_natural']			= "Bagian {field} harus berisi angka positif.";
$lang['form_validation_is_natural_no_zero']	= "Bagian {field} harus berisi angka lebih besar dari 0.";
$lang['form_validation_decimal']			= "Bagian {field} harus berisi angka desimal.";
$lang['form_validation_less_than']			= "Bagian {field} harus kurang dari {param}.";
$lang['form_validation_greater_than']		= "Bagian {field} harus lebih dari {param}.";
$lang['form_validation_date'] 				= "Bagian {field} harus menggunakan format tanggal.";
$lang['form_validation_datetime'] 			= "Bagian {field} harus menggunakan format tanggal dan waktu.";
