<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= mra('title','',' | ').mra('site_title') ?></title>
  <link rel="stylesheet" type="text/css" href="<?= mra('theme_dir') ?>style.css" />     
  <!--[if lt IE 9]>
  <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/r29/html5.min.js"></script>
  <![endif]-->
</head>

<body>

<div id="wrap">


<header class="mra">
<?= mra('site_title') ?>
<nav class="mra">
  <ul>
<?= makeMenu(mra('title')) ?>
  </ul>
</nav>
</header>

<article>
<?= $content ?>
</article>

<aside class="mra">
<?= mra('sidebar') ?>
</aside>

<span class="clear-fix"></span>
<footer class="mra small lighter">
 powered by <a href="http://mraro.com" target="_blank">MrAro</a> -
 <a href="mra/admin/">login</a>
</footer>

</div><!-- Close #wrap -->
</body>
</html>
