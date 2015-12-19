<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * AutoLoad Testimonials
 *
 * @copyright  JEDO 2012
 * @author     Jens Doberenz <http://jensdoberenz.de>
 * @package    jedoTestimonials
 * @version    3.0.0
 */

/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'jedoStyle\Testimonials',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Models
    'jedoStyle\Testimonials\TestimonialsModel'   => 'system/modules/jedo-testimonials/models/ModelTestimonials.php',

	// Modules
	'jedoStyle\Testimonials\ModuleTestimonials'  => 'system/modules/jedo-testimonials/modules/ModuleTestimonials.php',

	// Elements
	'jedoStyle\Testimonials\ContentTestimonials' => 'system/modules/jedo-testimonials/elements/ContentTestimonials.php',

	// Classes
	'jedoStyle\Testimonials\Testimonials'        => 'system/modules/jedo-testimonials/classes/Testimonials.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'ce_testimonials'    => 'system/modules/jedo-testimonials/templates',
	'testimonial_entry' => 'system/modules/jedo-testimonials/templates',
	'testimonials_form'  => 'system/modules/jedo-testimonials/templates',
	'testimonials_stats' => 'system/modules/jedo-testimonials/templates',
));
