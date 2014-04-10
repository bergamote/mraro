<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= mra('title','',' | ').mra('site_title') ?></title>
</head>

<body>

<header>
<?= mra('site_title','<h1>','</h1>') ?>
</header>

<nav>
  <ul>
<?= makeMenu() ?>
  </ul>
</nav>

<article>
<?= $content ?>

</article>

<footer id="mra_footer">
<em><?= mra('site_title') ?><br>
<a href="mra/admin/">login</a>
</em>
</footer>

</body>
</html>
