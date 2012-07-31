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
 * Class Testimonials
 *
 * @copyright  jedo Webstudio 2005-2012
 * @author     Jens Doberenz <http://www.jedo-webstudio.com>
 * @package    jedoTestimonials
 */
class Testimonials extends Frontend
{

	/**
	 * Add testimonials to a template
	 * @param FrontendTemplate
	 * @param stdClass
	 * @param string
	 * @param integer
	 * @param array
	 */
	public function addTestimonialsToTemplate(FrontendTemplate $objTemplate, stdClass $objConfig, $arrNotifies)
	{
		global $objPage;
		$this->import('String');

		$limit = null;
		$arrTestimonials = array();

		// Pagination
		if ($objConfig->perPage > 0)
		{
			// Get the total number of testimonials
			$objTotal = $this->Database->prepare("SELECT COUNT(*) AS count FROM tl_jedoTestimonials WHERE" . (!BE_USER_LOGGED_IN ? " published=1" : ""))
									   ->execute($this->id);

			$total = $objTotal->count;

			// Get the current page
			$page = $this->Input->get('page') ? $this->Input->get('page') : 1;



			// Set limit and offset
			$limit = $objConfig->perPage;
			$offset = ($page - 1) * $objConfig->perPage;
 
			// Initialize the pagination menu
			$objPagination = new Pagination($objTotal->count, $objConfig->perPage);
			$objTemplate->pagination = $objPagination->generate("\n  ");
			$objTemplate->totalvotes = $this->get_votestars($objTotal->totalvotes, $objTotal->count, $bigstars = true);
		}
		if (!$objConfig->disableVote)
		{
			$objTotal = $this->Database->prepare("SELECT COUNT(*) AS count, AVG(vote) as totalvotes  FROM tl_jedoTestimonials WHERE" . (!BE_USER_LOGGED_IN ? " published=1" : ""))
									   ->execute($this->id);

			$objTemplate->totalvotes = $this->get_votestars($objTotal->totalvotes, $objTotal->count, $bigstars = true);
		}
		// Get all published testimonials
		$objTestimonialsStmt = $this->Database->prepare("SELECT * FROM tl_jedoTestimonials WHERE" . (!BE_USER_LOGGED_IN ? " published=1" : "") . " ORDER BY date" . (($objConfig->order == 'descending') ? " DESC" : ""));

		if ($limit)
		{
			$objTestimonialsStmt->limit($limit, $offset);
		}

		$objTestimonials = $objTestimonialsStmt->execute($this->id);
		$total = $objTestimonials->numRows;

		if ($total > 0)
		{
			$count = 0;

			if ($objConfig->template == '')
			{
				$objConfig->template = 'tm_default';
			}

			$objPartial = new FrontendTemplate($objConfig->template);

			while ($objTestimonials->next())
			{
				$objPartial->setData($objTestimonials->row());

				// Clean the RTE output
				if ($objPage->outputFormat == 'xhtml')
				{
					$objTestimonials->testimonial = $this->String->toXhtml($objTestimonials->testimonial );
				}
				else
				{
					$objTestimonials->testimonial = $this->String->toHtml5($objTestimonials->testimonial );
				}

				$objPartial->testimonial = trim(str_replace(array('{{', '}}'), array('{{', '}}'), $objTestimonials->testimonial ));

				$objPartial->datim = $this->parseDate($objPage->datimFormat, $objTestimonials->date);
				$objPartial->date = $this->parseDate('l, d. M Y', $objTestimonials->date);
				$objPartial->class = (($count < 1) ? ' first' : '') . (($count >= ($total - 1)) ? ' last' : '') . (($count % 2 == 0) ? ' even' : ' odd');
				$objPartial->by = $GLOBALS['TL_LANG']['MSC']['testimonials_by'];
				$objPartial->id = 'c' . $objTestimonials->id;
				$objPartial->timestamp = $objTestimonials->date;
				$objPartial->datetime = date('Y-m-d\TH:i:sP', $objTestimonials->date);
				$objPartial->avatar = $this->get_gravatar($objTestimonials->email, $s = 100);
				$objPartial->titel =  $objTestimonials->title;
				$objPartial->vote = $this->get_votestars($objTestimonials->vote, 0,$bigstars = false);
				$objPartial->novoting = $objConfig->disableVote;

				$arrTestimonials[] = $objPartial->parse();
				++$count;
			}
		}
		$objTemplate->Testimonials = $arrTestimonials;
		$objTemplate->novoting = $objConfig->disableVote;
		$objTemplate->name = $GLOBALS['TL_LANG']['MSC']['tm_name'];
		$objTemplate->email = $GLOBALS['TL_LANG']['MSC']['tm_email'];
		$objTemplate->url = $GLOBALS['TL_LANG']['MSC']['tm_website'];
		$objTemplate->company = $GLOBALS['TL_LANG']['MSC']['tm_company'];
		$objTemplate->title = $GLOBALS['TL_LANG']['MSC']['tm_title'];
		$objTemplate->vote = $GLOBALS['TL_LANG']['MSC']['tm_vote'];
		$objTemplate->TestimonialsTotal = $limit ? $objTotal->count : $total;
		if (!$objConfig->disableVote)
		{
			$objTemplate->showVote = true;
		}
		// Get the front end user object
		$this->import('FrontendUser', 'User');

		// Access control
		if ($objConfig->requireLogin && !BE_USER_LOGGED_IN && !FE_USER_LOGGED_IN)
		{
			$objTemplate->requireLogin = true;
			return;
		}

		if (BE_USER_LOGGED_IN || FE_USER_LOGGED_IN)
		{
					$value_email = $this->User->email;
					$value_name = trim($this->User->firstname . ' ' . $this->User->lastname);
					$value_company = $GLOBALS['TL_LANG']['MSC']['tm_company'];
					$value_url = $GLOBALS['TL_LANG']['MSC']['tm_url'];
					$value_title = $GLOBALS['TL_LANG']['MSC']['tm_title'];
					$value_testimonial = $GLOBALS['TL_LANG']['MSC']['tm_testimonial'];

		} else {
					$value_email = $GLOBALS['TL_LANG']['MSC']['tm_email'];
					$value_name = $GLOBALS['TL_LANG']['MSC']['tm_name'];
					$value_company = $GLOBALS['TL_LANG']['MSC']['tm_company'];
					$value_url = $GLOBALS['TL_LANG']['MSC']['tm_url'];
					$value_title = $GLOBALS['TL_LANG']['MSC']['tm_title'];
					$value_testimonial = $GLOBALS['TL_LANG']['MSC']['tm_testimonial'];
		}

		// Form fields
		$arrFields = array
		(
			'name' => array
			(
				'name' => 'name',
				'label' => $GLOBALS['TL_LANG']['MSC']['tm_name'],
				'value' => $value_name,
				'inputType' => 'text',
				'eval' => array('mandatory'=>true, 'maxlength'=>64, 'placeholder'=> $GLOBALS['TL_LANG']['MSC']['tm_name'], 'onblur' => "if(this.value == '') { this.value='$value_name'}", 'onfocus' => "if (this.value == '$value_name') {this.value=''}")
			),
			'email' => array
			(
				'name' => 'email',
				'label' => $GLOBALS['TL_LANG']['MSC']['tm_email'],
				'value' => $value_email,
				'inputType' => 'text',
				'eval' => array('rgxp'=>'email', 'mandatory'=>true, 'maxlength'=>128, 'decodeEntities'=>true, 'placeholder'=> $GLOBALS['TL_LANG']['MSC']['tm_email'], 'onblur' => "if(this.value == '') { this.value='$value_email'}", 'onfocus' => "if (this.value == '$value_email') {this.value=''}")
			),
			'url' => array
			(
				'name' => 'url',
				'label' => $GLOBALS['TL_LANG']['MSC']['tm_url'],
				'value' => $value_url,
				'inputType' => 'text',
				'eval' => array('rgxp'=>'url', 'maxlength'=>128, 'decodeEntities'=>true, 'placeholder'=> $GLOBALS['TL_LANG']['MSC']['tm_url'], 'onblur' => "if(this.value == '') { this.value='$value_url'}", 'onfocus' => "if (this.value == '$value_url') {this.value=''}")
			),
			'company' => array
			(
				'name' => 'company',
				'label' => $GLOBALS['TL_LANG']['MSC']['tm_company'],
				'value' => $value_company,
				'inputType' => 'text',
				'eval' => array('maxlength'=>128, 'placeholder'=> $GLOBALS['TL_LANG']['MSC']['tm_company'], 'onblur' => "if(this.value == '') { this.value='$value_company'}", 'onfocus' => "if (this.value == '$value_company') {this.value=''}")
			),
			'title' => array
			(
				'name' => 'title',
				'label' => $GLOBALS['TL_LANG']['MSC']['tm_title'],
				'inputType' => 'text',
				'value' => $value_title,
				'eval' => array('maxlength'=>128, 'placeholder'=> $GLOBALS['TL_LANG']['MSC']['tm_title'], 'onblur' => "if(this.value == '') { this.value='$value_title'}", 'onfocus' => "if (this.value == '$value_title') {this.value=''}")
			)
		);

		if (!$objConfig->disableVote)
		{
			$arrFields['vote'] = array
			(
				'name' => 'vote',
				'label' => $GLOBALS['TL_LANG']['MSC']['tm_vote'],
				'inputType' => 'select',
				'default' => '0',			
				'options' => array('0','1','2','3','4','5'),
				'reference' => &$GLOBALS['TL_LANG']['MSC']['tm_rating'],
			);
		}

		// Captcha
		if (!$objConfig->disableCaptcha)
		{
			$arrFields['captcha'] = array
			(
				'name' => 'captcha',
				'inputType' => 'captcha',
				'eval' => array('mandatory'=>true)
			);
		}

		// Testimonial field
		$arrFields['testimonial'] = array
		(
			'name' => 'testimonial',
			'label' => $GLOBALS['TL_LANG']['MSC']['tm_testimonial'],
			'inputType' => 'textarea',
			'value' => $value_testimonial,
			'eval' => array('mandatory'=>true, 'rows'=>15, 'cols'=>40, 'preserveTags'=>true, 'placeholder'=> $GLOBALS['TL_LANG']['MSC']['tm_testimonial'], 'onblur' => "if(this.value == '') { this.value='$value_testimonial'}", 'onfocus' => "if (this.value == '$value_testimonial') {this.value=''}")
		);

		$doNotSubmit = false;
		$arrWidgets = array();
		$strFormId = "jedoTestimonials";

		// Initialize widgets
		foreach ($arrFields as $arrField)
		{
			$strClass = $GLOBALS['TL_FFL'][$arrField['inputType']];

			// Continue if the class is not defined
			if (!$this->classFileExists($strClass))
			{
				continue;
			}

			$arrField['eval']['required'] = $arrField['eval']['mandatory'];
			$objWidget = new $strClass($this->prepareForWidget($arrField, $arrField['name'], $arrField['value']));

			// Validate the widget
			if ($this->Input->post('FORM_SUBMIT') == $strFormId)
			{
				$objWidget->validate();

				if ($objWidget->hasErrors())
				{
					$doNotSubmit = true;
				}
			}

			$arrWidgets[$arrField['name']] = $objWidget;
		}

		$objTemplate->fields = $arrWidgets;
		$objTemplate->submit = $GLOBALS['TL_LANG']['MSC']['tm_submit'];
		$objTemplate->action = ampersand($this->Environment->request);
		$objTemplate->messages = ''; // Backwards compatibility
		$objTemplate->hasError = $doNotSubmit;
		$objTemplate->formId = $strFormId;

		// Do not index or cache the page with the confirmation message
		if ($_SESSION['TL_TESTIMONIAL_ADDED'] )
		{
			global $objPage;
			$objPage->noSearch = 1;
			$objPage->cache = 0;

			$objTemplate->confirm = $GLOBALS['TL_LANG']['MSC']['tm_confirm'];
			$_SESSION['TL_TESTIMONIAL_ADDED']  = false;
		}

		// Add the testimonial
		if ($this->Input->post('FORM_SUBMIT') == $strFormId && !$doNotSubmit)
		{
			$this->import('String');
			$strWebsite = $arrWidgets['url']->value;

			// Add http:// to the website
			if (($strWebsite != '') && !preg_match('@^(https?://|ftp://|mailto:|#)@i', $strWebsite))
			{
				$strWebsite = 'http://' . $strWebsite;
			}

			// Do not parse any tags in the testimonial
			$strTesimonial = htmlspecialchars(trim($arrWidgets['testimonial']->value));
			$strTesimonial = str_replace(array('&', '<', '>'), array('&', '<', '>'), $strTesimonial);

			// Remove multiple line feeds
			$strTesimonial = preg_replace('@\n\n+@', "\n\n", $strTesimonial);

			// Parse BBCode
			if ($objConfig->bbcode)
			{
				$strTesimonial = $this->parseBbCode($strTesimonial);
			}

			// Prevent cross-site request forgeries
			$strTesimonial = preg_replace('/(href|src|on[a-z]+)="[^"]*(contao\/main\.php|typolight\/main\.php|javascript|vbscri?pt|script|alert|document|cookie|window)[^"]*"+/i', '$1="#"', $strTesimonial);

			$time = time();

			// Prepare the record
			$arrSet = array
			(
				'tstamp' => $time,
				'name' => $arrWidgets['name']->value,
				'email' => $arrWidgets['email']->value,
				'company' => $arrWidgets['company']->value,
				'title' => $arrWidgets['title']->value,
				'vote' => $arrWidgets['vote']->value,
				'url' => $strWebsite,
				'testimonial' => $this->convertLineFeeds($strTesimonial),
				'ip' => $this->anonymizeIp($this->Environment->ip),
				'date' => $time,
				'published' => ($objConfig->moderate ? '' : 1)
			);

			$insertId = $this->Database->prepare("INSERT INTO tl_jedoTestimonials %s")->set($arrSet)->execute()->insertId;

			// Notification
			$objEmail = new Email();

			$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
			$objEmail->fromName = $GLOBALS['TL_ADMIN_NAME'];
			$objEmail->subject = sprintf($GLOBALS['TL_LANG']['MSC']['tm_subject'], $this->Environment->host);

			// Convert the testimonial to plain text
			$strTesimonial = strip_tags($strTesimonial);
			$strTesimonial = $this->String->decodeEntities($strTesimonial);
			$strTesimonial = str_replace(array('&amp;', '&lt;', '&gt;'), array('[&]', '[lt]', '[gt]'), $strTesimonial);

			// Add testimonial details
			$objEmail->text = sprintf($GLOBALS['TL_LANG']['MSC']['tm_message'],
									  $arrSet['name'] . ' (' . $arrSet['email'] . ')',
									  $strTesimonial,
									  $this->Environment->base . $this->Environment->request,
									  $this->Environment->base . 'contao/main.php?do=jedoTestimonials&act=edit&id=' . $insertId);

			// Do not send notifications twice
			if (is_array($arrNotifies))
			{
				$arrNotifies = array_unique($arrNotifies);
			}

			$objEmail->sendTo($arrNotifies);

			// Pending for approval
			if ($objConfig->moderate)
			{
				$_SESSION['TL_TESTIMONIAL_ADDED']  = true;
			}

			$this->reload();
		}
	}


