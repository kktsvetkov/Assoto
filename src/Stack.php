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
* Assets Stacks
*
* Assets are accumulated in stacks. There are two stacks by default: "head"
* (used for the "<head>...</head>" element) and "footer" (used for the bottom
* of the "<body>" tag)
*/
class Stack
{
	/**
	* @var array stacks of accumulated assets; key\value pairs,
	* 	where the name of the stack is the key, and the list
	*	of accumulated assets is the value
	*/
	protected static $stacks = array(
		self::STACK_HEAD => array(),
		self::STACK_FOOTER => array(),
		);

	/**
	* @var string stack name for HTML "<head>...</head>" tag
	*/
	const STACK_HEAD = 'head';

	/**
	* @var string stack name for bottom of the HTML, right before the
	* 	closing "</body>" tag
	*/
	const STACK_FOOTER = 'footer';

	/**
	* Return list of existing stacks
	* @return array
	*/
	public static function stacks()
	{
		return array_keys(self::$stacks);
	}

	/**
	* Stack new asset
	*
	* @param string $stack name of the stack, usually will be either "head"
	*	or "footer", but can be anything you want to use
	* @param string $asset
	* @param string $id (optional) how to identify the new asset added; if
	*	empty, it will be generated
	* @return boolean
	*/
	public static function add($stack, $asset, $id = '')
	{
		if (empty($id))
		{
			$id = 'asset:' . (1 + (empty(self::$stacks[$stack])
				? 0
				: count(self::$stacks[$stack])
				));
		}

		if (!empty(self::$display[$stack]))
		{
			trigger_error(
				"Assets stack '{$stack}' already displaid, you can't add to it.",
				E_USER_WARNING
				);
			return false;
		}

		self::$stacks[$stack][$id] = $asset;
		return true;
	}

	/**
	* Check if an asset already exists in a stack
	*
	* @param string $stack name of the stack, usually will be either "head"
	*	or "footer", but can be anything you have used to add assets
	* @param string $id identifier for the asset you want to delete
	* @return boolean
	*/
	public static function exists($stack, $id)
	{
		if (!isset(self::$stacks[$stack]))
		{
			trigger_error(
				"Assets stack '{$stack}' does not exists.",
				E_USER_WARNING
				);
			return false;
		}

		return isset(self::$stacks[$stack][$id]);
	}

	/**
	* Delete an already introduced asset
	* @param string $stack name of the stack, usually will be either "head"
	*	or "footer", but can be anything you have used to add assets
	* @param string $id identifier for the asset you want to delete
	* @return boolean
	*/
	public static function delete($stack, $id)
	{
		if (!isset(self::$stacks[$stack]))
		{
			trigger_error(
				"Assets stack '{$stack}' does not exists.",
				E_USER_WARNING
				);
			return false;
		}

		if (!isset(self::$stacks[$stack][$id]))
		{
			trigger_error(
				"Nothing found with id='{$id}' in assets stack '{$stack}'.",
				E_USER_WARNING
				);
			return false;
		}

		unset(self::$stacks[$stack][$id]);
		return true;
	}

	/**
	* Return list of already accumulated assets for a stack
	*
	* @param string $stack name of the stack, usually will be either "head"
	*	or "footer", but can be anything you have used to add assets
	* @return false|array key\value pairs of assets ids and assets values;
	*	FALSE if there is no such stack
	*/
	public static function stack($stack)
	{
		if (!isset(self::$stacks[$stack]))
		{
			trigger_error(
				"Assets stack '{$stack}' does not exists.",
				E_USER_WARNING
				);
			return false;
		}

		return self::$stacks[$stack];
	}

	/**
	* Resets the accumulated assets for a stack
	*
	* @param string $stack name of the stack, usually will be either "head"
	*	or "footer", but can be anything you have used to add assets
	* @return boolean false if there is no such stack, true otherwise
	*/
	public static function reset($stack)
	{
		if (!isset(self::$stacks[$stack]))
		{
			return false;
		}

		self::$stacks[$stack] = array();
		return true;
	}

	/**
	* Output the accumulated assets for a stack
	*
	* @param string $stack name of the stack, usually will be either "head"
	*	or "footer", but can be anything you have used to add assets
	* @param boolean $newlines (optional) whether to use new lines to
	*	concatenate the accumulated assets
	* @return false|string FALSE if there is no such stack
	*/
	public static function display($stack, $newlines = false)
	{
		if (!isset(self::$stacks[$stack]))
		{
			trigger_error(
				"Assets stack '{$stack}' does not exists.",
				E_USER_WARNING
				);
			return false;
		}

		if (!empty(self::$display[$stack]))
		{
			trigger_error(
				"Assets stack '{$stack}' already displaid.",
				E_USER_WARNING
				);
			return false;
		}

		self::$display[$stack] = true;
		return join(
			$newlines
				? "\n"
				: '',
			self::$stacks[$stack]
		);
	}

	/**
	* @var array keep track of what stacks has been displaid
	*/
	protected static $display = array();

}
