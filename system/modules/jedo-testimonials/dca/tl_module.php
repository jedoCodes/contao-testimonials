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
 * Add palette to tl_module
 */

$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'tm_addVote';
$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'tm_AvatarMode';

$GLOBALS['TL_DCA']['tl_module']['palettes']['testimonials'] 		= '{type_legend},name,headline,type,tm_design;{testimonials_legend},tm_order,tm_perPage,tm_moderate,tm_bbcode,tm_requireLogin,tm_disableCaptcha;{vote_legend},tm_addVote;{avatar_legend},tm_AvatarMode;{template_legend:hide},tm_template;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['testimonialscustom'] 	= '{type_legend},name,headline,type,tm_design;{testimonials_legend},tm_order,tm_perPage,tm_moderate,tm_bbcode,tm_requireLogin,tm_disableCaptcha;{vote_legend},tm_addVote;{avatar_legend},tm_AvatarMode,tm_AvatarSize,tm_DefaultAvatar;{template_legend:hide},tm_template;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['testimonialsavatar'] 	= '{type_legend},name,headline,type,tm_design;{testimonials_legend},tm_order,tm_perPage,tm_moderate,tm_bbcode,tm_requireLogin,tm_disableCaptcha;{vote_legend},tm_addVote;{avatar_legend},tm_AvatarMode,tm_AvatarSize,tm_DefaultAvatar;{template_legend:hide},tm_template;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['testimonialsgravatar'] 	= '{type_legend},name,headline,type,tm_design;{testimonials_legend},tm_order,tm_perPage,tm_moderate,tm_bbcode,tm_requireLogin,tm_disableCaptcha;{vote_legend},tm_addVote;{avatar_legend},tm_AvatarMode,tm_AvatarSize,tm_DefaultGravatar,tm_DefaultGravatarRating,tm_DefaultAvatar;{template_legend:hide},tm_template;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

$GLOBALS['TL_DCA']['tl_module']['subpalettes']['tm_addVote'] 	='tm_VoteField1Name,tm_enableVoteField1,tm_VoteField2Name,tm_enableVoteField2,tm_VoteField3Name,tm_enableVoteField3,tm_VoteField4Name,tm_enableVoteField4,tm_VoteField5Name,tm_enableVoteField5,tm_VoteField6Name,tm_enableVoteField6';

/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['tm_design'] = array
		(
			'label'                   	=> &$GLOBALS['TL_LANG']['tl_content']['tm_design'],
			'exclude'                 => true,
			'default'		=> 'custom',
			'inputType'              => 'select',
			'options'                 => array('light', 'dark', 'custom'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_content'],
			'eval'                    	=> array('tl_class'=>'w50'),
			'sql'                     	=> "varchar(32) NOT NULL default ''"
		);

$GLOBALS['TL_DCA']['tl_module']['fields']['tm_order'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['tm_order'],
	'default'                 => 'descending',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('ascending', 'descending'),
	'reference'               => &$GLOBALS['TL_LANG']['MSC'],
	'eval'                    => array('tl_class'=>'w50'),
	'sql'		=> "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['tm_perPage'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['tm_perPage'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50'),
	'sql'			=> "smallint(5) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['tm_moderate'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['tm_moderate'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'	=> "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['tm_bbcode'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['tm_bbcode'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'	=> "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['tm_disableCaptcha'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['tm_disableCaptcha'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'	=> "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['tm_requireLogin'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['tm_requireLogin'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'	=> "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['tm_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['tm_template'],
	'default'                 => 'testimonial_entrie',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_testimonials', 'getTestimonialsTemplates'),
	'eval'                    => array('tl_class'=>'w50'),
	'sql'			=> "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['tm_addVote'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['tm_addVote'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('submitOnChange'=>true),
	'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['tm_enableVoteField1'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['tm_enableVoteField1'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50 m12'),
	'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['tm_VoteField1Name'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['tm_VoteField1Name'],
	'exclude'                 => true,
	'default'		=> "Kreativität",
	'inputType'               => 'text',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['tm_enableVoteField2'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['tm_enableVoteField2'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50 m12'),
	'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['tm_VoteField2Name'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['tm_VoteField2Name'],
	'exclude'                 => true,
	'default'		=> "Beratungskompetenz",
	'inputType'               => 'text',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['tm_enableVoteField3'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['tm_enableVoteField3'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50 m12'),
	'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['tm_VoteField3Name'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['tm_VoteField3Name'],
	'exclude'                 => true,
	'default'		=> "Seriösität",
	'inputType'               => 'text',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['tm_enableVoteField4'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['tm_enableVoteField4'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50 m12'),
	'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['tm_VoteField4Name'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['tm_VoteField4Name'],
	'exclude'                 => true,
	'default'		=> "Projektmanagement",
	'inputType'               => 'text',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['tm_enableVoteField5'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['tm_enableVoteField5'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50 m12'),
	'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['tm_VoteField5Name'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['tm_VoteField5Name'],
	'exclude'                 => true,
	'default'		=> "Technische Kompetenz",
	'inputType'               => 'text',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['tm_enableVoteField6'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['tm_enableVoteField6'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50 m12'),
	'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['tm_VoteField6Name'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['tm_VoteField6Name'],
	'exclude'                 => true,
	'default'		=> "Preis/Leistung",
	'inputType'               => 'text',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['tm_AvatarMode'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['tm_AvatarMode'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('avatar','gravatar', 'custom'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('tl_class'=>'w50', 'submitOnChange'=>true,'includeBlankOption' => true, 'blankOptionLabel' => &$GLOBALS['TL_LANG']['tl_module']['tm_AvatarModeLabel']),
	'sql'			=> "varchar(32) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['tm_AvatarSize'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['tm_AvatarSize'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50'),
	'sql'			=> "varchar(32) NOT NULL default '100'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['tm_DefaultGravatar'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['tm_DefaultGravatar'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('mm', 'identicon', 'monsterid', 'wavatar', 'retro', 'custom'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('tl_class'=>'w50'),
	'sql'			=> "varchar(32) NOT NULL default 'mm'"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['tm_DefaultGravatarRating'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['tm_DefaultGRating'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('g', 'pg', 'r', 'x'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('tl_class'=>'w50'),
	'sql'			=> "varchar(32) NOT NULL default 'g'"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['tm_DefaultAvatar'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['tm_DefaultAvatar'],
	'exclude'                 => true,
	'inputType'               => 'fileTree',
	'eval'                    => array('fieldType'=>'radio', 'filesOnly'=>true, 'tl_class'=>'clr'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);



/**
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  JEDO 2012
 * @author      Jens Doberenz <http://jensdoberenz.de>
 * @package    jedoTestimonials
 */
class tl_module_testimonials extends Backend
{

	/**
	 * Return all testimonials templates as array
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

		// Get the page ID
		$objArticle = $this->Database->prepare("SELECT pid FROM tl_article WHERE id=?")
									 ->limit(1)
									 ->execute($intPid);

		// Inherit the page settings
		$objPage = $this->getPageDetails($objArticle->pid);

		// Get the theme ID
		$objLayout = LayoutModel::findByPk($objPage->layout);

		// Return all templates
		return $this->getTemplateGroup('testimonial_', $objLayout->pid);
	}
}
