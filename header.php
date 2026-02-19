<?php if (!defined("__TYPECHO_ROOT_DIR__")) {exit();} ?>

<head>
    <meta charset="UTF-8"/>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php if ($this->is("post") || $this->is("page") || $this->is("attachment")): ?>
        <meta name="keywords" content="<?php
        $k = $this->fields->keyword;
        if (empty($k)) {
          echo $this->keywords();
        } else {
          echo $k;
        }
        ?>">
        <meta name="description" content="<?php
        $d = $this->fields->description;
        if (empty($d) || !$this->is("single")) {
          if ($this->getDescription()) {
            echo $this->getDescription();
          }
        } else {
          echo $d;
        }
        ?>"/>
    <?php endif; ?>
    <title><?php
    $this->archiveTitle(
      [
        "category" => _t("%s"),
        "search" => _t("%s"),
        "tag" => _t("%s"),
        "author" => _t("%s"),
      ],
      "",
      " - "
    );
    $this->options->title();
    ?> - 知识人间烟火</title>
    <?php $this->header("description=&generator=&pingback=&template=&xmlrpc=&wlw=&commentReply=&keywords="); ?>
    <link rel="dns-prefetch" href="https://npm.elemecdn.com" />
    <style>
      <?php if (getOptions()->themeColor == "1"): ?>
        :root {
          --primary-bg: #a6c4c2;
          --link-color: #77b3af;
          --link-hover-color: #77b3af;
        }
        <?php elseif (getOptions()->themeColor == "2"): ?>
          :root{
            --primary-bg: #feae51;
          --link-color: #f08409;
          --link-hover-color: #f08409;
          }
          <?php elseif (getOptions()->themeColor == "3"): ?>
          :root{
            --primary-bg: #a2c6e1;
          --link-color: #668aa5;
          --link-hover-color: #668aa5;
          }
          <?php elseif (getOptions()->themeColor == "4"): ?>
          :root{
            --primary-bg: rgb(239 68 68);
          --link-color: rgb(239 68 68);
          --link-hover-color: rgb(239 68 68);
          }
      <?php else: ?>
        :root {
          --primary-bg: #000;
          --link-hover-color: #000;
        }
      <?php endif; ?>
    </style>
    <link type="text/css" rel="stylesheet" href="<?php $this->options->themeUrl(
      "assets/dist/style.css?v=" . getThemeVersion()); ?>"/>
    <link type="text/css" rel="stylesheet" href="https://qcm.xgsd.cc/css/iconfont.css">
    <link rel="shoucut icon" href="/favicon.ico">
    <script async src="https://qcm.xgsd.cc/js/SmoothScroll.min.js"></script>
    <script async src="https://npm.elemecdn.com/iconify-icon@1.0.7/dist/iconify-icon.min.js"></script>
    <script src="<?php $this->options->themeUrl("assets/dist/jasmine.iife.js"); ?>"></script>
    <style>
        <?php $this->options->customStyle(); ?>
    </style>
</head>
