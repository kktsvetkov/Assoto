<?php /**
* Autoload helper, if used outside Composer
*/
function Assoto_autoload($class)
{
	$c = explode('\\', $class);
	if (($c[0] == 'Assoto'))
	{
		array_shift($c);
		$_ = __DIR__ . '/src/' . join('/', $c) . '.php';
		if (file_exists($_))
		{
			include $_;
		}
	}
}
spl_autoload_register('Assoto_autoload');
