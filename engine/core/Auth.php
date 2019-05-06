<?

namespace Engine\Core;

use \Engine\Src\Models\Sessions;
use \Engine\Src\Models\Users;

class Auth
{
    public $authorized = false;
    public $session = Array();
    public $data;

    public $cookieLifeTime = 2764800;

    function __construct()
	{
        if(isset($_COOKIE['session']))
        {
            $Sessions = Sessions::where('session_key', $_COOKIE['session'])->where('ip', '=', Auth::get_client_ip())->first();
            if($Sessions)
            {
                $this->authorized = true;
                $this->get_data($Sessions->user_id);
            }
            else
            {
                $this->authorized = false;
                $this->data = (object)Array('id' => 0);
            }
        }
        else
        {
            $this->data = (object)Array('id' => 0);
        }

        if(isset($_GET['logout']))
        {
            $this->logout();
        }
    }

    private function get_data($user_id)
    {
        $Users = Users::find((int)$user_id);
        $this->data = $Users;
    }

    public function logout()
    {
        $this->remove_sessions($this->data->id, Auth::get_client_ip());

        $this->authorized = false;
        $this->data = (object)Array('id' => 0);
        header('Refresh: 0; url=/');
        setcookie("session", "", time()-$this->cookieLifeTime);
    }   

    public function hard_logout()
    {
        $this->remove_sessions($this->data->id);

        $this->authorized = false;
        $this->data = (object)Array('id' => 0);
        header('Refresh: 0; url=/');
        setcookie("session", "", time()-$this->cookieLifeTime);
    }

    public function login($email, $password)
    {
        if($this->data->id != 0) return;

        if(!filter_var($email,FILTER_VALIDATE_EMAIL))
        {
            $Users = Users::where('password', md5(md5($password)))->where('name', $email)->first();
        }
        else
        {
            $Users = Users::where('password', md5(md5($password)))->where('email', $email)->first();
        }
        
        if(isset($Users->id))
        {
            $this->remove_sessions($Users->id, Auth::get_client_ip());
            $ss_key = Auth::generateRandomString();
            setcookie("session", $ss_key, time()+$this->cookieLifeTime);

            $Sessions = new Sessions;
            $Sessions->user_id = $Users->id;
            $Sessions->ip = Auth::get_client_ip();
            $Sessions->session_key = $ss_key;
            $Sessions->save();

            $this->authorized = true;
            $this->get_data($Users->id);
            header('Refresh: 0; url=/');
        }
        else
        {
            header('Refresh: 0; url=/?failauth');
        }
    }

    public function remove_sessions($user_id, $user_ip)
    {
        $Sessions = Sessions::where('user_id','=',$user_id)->where('ip','=',$user_ip)->delete();
    }

    static function get_client_ip()
    {
        $ipaddress = '';
        if(getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    static function generateRandomString($length = 32)
    {
        return md5(substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length));
    }
}