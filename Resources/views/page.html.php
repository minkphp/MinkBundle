<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
    <title>page</title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
</head>
<body>

    <?php echo $page;?>
    <div id="environment"><?php echo $environment;?></div>
    <ul>
        <li>
            <a href="<?php echo $view['router']->generate('_behat_tests_page', array('page' => 'page10'));?>">p10</a>
        </li>
        <li>
            <a href="<?php echo $view['router']->generate('_behat_tests_page', array('page' => 'page0'));?>">p0</a>
        </li>
        <li>
            <a href="<?php echo $genUrl;?>">p22</a>
        </li>
    </ul>
</body>
</html>