	/**
	 * Replace bbcode and return the HTML string
	 * 
	 * Supports the following tags:
	 * - [b][/b] bold
	 * - [i][/i] italic
	 * - [u][/u] underline
	 * - [img][/img]
	 * - [code][/code]
	 * - [color=#ff0000][/color]
	 * - [quote][/quote]
	 * - [quote=tim][/quote]
	 * - [url][/url]
	 * - [url=http://][/url]
	 * - [email][/email]
	 * - [email=name@domain.com][/email]
	 * @param string
	 * @return string
	 */
	public function parseBbCode($strTesimonial)
	{
		$arrSearch = array
		(
			'@\[b\](.*)\[/b\]@Uis',
			'@\[i\](.*)\[/i\]@Uis',
			'@\[u\](.*)\[/u\]@Uis',
			'@\s*\[code\](.*)\[/code\]\s*@Uis',
			'@\[color=([^\]" ]+)\](.*)\[/color\]@Uis',
			'@\s*\[quote\](.*)\[/quote\]\s*@Uis',
			'@\s*\[quote=([^\]]+)\](.*)\[/quote\]\s*@Uis', 
			'@\[img\]\s*([^\[" ]+\.(jpe?g|png|gif|bmp|tiff?|ico))\s*\[/img\]@i',
			'@\[url\]\s*([^\[" ]+)\s*\[/url\]@i',
			'@\[url=([^\]" ]+)\](.*)\[/url\]@Uis',
			'@\[email\]\s*([^\[" ]+)\s*\[/email\]@i',
			'@\[email=([^\]" ]+)\](.*)\[/email\]@Uis',
			'@href="(([a-z0-9]+\.)*[a-z0-9]+\.([a-z]{2}|asia|biz|com|info|name|net|org|tel)(/|"))@i'
		);

		$arrReplace = array
		(
			'<strong>$1</strong>',
			'<em>$1</em>',
			'<span style="text-decoration:underline">$1</span>',
			"\n\n" . '<div class="code"><p>'. $GLOBALS['TL_LANG']['MSC']['tm_code'] .'</p><pre>$1</pre></div>' . "\n\n",
			'<span style="color:$1">$2</span>',
			"\n\n" . '<div class="quote">$1</div>' . "\n\n",
			"\n\n" . '<div class="quote"><p>'. sprintf($GLOBALS['TL_LANG']['MSC']['tm_quote'], '$1') .'</p>$2</div>' . "\n\n",
			'<img src="$1" alt="" />',
			'<a href="$1">$1</a>',
			'<a href="$1">$2</a>',
			'<a href="mailto:$1">$1</a>',
			'<a href="mailto:$1">$2</a>',
			'href="http://$1'
		);

		$strTesimonial = preg_replace($arrSearch, $arrReplace, $strTesimonial);

		// Encode e-mail addresses
		if (strpos($strTesimonial, 'mailto:') !== false)
		{
			$strTesimonial = $this->String->encodeEmail($strTesimonial);
		}

		return $strTesimonial;
	}


