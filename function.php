<?php
function checkinstall($config)
{
    if(!isset($config['installed']))
    {
        header("Location: install/");
        exit;
    }
}
?>