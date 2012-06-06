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
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['jedoTestimonials'] = '{title_legend},name,headline,type;{testimonial_legend},jedoTM_order,perPage,jedoTM_moderate,jedoTM_bbcode,jedoTM_requireLogin,jedoTM_disableVote,jedoTM_disableCaptcha;{template_legend:hide},jedoTM_template;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';


/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['jedoTM_order'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['jedoTM_order'],
	'default'                 => 'ascending',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('ascending', 'descending'),
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['jedoTM_moderate'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['jedoTM_moderate'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['jedoTM_bbcode'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['jedoTM_bbcode'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['jedoTM_requireLogin'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['jedoTM_requireLogin'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_module']['fields']['jedoTM_disableCaptcha'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['jedoTM_disableCaptcha'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50')
);
$GLOBALS['TL_DCA']['tl_module']['fields']['jedoTM_disableVote'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['jedoTM_disableVote'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50')
);
$GLOBALS['TL_DCA']['tl_module']['fields']['jedoTM_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['jedoTM_template'],
	'default'                 => 'jedoTM_default',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_testimonials', 'getTestimonialsTemplates'),
	'eval'                    => array('tl_class'=>'w50')
);

/**
 * Class tl_module
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  jedo Webstudio 2005-2012
 * @author     Jens Doberenz <http://www.jedo-webstudio.com>
 * @package    jedoTestimonials
 */
class tl_module_testimonials extends Backend
{

	/**
	 * Return all navigation templates as array
	 * @param DataContainer
	 * @return array
	 */
	public function getTestimonialsTemplates(DataContainer $dc)
	{
		$intPid = $dc->activeRecord->pid;

		if ($this->Input->get('act') == 'overrideAll')
		{
			$intPid = $this->Input->get('id');
		}

		return $this->getTemplateGroup('tm_', $intPid);
	}
}

?>