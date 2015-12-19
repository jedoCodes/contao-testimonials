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
 * Table tl_testimonials
 */
$GLOBALS['TL_DCA']['tl_testimonials'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'doNotCopyRecords'            => true,
		'switchToEdit'                => true,
		'onload_callback' => array
		(
			array('tl_testimonials', 'checkPermission')
		),
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
			)
		)

	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 2,
			'fields'                  => array('date DESC'),
			'flag'                    => 8,
			'panelLayout'             => 'filter;sort,search,limit'
		),
		'label' => array
		(
			'fields'                  => array('name'),
			'format'                  => '%s',
			'label_callback'          => array('tl_testimonials', 'listTestimonials')
		),
		'global_operations' => array
		(
			'importTestimonials' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_testimonials']['importOldEntries'],
				'href'                => 'key=importTestimonials',
				'class'               => 'header_theme_import',
				'attributes'          => 'onclick="Backend.getScrollOffset()"'
			),

			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_testimonials']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif',
				'button_callback'     => array('tl_testimonials', 'editTestimonial')
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_testimonials']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
				'button_callback'     => array('tl_testimonials', 'deleteTestimonial')
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_testimonials']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_testimonials', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_testimonials']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'			=> '{author_legend},name,email,company,url;{testimonials_legend},title,testimonial;{voting_legend:hidden},votefield1,votefield2,votefield3,votefield4,votefield5,votefield6;{security_legend:hidden},ip,date;{publish_legend},published'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'label'                   => array('ID'),
			'search'                  => true,
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'date' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_testimonials']['date'],
			'default'                 => time(),
			'exclude'                 => true,
			'filter'                  => true,
			'sorting'                 => true,
			'flag'                    => 8,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'disabled'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'ip' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_testimonials']['ip'],
			'default'                 => '127.0.0.1',
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('disabled'=>true,'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_testimonials']['name'],
			'exclude'                 => true,
			'sorting'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>64,'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'company' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_testimonials']['company'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>128,'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'email' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_testimonials']['email'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>128, 'rgxp'=>'email', 'decodeEntities'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'url' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_testimonials']['url'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>128, 'rgxp'=>'url', 'decodeEntities'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(128) NOT NULL default ''"
		),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_testimonials']['title'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>64),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),		
		'testimonial' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_testimonials']['testimonial'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('mandatory'=>true, 'rte'=>'tinyFlash'),
			'sql'                     => "mediumtext NULL"
		),
		'votefield1' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_testimonials']['votefield1'],
			'inputType'		=> 'select',
			'options'		=> array('0.0'=>'0.0','0.5'=>'0.5','1.0'=>'1.0','1.5'=>'1.5','2.0'=>'2.0','2.5'=>'2.5','3.0'=>'3.0','3.5'=>'3.5','4.0'=>'4.0','4.5'=>'4.5','5.0'=>'5.0'),
			'eval'			=> array('mandatory'=>true,'tl_class'=>'w50'),
			'sql'			=> "float NOT NULL default '-1'"
		),
		'votefield2' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_testimonials']['votefield2'],
			'inputType'		=> 'select',
			'options'		=> array('0.0'=>'0.0','0.5'=>'0.5','1.0'=>'1.0','1.5'=>'1.5','2.0'=>'2.0','2.5'=>'2.5','3.0'=>'3.0','3.5'=>'3.5','4.0'=>'4.0','4.5'=>'4.5','5.0'=>'5.0'),
			'eval'			=> array('mandatory'=>true,'tl_class'=>'w50'),
			'sql'			=> "float NOT NULL default '-1'"
		),
		'votefield3' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_testimonials']['votefield3'],
			'inputType'		=> 'select',
			'options'		=> array('0.0'=>'0.0','0.5'=>'0.5','1.0'=>'1.0','1.5'=>'1.5','2.0'=>'2.0','2.5'=>'2.5','3.0'=>'3.0','3.5'=>'3.5','4.0'=>'4.0','4.5'=>'4.5','5.0'=>'5.0'),
			'eval'			=> array('mandatory'=>true,'tl_class'=>'w50'),
			'sql'			=> "float NOT NULL default '-1'"
		),
		'votefield4' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_testimonials']['votefield4'],
			'inputType'		=> 'select',
			'options'		=> array('0.0'=>'0.0','0.5'=>'0.5','1.0'=>'1.0','1.5'=>'1.5','2.0'=>'2.0','2.5'=>'2.5','3.0'=>'3.0','3.5'=>'3.5','4.0'=>'4.0','4.5'=>'4.5','5.0'=>'5.0'),
			'eval'			=> array('mandatory'=>true,'tl_class'=>'w50'),
			'sql'			=> "float NOT NULL default '-1'"
		),
		'votefield5' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_testimonials']['votefield5'],
			'inputType'		=> 'select',
			'options'		=> array('0.0'=>'0.0','0.5'=>'0.5','1.0'=>'1.0','1.5'=>'1.5','2.0'=>'2.0','2.5'=>'2.5','3.0'=>'3.0','3.5'=>'3.5','4.0'=>'4.0','4.5'=>'4.5','5.0'=>'5.0'),
			'eval'			=> array('mandatory'=>true,'tl_class'=>'w50'),
			'sql'			=> "float NOT NULL default '-1'"
		),
		'votefield6' => array
		(
			'label'                     => &$GLOBALS['TL_LANG']['tl_testimonials']['votefield6'],
			'inputType'		=> 'select',
			'options'		=> array('0.0'=>'0.0','0.5'=>'0.5','1.0'=>'1.0','1.5'=>'1.5','2.0'=>'2.0','2.5'=>'2.5','3.0'=>'3.0','3.5'=>'3.5','4.0'=>'4.0','4.5'=>'4.5','5.0'=>'5.0'),
			'eval'			=> array('mandatory'=>true,'tl_class'=>'w50'),
			'sql'			=> "float NOT NULL default '-1'"
		),

		'votestotal' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_testimonials']['votestotal'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>64,'tl_class'=>'w50 wizard'),
			'save_callback' => array
			(
				array('tl_testimonials', 'generateTotalVote')
			),
			'load_callback' => array
			(
				array('tl_testimonials', 'generateTotalVote')
			),
			'sql'			=> "float NOT NULL default '-1'"
		),

		'ip' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_testimonials']['ip'],
			'default'                 => '127.0.0.1',
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('disabled'=>true,'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),

		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_testimonials']['published'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		)
	)
);


