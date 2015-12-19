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

$GLOBALS['TL_LANG']['tl_jedoTestimonials']['date']      = array('Date', 'Please enter the testimonal date.');
$GLOBALS['TL_LANG']['tl_jedoTestimonials']['ip']      = array('IP address', 'This IP can not be changed.');
$GLOBALS['TL_LANG']['tl_jedoTestimonials']['name']      = array('Author', 'Please enter the author\'s name.');
$GLOBALS['TL_LANG']['tl_jedoTestimonials']['email']     = array('E-mail address', 'The e-mail address will not be published.');
$GLOBALS['TL_LANG']['tl_jedoTestimonials']['url']   = array('Website', 'Here you can enter a website address.');
$GLOBALS['TL_LANG']['tl_jedoTestimonials']['testimonial']   = array('Testimonial', 'Please enter the testimonial.');
$GLOBALS['TL_LANG']['tl_jedoTestimonials']['vote']  = array('Voting', 'Here you can enter your voting (higher is better).');
$GLOBALS['TL_LANG']['tl_jedoTestimonials']['published'] = array('Publish comment', 'Make the comment publicly visible on the website.');
$GLOBALS['TL_LANG']['tl_jedoTestimonials']['company'] = array('Company', 'Please enter the company\'s name.');
$GLOBALS['TL_LANG']['tl_jedoTestimonials']['title'] = array('Title', 'Please enter a title.');
/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_jedoTestimonials']['author_legend']  = 'Autor';
$GLOBALS['TL_LANG']['tl_jedoTestimonials']['testimonials_legend'] = 'Testimonials';
$GLOBALS['TL_LANG']['tl_jedoTestimonials']['publish_legend'] = 'Publish settings';
$GLOBALS['TL_LANG']['tl_jedoTestimonials']['security_legend'] = 'Security settings';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_jedoTestimonials']['approved']          = 'Approved';
$GLOBALS['TL_LANG']['tl_jedoTestimonials']['pending']            = 'Awaiting moderation';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_jedoTestimonials']['show']   = array('Testimonial details', 'Show the details of testimonial ID %s');
$GLOBALS['TL_LANG']['tl_jedoTestimonials']['edit']   = array('Edit testimonial', 'Edit testimonial ID %s');
$GLOBALS['TL_LANG']['tl_jedoTestimonials']['delete'] = array('Delete testimonial', 'Delete testimonial ID %s');
$GLOBALS['TL_LANG']['tl_jedoTestimonials']['toggle'] = array('Publish/unpublish testimonial', 'Publish/unpublish testimonial ID %s');
$GLOBALS['TL_LANG']['tl_jedoTestimonials']['new']        = array('Add testimonial', 'Add a new testimonial.');
?>