	/**
	 * Convert line feeds to <br /> tags
	 * @param string
	 * @return string
	 */
	public function convertLineFeeds($strTesimonial)
	{
		global $objPage;
		$strTesimonial = nl2br_pre($strTesimonial, ($objPage->outputFormat == 'xhtml'));

		// Use paragraphs to generate new lines
		if (strncmp('<p>', $strTesimonial, 3) !== 0)
		{
			$strTesimonial = '<p>'. $strTesimonial .'</p>';
		}

		$arrReplace = array
		(
			'@<br>\s?<br>\s?@' => "</p>\n<p>", // Convert two linebreaks into a new paragraph
			'@\s?<br></p>@'    => '</p>',      // Remove BR tags before closing P tags
			'@<p><div@'        => '<div',      // Do not nest DIVs inside paragraphs
			'@</div></p>@'     => '</div>'     // Do not nest DIVs inside paragraphs
		);

		return preg_replace(array_keys($arrReplace), array_values($arrReplace), $strTesimonial);
	}

	/**
 	* Get either a Gravatar URL or complete image tag for a specified email address.
 	*
	* @param string $email The email address
 	* @param string $s Size in pixels, defaults to 80px [ 1 - 512 ]
 	* @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 	* @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 	* @param boole $img True to return a complete IMG tag False for just the URL
 	* @param array $atts Optional, additional key/value attributes to include in the IMG tag
 	* @return String containing either just a URL or a complete image tag
 	* @source http://gravatar.com/site/implement/images/php/
 	*/
	public function get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = true, $atts = array() ) 
	{
		$avatar = 'http://www.gravatar.com/avatar/';
		$avatar .= md5( strtolower( trim( $email ) ) );
		$avatar .= "?s=$s&d=$d&r=$r";

		if ( $img ) {
			$avatar = '<img class="gravatar" src="' . $avatar . '"';
			foreach ( $atts as $key => $val )
				$avatar .= ' ' . $key . '="' . $val . '"';
				$avatar .= ' />';
		}
		return $avatar;
	}

	public function get_votestars( $vote, $count ,$bigstars = false ) 
	{

		$average = round(($vote / 5) * 100);
		$style = ' style="width: '. $average . '%;"';

		if ( $bigstars ) {
			$stars = '<h3>'.$GLOBALS['TL_LANG']['MSC']['TotalVotes'].'</h3>';
			$stars .= '<div class="bigstars"><div class="allvotes" ';
			$stars .= $style;
			$stars .= '></div></div>';
			$stars .= '<div class="allstats">'.$average .'% '.$GLOBALS['TL_LANG']['MSC']['allstats'].'</div>';
			$stars .=  '<div class="countstats">( '.$count.' '.$GLOBALS['TL_LANG']['MSC']['allVotes'].' )</div>';

		} else {
			$stars = '<span>'.$GLOBALS['TL_LANG']['MSC']['myVote'].'</span>';
			$stars .= '<div class="smallstars"><div class="votevol" ';
			$stars .= $style;
			$stars .= '></div></div>';
		}

		return $stars;
	}

}

?>
