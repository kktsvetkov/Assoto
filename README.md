# Assoto
Dynamically handles assets inserted into the header and the footer, allowing you to enqueue your JS and CSS files, your meta tags, etc.

## What's this for ?
Web performance best practices recommend that:

* load all your stylesheets in the ``<HEAD>...</HEAD>`` portion of the page. In this way the browser loads stylesheets early and will use them to correctly render the page. If this rule is not followed, you are going to see pages that change their look and layout in the middle of loading - which is when the stylesheets have been loaded.

* load all your Javascript at the bottom of the page just before the closing ``</BODY>`` tag. The ``<SCRIPT>`` tags block and freeze rendering of HTML by the browser, because potentially the Javascript can alter the contents of the page and modify its structure.  

Following these rules, you will often need to prepare Javascript scripts and files, stylesheets and meta tags *BEFORE* are you ready to render a page. This is what **Assoto** is meant to help with. It will collect your assets in advance, and then deliver them when you start printing your page.

## Stacks
While preparing to render a page, you start accumulating your assets. This happens by _stacking_ them in what is called "stacks". There are two stacks:

 - **"head"** (`Assoto\Stack::STACK_HEAD`) for the ``<HEAD>...</HEAD>`` portion of the page,
 - **"footer"** (`Assoto\Stack::STACK_FOOTER`) for the bottom of the page just before the closing ``</BODY>`` tag.

Following the best practices **Assoto** will put all your stylesheets in the "head", and all your scripts at the bottom in the "footer".

To see what's already added in a stack call `Assoto\Stack::stack($stack)`. If you want to reset a stack and empty it call `Assoto\Stack::reset($stack)`.

## How to use ?
You start by stacking your assets.
```php
	/* adds my.js as javascript file asset */
	Assoto\JS::file('my.js');

	/* adds my.css as stylesheet asset */
	Assoto\CSS::stylesheet('my.css');

	/* adds the <title> tag as asset */
	Assoto\HTML::title('Hello World!');
```

When it is time to render the page, you call to `Assoto\Stack::display()` to print the accumulated assets in the stacks. Here's an example with a very crude PHP page:
```php
<html>
<head><?php echo Assoto\Stack::display('head'); ?></head>
<body>

	<h1>Assoto Example</h1>

<?php echo Assoto\Stack::display('footer'); ?>
</body>
</html>
```

The rendered output will be
```html
<html>
<head><link href="my.css" rel="stylesheet" type="text/css"/><title>Hello World!</title></head>
<body>

	<h1>Assoto Example</h1>

<script type="text/javascript" src="my.js"/>
</body>
</html>
```

That's it. Very simple and straight-forward. This will work with any type of template engine you use, it will just require some additional code to pass the accumulated assets.

## Assets
There are several classes with _canned_ methods for adding different types of assets: stylesheets, scripts, meta tags and links.

Under the hood all of them are actually calling `Assoto\Stack::add()` method. Depending on the type of the asset, these canned method are adding some additional default values, and pointing towards the correct stack.

 - `Assoto\HTML::title("Hello World!")` - adds `<title>...</title>` tags to "head" assets stack  

 - `Assoto\CSS::style("body{background:#fff}")` - adds inline `<style>...</style>` block to "head" assets stack
 - `Assoto\CSS::stylesheet("my.css")` - adds CSS stylesheet "head" assets stack

 - `Assoto\JS::inline("console.log(somevar);")` - adds javascript inline `<script>...</script>` block to "footer" assets stack
 - `Assoto\JS::file("my.js")` - adds javascript file to "footer" assets stack

 - `Assoto\Meta::link('//assets-cdn.github.com', 'dns-prefetch')` - adds "dns-prefetch" link tag to "head" assets stack
 - `Assoto\Meta::canonical('http://github.com/kktsvetkov/assoto')` - adds canonical link element to "head" assets stack
 - `Assoto\Meta::icon('favicon.ico')` - adds icon link to the "head" assets stack

 - `Assoto\Meta::meta('keywords', 'assets, enqueue')` - adds meta tag for "keywords" to the "head" assets stack
 - `Assoto\Meta::property('og:type', 'website')` - adds meta tag with property attribute (used for Open Graph Protocol) to the "head" assets stack

## Identifying assets
Each asset added gets an "id". In some cases this identification is used to check if the assets already exists in a stack, and in some cases it is used to make sure that you only have one asset of that type.

You can manually set the "id" when calling the `Assoto\Stack::add()` method directly. Most of the canned methods also support providing assets "id" as one of their arguments. There are some that methods where the "id" argument is omitted on purpose since those assets can have only one instance - such as `Assoto\Meta::canonical()` and `Assoto\HTML::title()`

Let's have few assets stacked:
```php
	Assoto\HTML::title('Hello World!');
	Assoto\CSS::stylesheet('my.css');
```
Both of those assets are assigned to the "head" (`Assoto/Stack::STACK_HEAD`) stack. Let's have a look at that stack and see the "id" for each of those assets:
```php
print_r(Assoto\Stack::stack(Assoto\Stack::STACK_HEAD));
```

