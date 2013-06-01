<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
    <title>form</title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
</head>
<body>
    <form name="user" method="post" action="<?php echo $view['router']->generate('_behat_tests_submit');?>">
        <input id="name" type="text" name="name" />
        <input id="age" type="text" name="age" />
        <select id="speciality" name="speciality">
            <option value="programmer">Programmer</option>
            <option value="manager">Manager</option>
        </select>

        <input type="submit" value="Send spec info" />
    </form>
</body>
</html>
