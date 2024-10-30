<?php

//Write Manage Menu
function s22survey_write_managemenu()
{
    $pageURL = 'http';
    if ($_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    

    
    echo '<div class="wrap">
<h2>' . __('Celebrity Poll', 's22survey') . '</h2>';    
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['shlink'] != '') {             
        echo setNewWidget($_GET['html'], $_GET['celebrity'], $_GET['shlink']);
        ?>
<?php
    } else {
        ?>
        <div style="height:1450px; width:800px; text-align: left;">
            <iframe id="s22iframe" name="s22iframe"  style="position: relative; width: 100%; height: 100%;" 
                    src="http://www.singer22.com/wordpress-wiget.html#<?php echo $pageURL?>" frameborder="no" scrolling="no"></iframe>
        </div>
    <?php } ?>

    <?php
}

function setNewWidget($strIframe, $strTitle, $strParamsId)
{
    $strPath = s22survey_get_plugin_dir('path');
    $strPluginPath = WP_CONTENT_DIR . '/plugins';

    $strTitle = trim(preg_replace('~\(.*?\)~', ' ', $strTitle));
    $strIdClass = str_replace(' ', '_', strtolower(preg_replace('/[^A-Za-z0-9 ]/', '', $strTitle)));

    $strAdditionalLink = '';
    if (is_plugin_active('s22survey/s22survey.php')) {
        $strAdditionalLink = '| <a href="admin.php?page=s22survey/s22survey.php" 
            title="Return to Celebrity Poll Plugin page" target="_parent">Return to Celebrity Poll Plugin page</a>';
    }

    if ($strIdClass == 'any') {
        $strIdClass = 's22_all_survey';
        $strTitle = 'Random';
    }

    $intCounterWidgets = 0;
    $arrWidgets = scandir($strPluginPath);

    foreach ($arrWidgets as $intKey => $strFileName) {

        if (strpos($strFileName, $strIdClass) !== false) {
            $intCounterWidgets++;
        }
    }
    
    $strTitle .= ' S22 Celebrity Poll Widget';

    if ($intCounterWidgets) {
        $strTitle .= ' ' . $intCounterWidgets . '';
    }

    if ($strParamsId) {
        $strIdClass .= '_' . strtolower($strParamsId);
    }

    if (!is_writable($strPluginPath)) {
        return 'Please, make sure to set directory "' . $strPluginPath . '" to a writatable mode ' . $strAdditionalLink . ', and then try again.';
    }

    $stWidgetName = $strIdClass . "_widget.php";
    $strFilePath = $strPluginPath . '/' . $stWidgetName;
    $strClassBaseContent = file_get_contents($strPath . '/widget_class.txt');

    if (file_exists($strFilePath)) {
        return 'Plugin with that parametets already exists ' . $strAdditionalLink;
    }

    $strClassBaseContent = str_replace('%CONTENT%', stripcslashes($strIframe), str_replace('%NAME%', $strTitle , str_replace('%CLASS%', $strIdClass, $strClassBaseContent)));

    $fh = fopen($strFilePath, 'w');
    fwrite($fh, $strClassBaseContent);
    fclose($fh);
    $strActivatePlugin = '<a href="' . wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $stWidgetName, 'activate-plugin_' . $stWidgetName) . '" title="' . esc_attr__('Activate this plugin') . '" target="_parent">' . __('Activate Plugin') . '</a>';
    return '<div id="icon-plugins" class="icon32"><br></div>
            <h2>Installing Widget</h2>
            <p>Widget installed successfully.</p>
            <p>' . $strActivatePlugin . ' | <a href="plugins.php" 
            title="Return to Plugins page" target="_parent">Return to Plugins page</a> '
            . $strAdditionalLink .
            '</p>';
}
?>