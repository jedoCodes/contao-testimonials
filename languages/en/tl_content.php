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
 * Fields
 */
$GLOBALS['TL_LANG']['tl_content']['jedoTM_order']          = array('Sort order', 'Please choose the sort order.');
$GLOBALS['TL_LANG']['tl_content']['jedoTM_perPage']        = array('Items per page', 'The number of testimonials per page. Set to 0 to disable pagination.');
$GLOBALS['TL_LANG']['tl_content']['jedoTM_moderate']       = array('Moderate', 'Approve testimonials before they are published on the website.');
$GLOBALS['TL_LANG']['tl_content']['jedoTM_bbcode']         = array('Allow BBCode', 'Allow visitors to format their testimonials with BBCode.');
$GLOBALS['TL_LANG']['tl_content']['jedoTM_requireLogin']   = array('Require login to testimonial', 'Allow only authenticated users to create testimonials.');
$GLOBALS['TL_LANG']['tl_content']['jedoTM_disableCaptcha'] = array('Disable the security question', 'Here you can disable the security question (not recommended).');
$GLOBALS['TL_LANG']['tl_content']['jedoTM_template']       = array('Testimonials template', 'Here you can select the testimonials template.');
$GLOBALS['TL_LANG']['tl_content']['jedoTM_disableVote'] = array('Disable the voting', 'Here you can disable the vote.');
/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_content']['testimonials_legend'] = 'Testimonials settings';
?>