<?

namespace Engine\Core;

use Engine\Configs\Database as DB_Config;
use Illuminate\Database\Capsule\Manager as DB;

class Database
{
    static function init()
    {
        $db = new DB;

        $db->addConnection([
            'driver'    => DB_Config::driver,
            'host'      => DB_Config::host,
            'database'  => DB_Config::database,
            'username'  => DB_Config::username,
            'password'  => DB_Config::password,
            'charset'   => DB_Config::charset,
            'collation' => DB_Config::collation,
            'prefix'    => DB_Config::prefix,
        ]);

        $db->setAsGlobal();
        $db->bootEloquent();
    }
}