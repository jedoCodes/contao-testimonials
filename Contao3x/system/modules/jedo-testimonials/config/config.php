<?php
/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * Config Testimonials
 *
 * @copyright  JEDO 2012
 * @author     Jens Doberenz <http://jensdoberenz.de>
 * @package    jedoTestimonials
 * @version    3.0.0
 */


// Content Elements
$GLOBALS['TL_CTE']['includes']['testimonials'] = 'ContentTestimonials';

// FrontEnd Module
$GLOBALS['FE_MOD']['application']['testimonials'] = 'ModuleTestimonials';

// BackEnd Module
if (!is_array($GLOBALS['BE_MOD']['jedoextensions'])) {
	array_insert($GLOBALS['BE_MOD'], 1, array('jedoextensions' => array()));
}
array_insert($GLOBALS['BE_MOD']['jedoextensions'], 1, array (
	'testimonials' => array (
		'tables'     			=> array('tl_testimonials'),
        		'importTestimonials'		=> array('tl_testimonials','ImportOldTestimonials'),
		'icon'       			=> 'system/modules/jedo-testimonials/assets/images/icon.gif',
		'stylesheet' 			=> 'system/modules/jedo-testimonials/assets/css/backend_default.css'
	)
));
