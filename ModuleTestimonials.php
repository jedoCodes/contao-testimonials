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
 *
 * @copyright  jedo Webstudio 2005-2012
 * @author     Jens Doberenz <http://www.jedo-webstudio.com>
 * @package    jedoTestimonials
 */
class ModuleTestimonials extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_testimonials';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### TESTIMONIALS ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		$this->import('Testimonials');
		$objConfig = new stdClass();

		$objConfig->perPage = $this->jedoTM_perPage;
		$objConfig->order = $this->jedoTM_order;
		$objConfig->template = $this->jedoTM_template;
		$objConfig->requireLogin = $this->jedoTM_requireLogin;
		$objConfig->disableCaptcha = $this->jedoTM_disableCaptcha;
		$objConfig->disableVote = $this->jedoTM_disableVote;
		$objConfig->bbcode = $this->jedoTM_bbcode;
		$objConfig->moderate = $this->jedoTM_moderate;

		$this->Testimonials->addTestimonialsToTemplate($this->Template, $objConfig, $GLOBALS['TL_ADMIN_EMAIL']);
	}
}

?>