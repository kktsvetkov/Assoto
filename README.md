# Assoto
Dynamically handles assets inserted into the header and the footer, allowing you to enqueue your JS and CSS files, your meta tags, etc.

## What's this for ?
Web performance best practices recommend that:

* load all your stylesheets in the ``<HEAD>...</HEAD>`` portion of the page. In this way the browser loads stylesheets early and will use them to correctly render the page. If this rule is not followed, you are going to see pages that change their look and layout in the middle of loading - which is when the stylesheets have been loaded.

* load all your Javascript at the bottom of the page just before the closing ``</BODY>`` tag. The ``<SCRIPT>`` tags block and freeze rendering of HTML by the browser, because potentially the Javascript can alter the contents of the page and modify its structure.  

Following these rules, you will often need to prepare Javascript scripts and files, stylesheets and meta tags *BEFORE* are you ready to render a page. This is what **Assoto** is meant to help with. It will collect your assets in advance, and then deliver them when you start printing your page.

## Stacks
While preparing to render a page, you start accumulating your assets. This happens by _stacking_ them in what is called "stacks". There are two stacks:

 - **"head"** (`Assoto/Stack::STACK_HEAD`) for the ``<HEAD>...</HEAD>`` portion of the page,
 - **"footer"** (`Assoto/Stack::STACK_FOOTER`) for the bottom of the page just before the closing ``</BODY>`` tag.

Following the best practices **Assoto** will put all your stylesheets in the "head", and all your scripts at the bottom in the "footer".

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

This will work with any type of template engine you use, it will just require some additional code to pass the accumulated assets.