/**
 * Class tl_testimonials
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  jedo Webstudio 2005-2012
 * @author     Jens Doberenz <http://www.jedo-webstudio.com>
 * @package    jedoTestimonials
 */
class tl_testimonials extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}
	

	public function generateTotalVote($varValue, DataContainer $dc)
	{

		$Value = $dc->activeRecord->votefield1 + $dc->activeRecord->votefield2 + $dc->activeRecord->votefield3 + $dc->activeRecord->votefield4 + $dc->activeRecord->votefield5 + $dc->activeRecord->votefield6; 
		$totalvote = $Value / 6;
		$totalvote = number_format($totalvote,2);

		//Update the database - need this when editing a entrie
		$this->Database->prepare("UPDATE tl_testimonials SET tstamp=". time() .", votestotal='" . $totalvote . "' WHERE id=?")->execute($dc->id);


		return $totalvote;
	}

	/**
	 * Check permissions to edit table tl_testimonials
	 */
	public function checkPermission()
	{
		$this->import('BackendUser', 'User');
		
		//return for admin
		if($this->User->isAdmin) {
			return;
		}
		
		// Set root IDs
		if (!is_array($this->User->jedoTestimonials) || count($this->User->jedoTestimonials) < 1) {
			$root = array(0);
		} else {
			$root = $this->User->jedoTestimonials;
		}		
		
		// Check permissions to add archives
		if (!$this->User->hasAccess('create', 'jedoTestimonials')) {
			$GLOBALS['TL_DCA']['tl_testimonials']['config']['closed'] = true;
		} 
	}

	/**
	 * List a particular record
	 * @param array
	 * @return string
	 */
	public function listTestimonials($arrRow)
	{

		$key = $arrRow['published'] ? 'published' : 'unpublished';

		return '
<div class="testimonial_wrap">
<div class="cte_type ' . $key . '"><strong><a href="mailto:' . $arrRow['email'] . '" title="' . specialchars($arrRow['email']) . '">' . $arrRow['name'] . '</a></strong> - ' . $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $arrRow['date']) . '</div>
<div class="cte_type_vote"><div class="beVotes beContainer"><span class="beVotes  beStars" style="width:'. round(60/5*$arrRow['votestotal']).'px;"></span></div></div>
<div class="cte_type_title">' . specialchars($arrRow['title']) . '</div>
<div class="limit_height mark_links' . (!$GLOBALS['TL_CONFIG']['doNotCollapse'] ? ' h32' : '') . '">
' . $arrRow['testimonial'] . '
</div>
</div>' . "\n    ";
	}


	/**
	 * Return the edit comment button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function editTestimonial($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('edit', 'jedoTestimonials')) ? '<a href="'.$this->addToUrl($href.'&id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the delete comment button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function deleteTestimonial($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('delete', 'jedoTestimonials')) ? '<a href="'.$this->addToUrl($href.'&id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the "toggle visibility" button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen($this->Input->get('tid')))
		{
			$this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 1));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_testimonials::published', 'alexf'))
		{
			return '';
		}

		$href .= '&tid='.$row['id'].'&state='.($row['published'] ? '' : 1);

		if (!$row['published'])
		{
			$icon = 'invisible.gif';
		}		

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}


	/**
	 * Disable/enable a user group
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($intId, $blnVisible)
	{
		// Check permissions to edit
		$this->Input->setGet('id', $intId);
		$this->Input->setGet('act', 'toggle');
		$this->checkPermission();

		// Check permissions to publish
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_testimonials::published', 'alexf'))
		{
			$this->log('Not enough permissions to publish/unpublish comment ID "'.$intId.'"', 'tl_calendar_events toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$this->createInitialVersion('tl_testimonials', $intId);
	
		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_testimonials']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_testimonials']['fields']['published']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_testimonials SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);

		$this->createNewVersion('tl_testimonials', $intId);
	}



	public function ImportOldTestimonials() {

	if ($this->Input->get('key') != 'importTestimonials')
	{
		return '';
	}

	if ($this->Input->post('FORM_SUBMIT') == 'tl_testimonials_import')
	{
		$source = $this->Input->post('source', true);
			if ($source == 'oldTestimonials1') {
				$oldData =  $this->Database->prepare("SELECT * FROM `tl_jedoTestimonials` ORDER BY id ASC")->execute();
			}
			if ($source == 'oldTestimonials2') {
				$oldData =  $this->Database->prepare("SELECT * FROM `tl_jedotestimonials` ORDER BY id ASC")->execute();
			}

			if( $oldData->numRows > 0 ) {

				while ($oldData->next())
				{
					

					$values = array();

					$values['tstamp'] 		= $oldData->tstamp;
					$values['name'] 		= $oldData->name;
					$values['email'] 		= $oldData->email;
					$values['url'] 			= $oldData->url;
					$values['company'] 		= $oldData->company;
					$values['title'] 		= $oldData->title;
					$values['testimonial'] 	= $oldData->testimonial;

					$values['votefield1']	 	= $oldData->vote;
					$values['votefield2']	 	= $oldData->vote;
					$values['votefield3']	 	= $oldData->vote;
					$values['votefield4']	 	= $oldData->vote;
					$values['votefield5']	 	= $oldData->vote;
					$values['votefield6']	 	= $oldData->vote;
					$values[' 	votestotal']		= $oldData->vote;
					$values['published'] 	= $oldData->published;
					$values['date'] 		= $oldData->date;
					$values['ip'] 			= $oldData->ip;

					$insertdata = $this->Database->prepare("INSERT INTO `tl_testimonials` %s")->set($values)->execute()->insertdata;
				}
			} else {
				$_SESSION['TL_ERROR'][] = $GLOBALS['TL_LANG']['ERR']['all_fields'];
				$this->reload();
			}

		$this->redirect(str_replace('&key=importTestimonials', '', $this->Environment->request));

	}

		// Return the form
		return '
<div id="tl_buttons">
<a href="'.ampersand(str_replace('&key=importTestimonials', '', $this->Environment->request)).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'" accesskey="b">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>

<h2 class="sub_headline">'.$GLOBALS['TL_LANG']['tl_testimonials']['importOldEntries'][1].'</h2>
'.$this->getMessages().'
<form action="'.ampersand($this->Environment->request, true).'" id="tl_testimonials_import" class="tl_form" method="post">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="tl_testimonials_import">
<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">

<div class="tl_tbox block">
  <h3><label for="source">'.$GLOBALS['TL_LANG']['tl_testimonials']['source'][0].'</label></h3>
  <select name="source" id="source" class="tl_select" onfocus="Backend.getScrollOffset();">
    <option value="oldTestimonials1">'.$GLOBALS['TL_LANG']['tl_testimonials']['oldTestimonials1'].'</option>
    <option value="oldTestimonials1">'.$GLOBALS['TL_LANG']['tl_testimonials']['oldTestimonials2'].'</option>
  </select>'.(($GLOBALS['TL_LANG']['tl_testimonials']['source'][1] != '') ? '
  <p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['tl_testimonials']['source'][1].'</p>' : '').'

</div>
</div>
<div class="tl_formbody_submit">

<div class="tl_submit_container">
  <input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['tl_testimonials']['importOldEntries'][0]).'">
</div>

</div>
</form>';

	}
}
