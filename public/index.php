<?php

    require_once(dirname(__DIR__).'/app/core/AutoLoad.php');
    new AutoLoad();
	$app = new App();
	$app->run();
?>