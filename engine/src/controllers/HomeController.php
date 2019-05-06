<?

namespace Engine\Src\Controllers;

use Illuminate\Database\Capsule\Manager as DB;
use Engine\Src\Models\Tasks;

class HomeController extends Controller
{
    function index()
    {
        isset($_GET['page']) ? $page = (int)$_GET['page'] : $page = 1;

        $perPage = 3; // Tasks per page

        if(isset($_GET['sortByName']))
        {
            $Tasks = Tasks::orderBy('user_name', htmlspecialchars($_GET['sortByName']))
                        ->offset(($page - 1) * $perPage)
                        ->limit($perPage)
                        ->get();
        }
        elseif(isset($_GET['sortByEmail']))
        {
            $Tasks = Tasks::orderBy('user_email', htmlspecialchars($_GET['sortByEmail']))
                        ->offset(($page - 1) * $perPage)
                        ->limit($perPage)
                        ->get();
        }
        elseif(isset($_GET['sortByStatus']))
        {
            $Tasks = Tasks::orderBy('is_completed', htmlspecialchars($_GET['sortByStatus']))
                        ->offset(($page - 1) * $perPage)
                        ->limit($perPage)
                        ->get();
        }
        else
        {
            $Tasks = Tasks::offset(($page - 1) * $perPage)
                        ->limit($perPage)
                        ->get();
        }

        $lastpage = ceil(Tasks::count()/3);

        isset($_GET['failauth']) ? $failauth = true : $failauth = false;

        $this->view->generate('task-list', ["failauth" => $failauth, "Tasks" => $Tasks, "Lastpage" => $lastpage]);
    }

    function update($data)
    {
        switch($data->action)
        {
            case "update":
                if(!$this->Auth->data->is_admin) return;

                $Tasks = Tasks::find((int)$data->id);
                $Tasks->user_name = htmlspecialchars($data->username);
                $Tasks->user_email = htmlspecialchars($data->email);
                $Tasks->title = htmlspecialchars($data->title);
                $Tasks->task = htmlspecialchars($data->task);
                $Tasks->save();
                $this->index();
            break;
            case "create":
                $Tasks = new Tasks;
                $Tasks->user_name = htmlspecialchars($data->username);
                $Tasks->user_email = htmlspecialchars($data->email);
                $Tasks->title = htmlspecialchars($data->title);
                $Tasks->task = htmlspecialchars($data->task);
                $Tasks->is_completed = false;
                $Tasks->save();
                $this->index();
            break;
            case "complite":
                if(!$this->Auth->data->is_admin) return;

                $Tasks = Tasks::find((int)$data->id);
                $Tasks->is_completed = true;
                $Tasks->save();
                $this->index();
            break;
        }
    }
}