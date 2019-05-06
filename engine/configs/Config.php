<?

namespace Engine\Configs;

class Main
{
    const Author    = Array(
        'nickname'  => 'soldier.x-fighter',
        'email'     => 'soldier.x-fighter@yandex.ru'
    );
}

class Database
{
    const driver    = 'mysql';
    const host      = 'localhost';
    const database  = 'kurumi_tokirumi';
    const username  = 'kurumi_tokirumi';
    const password  = '127001';
    const charset   = 'utf8';
    const collation = 'utf8_unicode_ci';
    const prefix    = '';
}