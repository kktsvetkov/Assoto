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
* Other HTML tags and elements
*/
class HTML
{
	/**
	* Adds the HTML title
	*
	* Adds the "<title>...</title>" tag as an assets. The title always
	* appears in the "<head>...</head>" part of the HTML. There can be only
	* one title, and that is why there is no $id argument here: instead you
	* can find this element with the "html:title" id in the "head" stack
	*
	* @param string $title
	*/
	public static function title($title)
	{
		return Stack::add(
			Stack::STACK_HEAD,
			'<title>' . htmlspecialchars($title) . '</title>',
			'html:title'
		);
	}

	/**
	* Return HTML attributes built from an array
	*
	* @param array $array
	* @return string generated HTML
	*/
	public static function attributes(array $array)
	{
		$html = '';
		foreach($array as $key => $value)
		{
			if (!is_scalar($value))
			{
				$value = http_build_query((array) $value);
			}

			if (is_int($key))
			{
				$html .= (empty($html) ? '' : ' ')
					. htmlspecialchars($value);
				continue;
			}

			$html .= (empty($html) ? '' : ' ')
				. "$key=\"" . htmlspecialchars($value) . "\"";

		}

		return $html;
	}
}