```bash
Array
(
    [css:my.css] => <link href="my.css" rel="stylesheet" type="text/css"/>
    [html:title] => <title>Hello World!</title>
)
```

Assets that are files (such as stylesheets and javascript files) have their urls used to compose their ids. This works as prevention for adding the same assets file multiple times. You can call as many times as you want to add the same asset, but in the end it will be added only once, and this is achieved by locking to its "id".

To check if an asset exists in a stack call `Assoto\Stack::exists($stack, $id)`. If you want to delete an already added asset call `Assoto\Stack::delete($stack, $id)`.

## More Examples
 - Inline favicon image
```php
 	Assoto\Meta::icon('data:image/jpeg;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAgCAYAAADnnNMGAAADDUlEQVRIib2WX2iOURzHP+/T0pKWTi4kLe1sN/Kvpfy74UaijJTRrI2xlVJkJ0YR0XAuKLsxtOxCmvwrVlwgf0fChRbLqSW5kE7ScqH15uL5PXvPu9737X1f5VtPzznf5/f7fc+f3/mdJ0WR8NrMAD4BCnihnF1RrG9UrKHYTpN2VQl+JYmESP8PkZLwX0QqkobXZg5wD6gEfgtdDfQqZw3wJ1cAr81ZoBX4KlQlMAVoUM6+h+yZdANzgRpgnjxVQKfXZq1y9mdgnxaB3cBeYHrgUyuD604Cp8R4rcwCYAB4CSwBtgj3DWgG+iTAbeC02CW4BrwClgGbhWtUzg6kvDYRcf7XAoPK2XXBUvTJUgCMysxUsDSz5d2rnO0I/B4BK4EvQF0EnBcBgC6y0QaMSHuOCCTBE4E3oYBgl7yrge4IWBB8bA4tlbNpYBP5z8V3YF0OfmbQXh0BjcCYEJ1em9ZJQh+AhhyB0sAq5ez3kPTaVAP3A2p/ro3/BQwRZ1JEnLoeWE+mrEC8Lw+IlzHBGHGG1Uj/gHL2TCoYwUngUI4Rl4sB5WwjBOdEOXsYeFxGsFz7NQI0JZ2KSR+bgA3ESzQOfATOAksLiFwCLgL14lMF3FXOjicGqTyOAHhtFgHvCtkAw8B8ycScyFsgpZY9D6gR4Ie0n8kDcSl6WmgUharwDWCqtD8DW8mk+jBxWiejX+61uVCSiDjUB1STcvYtMEv61cpZD2wPbNq9NjuLEvHa7ADaA6pLOfta7vgsKGf7gXMBddFrs7igiGz05YAaUM6eyjcgEdoHPAyo616bqaHNhKPXZgpwK/j2ITlMRWAT8XUAcQW4kVMEuEKmRPwRx6IgF1pov8ZrcyRLxGtzkMwFBbBROTtCCVDODgEtAXXMa7MeIPLabCO4KoGjytnBUgQCoX6gJ6DueG2WR2RX0ZvK2ePlCARCe4AnAVUfKWdPAB3EB6ztXwQCtBBf103K2Z5I1HuBhbKB/wzl7ChQp5y9CtmlfjyfU5lCE/HK/YMsya8U4zSZAjlWyHAy/gJwnedKs9gq+QAAAABJRU5ErkJggg==');
```
 - Hreflang alternative link tags
```php
	Assoto\Meta::hreflang('//example.com/en-us');
	Assoto\Meta::hreflang('//example.com/de-de', 'de-de');
	Assoto\Meta::hreflang('//example.com/en-ca', 'en-ca');
```
 - Open Graph Protocol
```php
	Assoto\Meta::property('og:image', 'https://avatars3.githubusercontent.com/u/694812?s=400&amp;v=4');
	Assoto\Meta::property('og:site_name', 'GitHub');
	Assoto\Meta::property('og:type', 'object');
	Assoto\Meta::property('og:title', 'kktsvetkov/Assoto');
	Assoto\Meta::property('og:url', 'https://github.com/kktsvetkov/Assoto');
	Assoto\Meta::property('fb:app_id', '1401488693436528');
```
 - Google Site Verification
```php
	Assoto\Meta::meta('google-site-verification', 'KT5gs8h0wvaagLKAVWq8bbeNwnZZK1r1XQysX3xurLU');
```
 - Atom Feed
```php
	Assoto\Meta::link(
		'https://github.com/kktsvetkov/Assoto/commits/master.atom',
		'alternative',
		'', // empty id, let the id be auto-generated
		array(
			'type' => 'application/atom+xml',
			'title' => 'Recent Commits to Assoto'
		));
```
 - Apple Touch Icons
```php
 	Assoto\Meta::link(
		'//assets-cdn.github.com/apple-touch-icon-114x114.png',
		'apple-touch-icon',
		'', // empty id, let the id be auto-generated
		array('sizes' => '114x114')
		);
```
 - Bootstrap Javascript File
```php
 Assoto\JS::file(
 	'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
 	'', // empty id, let the id be auto-generated
 	array(
 		'integrity' => 'sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa',
 	 	'crossorigin' => 'anonymous',
 		'async'
 	)
 );
```
