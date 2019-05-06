<?

namespace Engine\Src\Controllers;

use Engine\Src\Models\Users;

class AuthController extends Controller
{
    function index()
    {
        if(isset($_GET['logout'])) $this->Auth->logout();
    }

    function store($data)
    {
        $this->Auth->login(htmlspecialchars($data->login), htmlspecialchars($data->password));
    }
}