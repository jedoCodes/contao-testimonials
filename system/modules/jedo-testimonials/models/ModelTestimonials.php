<?php
/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * AutoLoad jedoTestimonials
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


/**
 * Reads and writes testimonials
 * 
 */
class TestimonialsModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_testimonials';


	/**
	 * Find published comments by their source table and parent ID
	 * 
	 * @param string  $strSource The source element
	 * @param integer $intParent The parent ID
	 * @param boolean $blnDesc   If true, comments will be sorted descending
	 * @param integer $intLimit  An optional limit
	 * @param integer $intOffset An optional offset
	 * 
	 * @return \Model\Collection|null A collection of models or null if there are no comments
	 */
	public static function findPublishedTestimonials( $intParent, $blnDesc=false, $intLimit=0, $intOffset=0)
	{
		$t = static::$strTable;
		
		if (!BE_USER_LOGGED_IN)
		{
			$arrColumns = array("$t.published='1'");
		}

		$arrOptions = array
		(
			'order'  => ($blnDesc ? "$t.date DESC" : "$t.date"),
			'limit'  => $intLimit,
			'offset' => $intOffset
		);

		return static::findBy($arrColumns, $intParent, $arrOptions);
	}


	/**
	 * Count published comments by their source table and parent ID
	 * 
	 * @param string  $strSource The source element
	 * @param integer $intParent The parent ID
	 * 
	 * @return integer The number of comments
	 */
	public static function countPublishedTestimonials($intParent)
	{
		$t = static::$strTable;

		if (!BE_USER_LOGGED_IN)
		{
			$arrColumns = array("$t.published='1'");
		}

		return static::countBy($arrColumns, $intParent);
	}

}
