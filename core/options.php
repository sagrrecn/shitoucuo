<?php

use Typecho\Common;
use Typecho\Widget\Helper\Form\Element\Checkbox;
use Typecho\Widget\Helper\Form\Element\Text;
use Typecho\Widget\Helper\Form\Element\Textarea;
use Typecho\Widget\Helper\Form\Element\Radio;
use Utils\Helper;
use Widget\Notice;
use Widget\Options;

if (!defined("__TYPECHO_ROOT_DIR__")) {
  exit();
}

/**
 * 主题配置
 * @param $form
 * @return void
 */
function themeConfig($form)
{
  $icon = new Text(
    "icon",
    null,
    null,
    _t("Favicon"),
    _t("图片地址, 浏览器标签栏显示ICON，默认值为站点路径下 /favicon.ico")
  );
  $form->addInput($icon);

  $logoUrl = new Text("logoUrl", null, null, _t("LOGO"), _t("图片地址, 用于显示站点 LOGO ，留空不显示"));
  $form->addInput($logoUrl);
  $stickyPost = new Text(
    "stickyPost",
    null,
    null,
    "置顶文章",
    "格式：文章的ID || 文章的ID || 文章的ID"
  );
  $form->addInput($stickyPost);
  $avatarWebsite = new Radio(
    "avatarWebsite",
    [
      "gravatar" => _t("Gravatar"),
      "qq" => _t("QQ 头像"),
    ],
    "gravatar",
    _t("设置显示头像优先级"),
    _t("默认通过邮箱，获取 Gravatar 头像")
  );
  $customStyle = new Textarea("customStyle", null, null, "自定义样式", "不需要添加 &lt;style&gt; 标签");
  $form->addInput($customStyle);

  $customScript = new Textarea("customScript", null, null, "自定义脚本", "不需要添加 &lt;script&gt; 标签");
  $form->addInput($customScript);
    backupThemeData();
}


/**
 * 备份主题数据
 * @return void
 */
function backupThemeData()
{
  $name = "sagrre";//注意修改主题名称，这里也要跟着修改；
  $db = Typecho_Db::get();
  if (isset($_POST["type"])) {

    if ($_POST["type"] == "创建备份") {
      $value = $db->fetchRow(
        $db
          ->select()
          ->from("table.options")
          ->where("name = ?", "theme:" . $name)
      )["value"];
      if (
        $db->fetchRow(
          $db
            ->select()
            ->from("table.options")
            ->where("name = ?", "theme:" . $name . "_backup")
        )
      ) {

        $db->query(
          $db
            ->update("table.options")
            ->rows(["value" => $value])
            ->where("name = ?", "theme:" . $name . "_backup")
        );
        Notice::alloc()->set("备份更新成功", "success");
        Options::alloc()->response->redirect(Common::url("options-theme.php", Options::alloc()->adminUrl));
        ?>
                <?php
      } else {
         ?>
                <?php if ($value) {

                  $db->query(
                    $db
                      ->insert("table.options")
                      ->rows(["name" => "theme:" . $name . "_backup", "user" => "0", "value" => $value])
                  );
                  Notice::alloc()->set("备份成功", "success");
                  Options::alloc()->response->redirect(Common::url("options-theme.php", Options::alloc()->adminUrl));
                  ?>
                    <?php
                }
      }
    }
    if ($_POST["type"] == "还原备份") {
      if (
        $db->fetchRow(
          $db
            ->select()
            ->from("table.options")
            ->where("name = ?", "theme:" . $name . "_backup")
        )
      ) {

        $_value = $db->fetchRow(
          $db
            ->select()
            ->from("table.options")
            ->where("name = ?", "theme:" . $name . "_backup")
        )["value"];
        $db->query(
          $db
            ->update("table.options")
            ->rows(["value" => $_value])
            ->where("name = ?", "theme:" . $name)
        );
        Notice::alloc()->set("备份还原成功", "success");
        Options::alloc()->response->redirect(Common::url("options-theme.php", Options::alloc()->adminUrl));
        ?>
                <?php
      } else {

        Notice::alloc()->set("无备份数据，请先创建备份", "error");
        Options::alloc()->response->redirect(Common::url("options-theme.php", Options::alloc()->adminUrl));
        ?>
                <?php
      } ?>
            <?php
    }
    ?>
        <?php if ($_POST["type"] == "删除备份") {
          if (
            $db->fetchRow(
              $db
                ->select()
                ->from("table.options")
                ->where("name = ?", "theme:" . $name . "_backup")
            )
          ) {

            $db->query($db->delete("table.options")->where("name = ?", "theme:" . $name . "_backup"));
            Notice::alloc()->set("删除备份成功", "success");
            Options::alloc()->response->redirect(Common::url("options-theme.php", Options::alloc()->adminUrl));
            ?>
                <?php
          } else {

            Notice::alloc()->set("无备份数据，无法删除", "success");
            Options::alloc()->response->redirect(Common::url("options-theme.php", Options::alloc()->adminUrl));
            ?>
                <?php
          } ?>
            <?php
        } ?>
        <?php
  }
  ?>

    </form>
    <?php echo '<br/><div class="message error">请先点击右下角的保存设置按钮，创建备份！<br/><br/><form class="backup" action="?jasmine_backup" method="post">
    <input type="submit" name="type" class="btn primary" value="创建备份" />
    <input type="submit" name="type" class="btn primary" value="还原备份" />
    <input type="submit" name="type" class="btn primary" value="删除备份" /></form></div>';
}
/**
 * 输出所有分类
 * @return void
 */
function getCategoryies()
{
  $db = Typecho_Db::get();
  $prow = $db->fetchAll(
    $db
      ->select()
      ->from("table.metas")
      ->where("type = ?", "category")
  );
  $text = "";
  foreach ($prow as $item) {
    $text .= $item["name"] . "(" . $item["mid"] . ")" . "&nbsp;&nbsp;&nbsp;&nbsp;";
  }
  return $text;
}
