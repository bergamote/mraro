<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= mra('title','',' | ').mra('site_title') ?></title>
  <link rel="stylesheet" type="text/css" href="<?= mra('theme_dir') ?>style.css" />     
  
</head>

<body>

<header id="mra_header">
<?= mra('site_title') ?>

</header>

<nav id="mra_menu">
  <ul>
<?= makeMenu(mra('title')) ?>
  </ul>
</nav>

<article>
<?= $content ?>
</article>

<footer id="mra_footer">
<em><?= mra('site_title') ?><br>
<?= mra('author') ?><br>
<a href="mra/admin/">login</a>
</em>
</footer>

</body>
</html>
