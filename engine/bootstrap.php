<?

namespace Engine;

require_once 'configs/Config.php';
require_once 'core/Database.php';
require_once 'core/Route.php';
require_once 'core/Controller.php';
require_once 'src/controllers/Controller.php';
require_once 'core/Model.php';
require_once 'core/View.php';
require_once 'core/Auth.php';

require_once "vendor/autoload.php";

foreach (glob('engine/src/models/*.php') as $model)
{
    require_once $model;
}

core\Database::init();

core\Route::init();