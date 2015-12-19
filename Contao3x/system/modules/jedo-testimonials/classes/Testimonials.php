<?php
/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * Class Testimonials
 *
 * @copyright  JEDO 2012
 * @author     Jens Doberenz <http://jensdoberenz.de>
 * @package    jedoTestimonials
 * @version    3.0.0
 */

/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace jedoStyle\Testimonials;


class Testimonials extends \Frontend
{

	public function addTestimonialsToTemplate(\FrontendTemplate $objTemplate, \stdClass $objConfig, $intParent, $arrNotifies)
	{
		global $objPage;

		$limit = 0;
		$offset = 0;
		$total = 0;
		$gtotal = 0;
		$arrTestimonials = array();
		$objTemplate->testimonials = array(); 

		// Pagination
		if ($objConfig->perPage > 0)
		{
			// Get the total number of testimonials
			$intTotal = \TestimonialsModel::countPublishedTestimonials($intParent);
			$total = $gtotal = $intTotal;

			// Get the current page
			$id = 'page_entry' . $intParent; // see #4141
			$page = \Input::get($id) ?: 1;

			// Do not index or cache the page if the page number is outside the range
			if ($page < 1 || $page > max(ceil($total/$objConfig->perPage), 1))
			{
				global $objPage;
				$objPage->noSearch = 1;
				$objPage->cache = 0;

				// Send a 404 header
				header('HTTP/1.1 404 Not Found');
				return;
			}

			// Set limit and offset
			$limit = $objConfig->perPage;
			$offset = ($page - 1) * $objConfig->perPage;

			// Initialize the pagination menu
			$objPagination = new \Pagination($total, $objConfig->perPage, 7, $id);
			$objTemplate->pagination = $objPagination->generate("\n  ");
		}

		// Get all published testimonials
		if ($limit)
		{
			$objTestimonials = \TestimonialsModel::findPublishedTestimonials($intParent, ($objConfig->order == 'descending'), $limit, $offset);
		}
		else
		{
			$objTestimonials = \TestimonialsModel::findPublishedTestimonials($intParent, ($objConfig->order == 'descending'));
		}


		// Parse the testimonials
		if ($objTestimonials !== null && ($total = $objTestimonials->count()) > 0)
		{
			$count = 0;

			if ($objConfig->template == '')
			{
				$objConfig->template = 'testimonial_entry';
			}

			$objPartial = new \FrontendTemplate($objConfig->template);

			while ($objTestimonials->next())
			{
				$objPartial->setData($objTestimonials->row());

				// Clean the RTE output
				if ($objPage->outputFormat == 'xhtml')
				{
					$objTestimonials->testimonial = \String::toXhtml($objTestimonials->testimonial);
				}
				else
				{
					$objTestimonials->testimonial = \String::toHtml5($objTestimonials->testimonial);
				}

				$objPartial->testimonial = trim(str_replace(array('{{', '}}'), array('&#123;&#123;', '&#125;&#125;'), $objTestimonials->testimonial));

				$objPartial->datim = $this->parseDate($objPage->datimFormat, $objTestimonials->date);
				$objPartial->date = $this->parseDate($objPage->dateFormat, $objTestimonials->date);
				$objPartial->class = (($count < 1) ? ' first' : '') . (($count >= ($total - 1)) ? ' last' : '') . (($count % 2 == 0) ? ' even' : ' odd');
				$objPartial->by = $GLOBALS['TL_LANG']['MSC']['com_by'];
				$objPartial->id = 'entry_' . $objTestimonials->id;
				$objPartial->timestamp = $objTestimonials->date;
				$objPartial->datetime = date('Y-m-d\TH:i:sP', $objTestimonials->date);
				$objPartial->avatar = $this->get_gravatar($objTestimonials->email,$objTestimonials->name,$objConfig->AvatarMode, $objConfig->AvatarSize, $objConfig->DefaultGravatar, $objConfig->DefaultGravatarRating, $objConfig->DefaultAvatar  );
				$objPartial->titel =  $objTestimonials->title;
				$objPartial->addVote = $objConfig->addVote;
				$objPartial->enableVoteField1 = $objConfig->enableVoteField1;
				$objPartial->enableVoteField2 = $objConfig->enableVoteField2;
				$objPartial->enableVoteField3 = $objConfig->enableVoteField3;
				$objPartial->enableVoteField4 = $objConfig->enableVoteField4;
				$objPartial->enableVoteField5 = $objConfig->enableVoteField5;
				$objPartial->enableVoteField6 = $objConfig->enableVoteField6;
				$objPartial->VoteField1Name = $objConfig->VoteField1Name;
				$objPartial->VoteField2Name = $objConfig->VoteField2Name;
				$objPartial->VoteField3Name = $objConfig->VoteField3Name;
				$objPartial->VoteField4Name = $objConfig->VoteField4Name;
				$objPartial->VoteField5Name = $objConfig->VoteField5Name;
				$objPartial->VoteField6Name = $objConfig->VoteField6Name;
				$objPartial->design = $objConfig->design;


				$arrTestimonials[] = $objPartial->parse();
				++$count;
			}
		}

		$objTemplate->testimonials = $arrTestimonials;
		$objTemplate->addVote = $objConfig->addVote;
		$objTemplate->name = $GLOBALS['TL_LANG']['MSC']['tm_name'];
		$objTemplate->email = $GLOBALS['TL_LANG']['MSC']['tm_email'];
		$objTemplate->url = $GLOBALS['TL_LANG']['MSC']['tm_website'];
		$objTemplate->company = $GLOBALS['TL_LANG']['MSC']['tm_company'];
		$objTemplate->title = $GLOBALS['TL_LANG']['MSC']['tm_title'];
		$objTemplate->TestimonialsTotal = $limit ? $gtotal : $total;

		// Add a form to create new testimonials
		$this->renderTestimonialForm($objTemplate, $objConfig, $intParent);
	}


