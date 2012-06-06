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
 * @version	   2.5.0
 */


/**
 * Table tl_jedoTestimonials
 */
$GLOBALS['TL_DCA']['tl_jedoTestimonials'] = array
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
			array('tl_jedoTestimonials', 'checkPermission')
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
			'label_callback'          => array('tl_jedoTestimonials', 'listComments')
		),
		'global_operations' => array
		(
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
				'label'               => &$GLOBALS['TL_LANG']['tl_jedoTestimonials']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif',
				'button_callback'     => array('tl_jedoTestimonials', 'editComment')
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_jedoTestimonials']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
				'button_callback'     => array('tl_jedoTestimonials', 'deleteComment')
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_jedoTestimonials']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_jedoTestimonials', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_jedoTestimonials']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{author_legend},name,email,company,url;{testimonials_legend},title,vote,testimonial;{security_legend:hidden},ip,date;{publish_legend},published'
	),

	// Fields
	'fields' => array
	(
		'date' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_jedoTestimonials']['date'],
			'default'                 => time(),
			'exclude'                 => true,
			'filter'                  => true,
			'sorting'                 => true,
			'flag'                    => 8,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'disabled'=>true, 'tl_class'=>'w50 wizard')
		),
		'ip' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_jedoTestimonials']['ip'],
			'default'                 => '127.0.0.1',
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('disabled'=>true,'tl_class'=>'w50 wizard')
		),
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_jedoTestimonials']['name'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>64,'tl_class'=>'w50 wizard')
		),
		'company' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_jedoTestimonials']['company'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>128,'tl_class'=>'w50 wizard')
		),
		'email' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_jedoTestimonials']['email'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>128, 'rgxp'=>'email', 'decodeEntities'=>true, 'tl_class'=>'w50')
		),
		'url' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_jedoTestimonials']['url'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>128, 'rgxp'=>'url', 'decodeEntities'=>true, 'tl_class'=>'w50')
		),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_jedoTestimonials']['title'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>64, 'tl_class'=>'w50 wizard')
		),		
		'testimonial' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_jedoTestimonials']['testimonial'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('mandatory'=>true, 'rte'=>'tinyFlash')
		),
		'vote' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_jedoTestimonials']['vote'],
			'default'                 => '0',
			'exclude'                 => true,
			'sorting'                 => true,
			'inputType'               => 'select',
			'options'                 => array('0','1','2','3','4','5'),
			'eval'                    => array('tl_class'=>'w50')
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_jedoTestimonials']['published'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true)
		)
	)
);


/**
 * Class tl_jedoTestimonials
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  jedo Webstudio 2005-2012
 * @author     Jens Doberenz <http://www.jedo-webstudio.com>
 * @package    jedoTestimonials
 */
class tl_jedoTestimonials extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}
	

	/**
	 * Check permissions to edit table tl_jedoTestimonials
	 */
	public function checkPermission()
	{
		$this->import('BackendUser', 'User');
		
		//return for admin
		if($this->User->isAdmin) {
			return;
		}
		
		// Set root IDs
		if (!is_array($this->User->jedoTestimonials) || count($this->User->culex_guestbooks) < 1) {
			$root = array(0);
		} else {
			$root = $this->User->culex_guestbooks;
		}		
		$GLOBALS['TL_DCA']['tl_culex_guestbook_lists']['list']['sorting']['root'] = $root;
		
		// Check permissions to add archives
		if (!$this->User->hasAccess('create', 'jedoTestimonials')) {
			$GLOBALS['TL_DCA']['tl_jedoTestimonials']['config']['closed'] = true;
		} 
	}

	/**
	 * List a particular record
	 * @param array
	 * @return string
	 */
	public function listComments($arrRow)
	{

		$key = $arrRow['published'] ? 'published' : 'unpublished';

		return '
<div class="testimonial_wrap">
<div class="cte_type ' . $key . '"><strong><a href="mailto:' . $arrRow['email'] . '" title="' . specialchars($arrRow['email']) . '">' . $arrRow['name'] . '</a></strong>' . (strlen($arrRow['vote']) ? ' (<span class="vote star' . specialchars($arrRow['vote']) . '"></span>)' : '') . ' - ' . $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $arrRow['date']) . '</div>
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
	public function editComment($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('edit', 'jedoTestimonials')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
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
	public function deleteComment($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || $this->User->hasAccess('delete', 'jedoTestimonials')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
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
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_jedoTestimonials::published', 'alexf'))
		{
			return '';
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

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
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_jedoTestimonials::published', 'alexf'))
		{
			$this->log('Not enough permissions to publish/unpublish comment ID "'.$intId.'"', 'tl_calendar_events toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$this->createInitialVersion('tl_jedoTestimonials', $intId);
	
		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_jedoTestimonials']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_jedoTestimonials']['fields']['published']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_jedoTestimonials SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);

		$this->createNewVersion('tl_jedoTestimonials', $intId);
	}
}

?>