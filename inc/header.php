<?php

class Header {
/**
 * @param $page Page
 */
function __construct($page) {
    $this->page = $page;
    if ($page->settings->header_show_totals) {
        $t = $page->settings->table;
        $t_bans = $t['bans'];
        $t_mutes = $t['mutes'];
        $t_warnings = $t['warnings'];
        $t_kicks = $t['kicks'];
        try {
            $st = $page->conn->query("SELECT
            (SELECT COUNT(*) FROM $t_bans) AS c_bans,
            (SELECT COUNT(*) FROM $t_mutes) AS c_mutes,
            (SELECT COUNT(*) FROM $t_warnings) AS c_warnings,
            (SELECT COUNT(*) FROM $t_kicks) AS c_kicks");
            ($row = $st->fetch(PDO::FETCH_ASSOC)) or die('Failed to fetch row counts.');
            $this->count = array(
                'bans.php'     => $row['c_bans'],
                'mutes.php'    => $row['c_mutes'],
                'warnings.php' => $row['c_warnings'],
                'kicks.php'    => $row['c_kicks'],
            );
        } catch (PDOException $ex) {
            Settings::handle_error($page->settings, $ex);
        }
    }
}

function navbar($links) {
    echo '<ul class="nav navbar-nav">';
    foreach ($links as $page => $title) {
        $li = "li";
        if ((substr($_SERVER['SCRIPT_NAME'], -strlen($page))) === $page) {
            $li .= ' class="active"';
        }
        if ($this->page->settings->header_show_totals && isset($this->count[$page])) {
            $title .= " <span class=\"badge\">";
            $title .= $this->count[$page];
            $title .= "</span>";
        }
        echo "<$li><a href=\"$page\">$title</a></li>";
    }
    echo '</ul>';
}

function print_header() {
$settings = $this->page->settings;
?>
<!DOCTYPE html>
<html lang="en">
<head>

  <!-- Material Design fonts -->
  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <!-- Bootstrap -->
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Material Design -->
  <link href="inc/dist/css/bootstrap-material-design.css" rel="stylesheet">
  <link href="inc/dist/css/ripples.min.css" rel="stylesheet">
  <script src="inc/js/jquery.min.js"></script>
<script src="inc/js/bootstrap.min.js"></script>
<script src="inc/js/ripples.min.js"></script>
<script src="inc/js/rippples.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="LiteBans">
    <link rel="shortcut icon" href="inc/img/minecraft.ico">
    <!-- CSS -->
    <link href="inc/css/custom.css" rel="stylesheet">
    <script type="text/javascript">
        function withjQuery(f) {
            if (window.jQuery) f();
            else window.setTimeout(function () {
                withjQuery(f);
            }, 100);
        }
    </script>
</head>

<header class="navbar navbar-default navbar-static-top" role="banner">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#litebans-navbar" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo $settings->name_link; ?>">
                <?php echo $settings->name; ?>
            </a>
        </div>
        <nav id="litebans-navbar" class="collapse navbar-collapse">
            <?php
            $this->navbar(array(
                "index.php"    => $this->page->lang->header_index,
                "bans.php"     => $this->page->lang->header_bans,
                "mutes.php"    => $this->page->lang->header_mutes,
                "warnings.php" => $this->page->lang->header_warnings,
                "kicks.php"    => $this->page->lang->header_kicks,
            ));
            ?>
            <div class="nav navbar-nav navbar-right">
                <p class="navbar-text">
                Designed by <a href="http://funkemunky.enjin.com" style="color: black;">funkemunky</a>, UI by <a href="http://github.com/ruany" style="color: black;">Ruan</a>.
				</p>
            </div>
        </nav>
    </div>
</header>
<?php
}
}
?>