	/**
	 * Add a form to create new testimonials
	 * @param \FrontendTemplate
	 * @param \stdClass
	 * @param string
	 * @param integer
	 * @param array
	 */
	protected function renderTestimonialForm(\FrontendTemplate $objTemplate, \stdClass $objConfig, $intParent)
	{
		$this->import('FrontendUser', 'User');

		// Access control
		if ($objConfig->requireLogin && !BE_USER_LOGGED_IN && !FE_USER_LOGGED_IN)
		{
			$objTemplate->requireLogin = true;
			return;
		}

		if (BE_USER_LOGGED_IN && FE_USER_LOGGED_IN)
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
				'name'      => 'name',
				'label'     => $GLOBALS['TL_LANG']['MSC']['tm_name'],
				'value'     => $value_name,
				'inputType' => 'text',
				'eval'      => array('mandatory'=>true, 'maxlength'=>64, 'placeholder'=> $GLOBALS['TL_LANG']['MSC']['tm_name'], 'onblur' => "if(this.value == '') { this.value='$value_name'}", 'onfocus' => "if (this.value == '$value_name') {this.value=''}")
			),
			'email' => array
			(
				'name'      => 'email',
				'label'     => $GLOBALS['TL_LANG']['MSC']['tm_email'],
				'value'     => $value_email,
				'inputType' => 'text',
				'eval'      => array('rgxp'=>'email', 'mandatory'=>true, 'maxlength'=>128, 'decodeEntities'=>true, 'placeholder'=> $GLOBALS['TL_LANG']['MSC']['tm_email'], 'onblur' => "if(this.value == '') { this.value='$value_email'}", 'onfocus' => "if (this.value == '$value_email') {this.value=''}")
			),
			'url' => array
			(
				'name'      => 'url',
				'label'     => $GLOBALS['TL_LANG']['MSC']['tm_url'],
				'value'     => $value_url,
				'inputType' => 'text',
				'eval'      => array('rgxp'=>'url', 'maxlength'=>128, 'decodeEntities'=>true, 'placeholder'=> $GLOBALS['TL_LANG']['MSC']['tm_url'], 'onblur' => "if(this.value == '') { this.value='$value_url'}", 'onfocus' => "if (this.value == '$value_url') {this.value=''}")
			),
			'company' => array
			(
				'name'      => 'company',
				'label'     => $GLOBALS['TL_LANG']['MSC']['tm_company'],
				'value'     => $value_company,
				'inputType' => 'text',
				'eval'      => array('maxlength'=>128, 'placeholder'=> $GLOBALS['TL_LANG']['MSC']['tm_company'], 'onblur' => "if(this.value == '') { this.value='$value_company'}", 'onfocus' => "if (this.value == '$value_company') {this.value=''}")
			),
			'title' => array
			(
				'name'      => 'title',
				'label'     => $GLOBALS['TL_LANG']['MSC']['tm_title'],
				'value'     => $value_title,
				'inputType' => 'text',
				'eval'      => array('maxlength'=>128, 'placeholder'=> $GLOBALS['TL_LANG']['MSC']['tm_title'], 'onblur' => "if(this.value == '') { this.value='$value_title'}", 'onfocus' => "if (this.value == '$value_title') {this.value=''}")
			)
		);

		if (($objConfig->enableVoteField1) && ($objConfig->addVote)){
			$arrFields['votefield1'] = array
			(
				'name' 		=> 'votefield1',
				'label'     => &$GLOBALS['TL_LANG']['MSC']['votefield1'],
				'default' 	=> '0.0',
				'inputType' => 'text',
				'eval'      => array('style' => 'display: none;')
			);
		}
		if (($objConfig->enableVoteField2) && ($objConfig->addVote)){
			$arrFields['votefield2'] = array
			(
				'name' 		=> 'votefield2',
				'label'     => &$GLOBALS['TL_LANG']['MSC']['votefield2'],
				'default' 	=> '0.0',
				'inputType' => 'text',
				'eval'      => array('style' => 'display: none;')
			);
		}
		if (($objConfig->enableVoteField3) && ($objConfig->addVote)){
			$arrFields['votefield3'] = array
			(
				'name' 		=> 'votefield3',
				'label'     => &$GLOBALS['TL_LANG']['MSC']['votefield3'],
				'default' 	=> '0.0',
				'inputType' => 'text',
				'eval'      => array('style' => 'display: none;')
			);
		}
		if (($objConfig->enableVoteField4) && ($objConfig->addVote)){
			$arrFields['votefield4'] = array
			(
				'name' 		=> 'votefield4',
				'label'     => &$GLOBALS['TL_LANG']['MSC']['votefield4'],
				'default'   => '0.0',
				'inputType' => 'text',
				'eval'      => array('style' => 'display: none;')
			);
		}
		if (($objConfig->enableVoteField5) && ($objConfig->addVote)){
			$arrFields['votefield5'] = array
			(
				'name' 		=> 'votefield5',
				'label'     => &$GLOBALS['TL_LANG']['MSC']['votefield5'],
				'default'   => '0.0',
				'inputType' => 'text',
				'eval'      => array('style' => 'display: none;')
			);
		}
		if (($objConfig->enableVoteField6) && ($objConfig->addVote)){
			$arrFields['votefield6'] = array
			(
				'name' 		=> 'votefield6',
				'label'     => &$GLOBALS['TL_LANG']['MSC']['votefield6'],
				'default'   => '0.0',
				'inputType'	=> 'text',
				'eval'      => array('style' => 'display: none;')
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
			'eval' => array('mandatory'=>true, 'rows'=>15, 'cols'=>40, 'preserveTags'=>true)
		);

		$doNotSubmit = false;
		$arrWidgets = array();
		$strFormId = 'jedo_testimonials_'. $intParent;

		// Initialize the widgets
		foreach ($arrFields as $arrField)
		{
			$strClass = $GLOBALS['TL_FFL'][$arrField['inputType']];

			// Continue if the class is not defined
			if (!class_exists($strClass))
			{
				continue;
			}

			$arrField['eval']['required'] = $arrField['eval']['mandatory'];
			$objWidget = new $strClass($this->prepareForWidget($arrField, $arrField['name'], $arrField['value']));

			// Validate the widget
			if (\Input::post('FORM_SUBMIT') == $strFormId)
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
		$objTemplate->submit = $GLOBALS['TL_LANG']['MSC']['com_submit'];
		$objTemplate->action = ampersand(\Environment::get('request'));
		$objTemplate->messages = ''; // Backwards compatibility
		$objTemplate->formId = $strFormId;
		$objTemplate->hasError = $doNotSubmit;

		// Do not index or cache the page with the confirmation message
		if ($_SESSION['TL_TESTIMONIAL_ADDED'])
		{
			global $objPage;
			$objPage->noSearch = 1;
			$objPage->cache = 0;

			$objTemplate->confirm = $GLOBALS['TL_LANG']['MSC']['com_confirm'];
			$_SESSION['TL_TESTIMONIAL_ADDED'] = false;
		}

		// Store the testimonial
		if (!$doNotSubmit && \Input::post('FORM_SUBMIT') == $strFormId)
		{

			$strWebsite = $arrWidgets['url']->value;
			if ($strWebsite == $value_url ) $strWebsite = '';

			// Add http:// to the website
			if (($strWebsite != '') && !preg_match('@^(https?://|ftp://|mailto:|#)@i', $strWebsite))
			{
				$strWebsite = 'http://' . $strWebsite;
			}

			// Do not parse any tags in the testimonial
			$strTestimonial = htmlspecialchars(trim($arrWidgets['testimonial']->value));

			$strTestimonial = str_replace(array('&amp;', '&lt;', '&gt;'), array('[&]', '[lt]', '[gt]'), $strTestimonial);

			// Remove multiple line feeds
			$strTestimonial = preg_replace('@\n\n+@', "\n\n", $strTestimonial);

			// Parse BBCode
			if ($objConfig->bbcode)
			{
				$strTestimonial = $this->parseBbCode($strTestimonial);
			}

			// Prevent cross-site request forgeries
			$strTestimonial = preg_replace('/(href|src|on[a-z]+)="[^"]*(contao\/main\.php|typolight\/main\.php|javascript|vbscri?pt|script|alert|document|cookie|window)[^"]*"+/i', '$1="#"', $strTestimonial);
			$time = time();

			if ($objConfig->addVote)
			{
				// make the totalvote object
				$fields = 0;
				$value = 0.0;

				if($objConfig->enableVoteField1) {

					$arrWidgets['votefield1']->value = $this->getRatingValue($arrWidgets['votefield1']->value);

					$value = $value + $arrWidgets['votefield1']->value; 
					$fields ++;
				}
				if($objConfig->enableVoteField2) {
 
					$arrWidgets['votefield2']->value = $this->getRatingValue($arrWidgets['votefield2']->value);

					$value = $value + $arrWidgets['votefield2']->value; 
					$fields ++;
				}
				if($objConfig->enableVoteField3) { 

					$arrWidgets['votefield3']->value = $this->getRatingValue($arrWidgets['votefield3']->value);

					$value = $value + $arrWidgets['votefield3']->value; 
					$fields ++;
				}
				if($objConfig->enableVoteField4) { 

					$arrWidgets['votefield4']->value = $this->getRatingValue($arrWidgets['votefield4']->value);

					$value = $value + $arrWidgets['votefield4']->value; 
					$fields ++;
				}
				if($objConfig->enableVoteField5) { 

					$arrWidgets['votefield5']->value = $this->getRatingValue($arrWidgets['votefield5']->value);

					$value = $value + $arrWidgets['votefield5']->value; 
					$fields ++;
				}
				if($objConfig->enableVoteField6) { 

					$arrWidgets['votefield6']->value = $this->getRatingValue($arrWidgets['votefield6']->value);

					$value = $value + $arrWidgets['votefield6']->value; 
					$fields ++;
				}
				$totalvote = $value / $fields;
				$strTVotes = number_format($totalvote,2);
			}

			if ($arrWidgets['company']->value == $value_company ) $arrWidgets['company']->value = '';
			if ($arrWidgets['title']->value == $value_title ) $arrWidgets['title']->value = '';

			// Prepare the record
			$arrSet = array
			(
				'tstamp' => $time,
				'name' => $arrWidgets['name']->value,
				'email' => $arrWidgets['email']->value,
				'company' => $arrWidgets['company']->value,
				'title' => $arrWidgets['title']->value,
				'url' => $strWebsite,
				'testimonial' => $this->convertLineFeeds($strTestimonial),
				'ip' => $this->anonymizeIp($this->Environment->ip),
				'date' => $time,
				'votestotal' => $strTVotes,
				'votefield1' => (!$objConfig->enableVoteField1 ? '' : $arrWidgets['votefield1']->value),
				'votefield2' => (!$objConfig->enableVoteField2 ? '' : $arrWidgets['votefield2']->value),
				'votefield3' => (!$objConfig->enableVoteField3 ? '' : $arrWidgets['votefield3']->value),
				'votefield4' => (!$objConfig->enableVoteField4 ? '' : $arrWidgets['votefield4']->value),
				'votefield5' => (!$objConfig->enableVoteField5 ? '' : $arrWidgets['votefield5']->value),
				'votefield6' => (!$objConfig->enableVoteField6 ? '' : $arrWidgets['votefield6']->value),
				'published' => ($objConfig->moderate ? '' : 1)
			);

			// Store the testimonial
			$objTestimonials = new \TestimonialsModel();
			$objTestimonials->setRow($arrSet)->save();

			// Prepare the notification mail
			$objEmail = new \Email();
			$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
			$objEmail->fromName = $GLOBALS['TL_ADMIN_NAME'];
			$objEmail->subject = sprintf($GLOBALS['TL_LANG']['MSC']['com_subject'], \Environment::get('host'));

			// Convert the testimonial to plain text
			$strTestimonial = strip_tags($strTestimonial);
			$strTestimonial = \String::decodeEntities($strTestimonial);
			$strTestimonial = str_replace(array('[&]', '[lt]', '[gt]'), array('&', '<', '>'), $strTestimonial);

			// Add the testimonial details
			$objEmail->text = sprintf($GLOBALS['TL_LANG']['MSC']['com_message'],
									  $arrSet['name'] . ' (' . $arrSet['email'] . ')',
									  $strTestimonial,
									  \Environment::get('base') . \Environment::get('request'),
									  \Environment::get('base') . 'contao/main.php?do=testimonials&act=edit&id=' . $objTestimonials->id);

			// Pending for approval
			if ($objConfig->moderate)
			{
				// FIXME: notify the subscribers when the testimonial is published
				$_SESSION['TL_TESTIMONIAL_ADDED'] = true;
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
	public function parseBbCode($strTestimonial)
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
			"\n\n" . '<div class="code"><p>'. $GLOBALS['TL_LANG']['MSC']['com_code'] .'</p><pre>$1</pre></div>' . "\n\n",
			'<span style="color:$1">$2</span>',
			"\n\n" . '<div class="quote">$1</div>' . "\n\n",
			"\n\n" . '<div class="quote"><p>'. sprintf($GLOBALS['TL_LANG']['MSC']['com_quote'], '$1') .'</p>$2</div>' . "\n\n",
			'<img src="$1" alt="" />',
			'<a href="$1">$1</a>',
			'<a href="$1">$2</a>',
			'<a href="mailto:$1">$1</a>',
			'<a href="mailto:$1">$2</a>',
			'href="http://$1'
		);

		$strTestimonial = preg_replace($arrSearch, $arrReplace, $strTestimonial);

		// Encode e-mail addresses
		if (strpos($strTestimonial, 'mailto:') !== false)
		{
			$strTestimonial = \String::encodeEmail($strTestimonial);
		}

		return $strTestimonial;
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
	public function get_gravatar( $email, $name, $AvatarMode, $AvatarSize, $DefaultGravatar, $DefaultGravatarRating, $DefaultAvatar )
	{
 		if ($AvatarMode == '') {
			return;
		}

		if ($AvatarMode == 'gravatar')
		{
			if($DefaultGravatar == "custom") { $DefaultGravatar = $this->Environment->base.$DefaultAvatar; }

			$afile = 'http://www.gravatar.com/avatar/';
			$afile .= md5( strtolower( trim( $email ) ) );
			$afile .= "?s=$AvatarSize&d=$DefaultGravatar&r=$DefaultGravatarRating";

			$avatar = '<img class="gravatar" src="' . $afile . '" />';

		} elseif ($AvatarMode == 'avatar') {

			$DefaultGravatar = $this->Environment->base.$DefaultAvatar; 
			if(\Database::getInstance()->fieldExists('avatar', 'tl_member')) {
				$avatarext = \Database::getInstance()->execute("select avatar, name from tl_member where email='$email'");
				if($avatarext->avatar) {
					$avatar = '<img class="gravatar" src="' . $avatarext->avatar . '" alt="Avatar of ' . $avatarext->name . '"  width="' . $AvatarSize . '" />';
				} else {
					$avatar = '<img class="gravatar" src="' . $DefaultGravatar . '" alt="Avatar of ' . $name . '" width="' . $AvatarSize . '" />';
				}
			} else {
				$avatar = '<img class="gravatar" src="' . $DefaultGravatar . '" alt="Avatar of ' . $name . '" width="' . $AvatarSize . '" />';
			}
		} else {
			$avatar = '<img class="gravatar" src="' . $this->Environment->base.$DefaultAvatar . '" alt="Avatar of ' . $name . '" width="' . $AvatarSize . '" />';
		}
			$avatar .= "\n"; 

		return $avatar;
	}

	/**
	 * Convert line feeds to <br /> tags
	 * @param string
	 * @return string
	 */
	public function convertLineFeeds($strTestimonial)
	{
		global $objPage;
		$strTestimonial = nl2br_pre($strTestimonial, ($objPage->outputFormat == 'xhtml'));

		// Use paragraphs to generate new lines
		if (strncmp('<p>', $strTestimonial, 3) !== 0)
		{
			$strTestimonial = '<p>'. $strTestimonial .'</p>';
		}

		$arrReplace = array
		(
			'@<br>\s?<br>\s?@' => "</p>\n<p>", // Convert two linebreaks into a new paragraph
			'@\s?<br></p>@'    => '</p>',      // Remove BR tags before closing P tags
			'@<p><div@'        => '<div',      // Do not nest DIVs inside paragraphs
			'@</div></p>@'     => '</div>'     // Do not nest DIVs inside paragraphs
		);

		return preg_replace(array_keys($arrReplace), array_values($arrReplace), $strTestimonial);
	}

	public function getRatingValue($ratingValue){

			if(($ratingValue > 0.00) && ($ratingValue <= 0.25)) $ratingValue = 0.0;
			if(($ratingValue > 0.25) && ($ratingValue <= 0.75)) $ratingValue = 0.5;
			if(($ratingValue > 0.75) && ($ratingValue <= 1.25)) $ratingValue = 1.0;
			if(($ratingValue > 1.25) && ($ratingValue <= 1.75)) $ratingValue = 1.5;
			if(($ratingValue > 1.75) && ($ratingValue <= 2.25)) $ratingValue = 2.0;
			if(($ratingValue > 2.25) && ($ratingValue <= 2.75)) $ratingValue = 2.5;
			if(($ratingValue > 2.75) && ($ratingValue <= 3.25)) $ratingValue = 3.0;
			if(($ratingValue > 3.25) && ($ratingValue <= 3.75)) $ratingValue = 3.5;
			if(($ratingValue > 3.75) && ($ratingValue <= 4.25)) $ratingValue = 4.0;
			if(($ratingValue > 4.25) && ($ratingValue <= 4.75)) $ratingValue = 4.5;
			if(($ratingValue > 4.75) && ($ratingValue <= 5.00)) $ratingValue = 5.0;

		return $ratingValue;
	}


	public static function getRating($votefield){
		$erg = \Database::getInstance()->prepare('SELECT AVG('.$votefield.') AS rating FROM tl_testimonials WHERE published=1 AND '.$votefield.' != "-1"')->execute();

		return number_format($erg->rating,2);
	}
}
