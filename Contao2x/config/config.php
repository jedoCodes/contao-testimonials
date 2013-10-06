<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  jedo Webstudio 2005-2012
 * @author     Jens Doberenz <http://www.jedo-webstudio.com>
 * @package    jedoTestimonials
 * @license    LGPL
 * @version 1.0.0
 */


/**
 * Add content element
 */
$GLOBALS['TL_CTE']['includes']['jedoTestimonials'] = 'ContentTestimonials';


/**
 * Front end modules
 */
$GLOBALS['FE_MOD']['application']['jedoTestimonials'] = 'ModuleTestimonials';


/**
 * Back end modules
 */
if (!is_array($GLOBALS['BE_MOD']['extensions']))
{
	array_insert($GLOBALS['BE_MOD'], 1, array('extensions' => array()));
}
array_insert($GLOBALS['BE_MOD']['extensions'], 0, array
(
	'jedoTestimonials' => array
	(
		'tables'     => array('tl_jedoTestimonials'),
		'icon'       => 'system/modules/jedoTestimonials/html/images/icon.gif',
		'stylesheet' => 'system/modules/jedoTestimonials/html/tm_be_style.css'
	)
));

?>