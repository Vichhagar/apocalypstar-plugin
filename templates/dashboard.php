<h1>Apocalypstar Plugin</h1>

<?php

$userLevel = new \Inc\Base\AdminUsers;
var_dump((int)$userLevel -> getUserLevel());
var_dump($userLevel -> getUserName());