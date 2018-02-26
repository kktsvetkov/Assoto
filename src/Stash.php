<?php /**
* Assoto: dynamic loading of javascript, stylesheets, meta tags, etc.
*
* @author Kaloyan Tsvetkov (KT) <kaloyan@kaloyan.info>
* @package Assoto
* @link https://github.com/kktsvetkov/assoto/
* @license http://opensource.org/licenses/LGPL-3.0 GNU Lesser General Public License, version 3.0
*/

namespace Assoto;

/**
* Assets Stash
*
* Assets are accumulated in zones. There are two zones by default: "head" (used
* for the "<head>...</head>" element) and "footer" (used for the bottom of the
* "<body>" tag)
*/
class Stash
{
	protected static $stash = array(
		self::ZONE_HEAD => array(),
		self::ZONE_FOOTER => array(),
	);

	const ZONE_HEAD = 'head';

	const ZONE_FOOTER = 'footer';

	/**
	* Return list of existing zones
	* @return array
	*/
	public static function zones()
	{
		return array_keys(self::$stash);
	}

	/**
	* Enque new asset to a zone
	* @param string $zone name of the zone, usually will be either "head"
	*	or "footer", but can be anything you want to use
	* @param string $asset
	* @param string $id (optional) how to identify the new asset added; if
	*	empty, it will be generated
	*/
	public static function add($zone, $asset, $id = '')
	{
		if (empty($id))
		{
			$id = 'asset:' . (1 + (empty(self::$stash[$zone])
				? 0
				: count(self::$stash[$zone])
				));
		}

		self::$stash[$zone][$id] = $asset;
	}

	/**
	* Check if an asset already exists in a zone
	* @param string $zone name of the zone, usually will be either "head"
	*	or "footer", but can be anything you have used to add assets
	* @param string $id identifier for the asset you want to delete
	* @return boolean
	*/
	public static function exists($zone, $id)
	{
		if (!isset(self::$stash[$zone]))
		{
			trigger_error(
				"Assets zone '{$zone}' does not exists.",
				E_USER_WARNING
				);
			return false;
		}

		return isset(self::$stash[$zone][$id]);
	}

	/**
	* Delete an already introduced asset
	* @param string $zone name of the zone, usually will be either "head"
	*	or "footer", but can be anything you have used to add assets
	* @param string $id identifier for the asset you want to delete
	* @return boolean
	*/
	public static function delete($zone, $id)
	{
		if (!isset(self::$stash[$zone]))
		{
			trigger_error(
				"Assets zone '{$zone}' does not exists.",
				E_USER_WARNING
				);
			return false;
		}

		if (!isset(self::$stash[$zone][$id]))
		{
			trigger_error(
				"Nothing found with id='{$id}' in assets zone '{$zone}'.",
				E_USER_WARNING
				);
			return false;
		}

		unset(self::$stash[$zone][$id]);
		return true;
	}

	/**
	* Return list of already accumulated assets for a zone
	* @param string $zone name of the zone, usually will be either "head"
	*	or "footer", but can be anything you have used to add assets
	* @return false|array key\value pairs of assets ids and assets values;
	*	FALSE if there is no such zone
	*/
	public static function zone($zone)
	{
		if (!isset(self::$stash[$zone]))
		{
			trigger_error(
				"Assets zone '{$zone}' does not exists.",
				E_USER_WARNING
				);
			return false;
		}

		return self::$stash[$zone];
	}

	/**
	* Resets the accumulated assets for a zone
	* @param string $zone name of the zone, usually will be either "head"
	*	or "footer", but can be anything you have used to add assets
	* @return boolean false if there is no such zone, true otherwise
	*/
	public static function reset($zone)
	{
		if (!isset(self::$stash[$zone]))
		{
			return false;
		}

		self::$stash[$zone] = array();
		return true;
	}

	/**
	* Output the accumulated assets for a zone
	* @param string $zone name of the zone, usually will be either "head"
	*	or "footer", but can be anything you have used to add assets
	* @param boolean $newlines (optional) whether to use new lines to
	*	concatenate the accumulated assets
	* @return false|string FALSE if there is no such zone
	*/
	public static function display($zone, $newlines = false)
	{
		if (!isset(self::$stash[$zone]))
		{
			trigger_error(
				"Assets zone '{$zone}' does not exists.",
				E_USER_WARNING
				);
			return false;
		}

		return join(
			$newlines
				? "\n"
				: '',
			self::$stash[$zone]
		);
	}

}
