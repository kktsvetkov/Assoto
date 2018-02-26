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
* Cascading Stylesheets (CSS) Assets
*
* The style blocks ("<style>...</style>") and linked stylesheets
* ("<link type='text\css' href='...'>") are usually attached to the
* "<head>...</head>" inside the HTML
*/
class CSS
{
	/**
	* Adds an inline style block
	*
	* Adds a style block as an asset, which will appear as
	* "<style>...</style>" inserted into the "head" zone
	*
	* @param string $style CSS for the style block; don't add the "<style>"
	*	tags wrapping the CSS code, that will be added by this method
	* @param string $id (optional) identifier for this asset; if left empty
	*	it will be generated by this method
	*/
	public static function style($style, $id = '')
	{
		if (empty($id))
		{
			$id = count(Stash::zone(Stash::ZONE_HEAD));
		}

		return Stash::add(
			Stash::ZONE_HEAD,
			'<style type="text/css">' . $style . '</style>',
			'css:' . $id
		);
	}

	/**
	* Adds a CSS stylesheet file
	*
	* Adds a CSS stylesheet as an asset, which will appear inserted into
	* the "head" zone as "<link type='text\css' href='...'>" tag
	*
	* @param string $stylesheet URL to the stylesheet
	* @param string $id (optional) identifier for this asset; if left empty
	*	then the stylesheet will be used
	* @param array $extra (optional) extra attributes for the style, such as
	*	"type", "rel", "media", etc.
	*/
	public static function stylesheet($stylesheet, $id = '', array $extra = array())
	{
		$extra += array(
			'type' => 'text/css',
			);

		if (empty($id))
		{
			$id = $stylesheet;
		}

		return Meta::link(
			$stylesheet,
			'stylesheet',
			'css:' . $id,
			$extra
			);
	}
}
