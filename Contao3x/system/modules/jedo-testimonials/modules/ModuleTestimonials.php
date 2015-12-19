<?php
/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @copyright  JEDO 2012
 * @author      Jens Doberenz <http://jensdoberenz.de>
 * @package    jedoTestimonials
 * @version     3.0.0
 */

/**
 * Run in a custom namespace, so the class can be replaced
 */

namespace jedoStyle\Testimonials;


class ModuleTestimonials extends \Module
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
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### JEDO TESTIMONIALS ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}
		if($this->tm_DefaultAvatar)
		{
			if (!is_numeric($this->tm_DefaultAvatar))
			{
				return '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
			}
				$objFile = \FilesModel::findByPk($this->tm_DefaultAvatar);

			if ($objFile === null || !is_file(TL_ROOT . '/' . $objFile->path))
			{
				return '';
			}
			$this->tm_DefaultAvatar = $objFile->path;
		}
		return parent::generate();
	}


	/**
	 * Generate the module
	 */
	protected function compile()
	{
		$this->import('Testimonials');
		$objConfig = new \stdClass();

		$objConfig->perPage = $this->tm_perPage;
		$objConfig->order = $this->tm_order;
		$objConfig->template = $this->tm_template;
		$objConfig->requireLogin = $this->tm_requireLogin;
		$objConfig->disableCaptcha = $this->tm_disableCaptcha;
		$objConfig->bbcode = $this->tm_bbcode;
		$objConfig->moderate = $this->tm_moderate;
		$objConfig->addAvatar = $this->tm_addAvatar;
		$objConfig->AvatarSize = $this->tm_AvatarSize;
		$objConfig->AvatarMode = $this->tm_AvatarMode;
		$objConfig->DefaultAvatar = $this->tm_DefaultAvatar;
		$objConfig->DefaultGravatar = $this->tm_DefaultGravatar;
		$objConfig->DefaultGravatarRating = $this->tm_DefaultGravatarRating;
		$objConfig->addVote = $this->tm_addVote;
		$objConfig->design = $this->tm_design;
		$objConfig->enableVoteField1 = $this->tm_enableVoteField1;
		$objConfig->enableVoteField2 = $this->tm_enableVoteField2;
		$objConfig->enableVoteField3 = $this->tm_enableVoteField3;
		$objConfig->enableVoteField4 = $this->tm_enableVoteField4;
		$objConfig->enableVoteField5 = $this->tm_enableVoteField5;
		$objConfig->enableVoteField6 = $this->tm_enableVoteField6;
		$objConfig->VoteField1Name = $this->tm_VoteField1Name;
		$objConfig->VoteField2Name = $this->tm_VoteField2Name;
		$objConfig->VoteField3Name = $this->tm_VoteField3Name;
		$objConfig->VoteField4Name = $this->tm_VoteField4Name;
		$objConfig->VoteField5Name = $this->tm_VoteField5Name;
		$objConfig->VoteField6Name = $this->tm_VoteField6Name;

		$this->Testimonials->addTestimonialsToTemplate($this->Template, $objConfig, $this->id, $GLOBALS['TL_ADMIN_EMAIL']);
	}
}