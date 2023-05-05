<?php

//Constante para crear el separador entre carpetas en una ruta, es \ para windows y / para linux
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

//Ruta principal del sitio web:
defined('SITE_ROOT') ? null : define('SITE_ROOT', 'D:'.DS."wamp".DS."www".DS."_personal".DS."gallery");

//Ruta hacia la carpeta de los includes:
defined('INCLUDES_PATH') ? null : define('INCLUDES_PATH', SITE_ROOT.DS.'admin'.DS.'includes');

require_once(INCLUDES_PATH.DS."functions.php");
require_once(INCLUDES_PATH.DS."new_config.php");
require_once(INCLUDES_PATH.DS."database.php");
require_once(INCLUDES_PATH.DS."db_object.php");
require_once(INCLUDES_PATH.DS."user.php");
require_once(INCLUDES_PATH.DS."photo.php");
require_once(INCLUDES_PATH.DS."session.php");
require_once(INCLUDES_PATH.DS."comment.php");
require_once(INCLUDES_PATH.DS."paginate.php");

?>