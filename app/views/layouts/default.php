<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>DietCake <?php eh($title) ?></title>

    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/board-style.css" rel="stylesheet">
  </head>

  <body>

    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header"><a href="<?php eh(url('/')); ?>" class="navbar-brand">DietCake Board</a></div>
        </div>
    </nav>

    <div class="container">

      <?php echo $_content_ ?>

    </div>

    <script>
    console.log(<?php eh(round(microtime(true) - TIME_START, 3)) ?> + 'sec');
    </script>

  </body>
</html>
