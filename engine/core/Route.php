<? 

namespace Engine\Core;

class Route
{
    public static function init()
    {
        require_once 'engine/routes/Route.php';
        require_once 'engine/routes/web.php';
    }

    /**
     * Route function
     */
    public static function resource($pathRoute, $controllerRoute)
    {
        $pathArray = explode('/', $pathRoute);
        if($pathArray[0] == '') unset($pathArray[0]);

        $controllerData = explode('@', $controllerRoute);
        $selectedController = $controllerData[0];

        $selectedMethod = null;
        if(isset($controllerData[1])) $selectedMethod = $controllerData[1];

        $urlWithoutGet = explode('?', $_SERVER['REQUEST_URI'], 2);
        $routesArray = explode('/', $urlWithoutGet[0]);
        
        unset($routesArray[0]);

        if($routesArray[1] == $pathArray[1])
        {
            $controllerPath = 'engine/src/controllers/'.$selectedController.'.php';

            if(file_exists($controllerPath))
            {
                include $controllerPath;
            }
            else
            {
                echo('error');die;
            }

            $selectedControllerWithNamespace = '\Engine\Src\Controllers\\'.$selectedController;
            $enabledController = new $selectedControllerWithNamespace;

            if($selectedMethod == null)
            {
                if($_SERVER["REQUEST_METHOD"] == "POST")
                {
                    if(isset($_POST['_method']))
                    {
                        if(strtoupper($_POST['_method']) == "PUT")
                        {
                            if(method_exists($enabledController, 'update'))
                            {
                                unset($_POST['_method']);
                                return $enabledController->update((object)$_POST);
                            }
                            else
                            {
                                // method not found
                            }
                        }
                        else
                        {
                            if(strtoupper($_POST['_method']) == "DELETE")
                            {
                                if(method_exists($enabledController, 'destroy'))
                                {
                                    unset($_POST['_method']);
                                    return $enabledController->destroy((object)$_POST);
                                }
                                else
                                {
                                    // method not found
                                }
                            }
                            else
                            {
                                if(strtoupper($_POST['_method']) == "UPDATE")
                                {
                                    if(method_exists($enabledController, 'update'))
                                    {
                                        unset($_POST['_method']);
                                        return $enabledController->update((object)$_POST);
                                    }
                                    else
                                    {
                                        // method not found
                                    }
                                }
                            }
                        }
                    }
                    else
                    {
                        if(method_exists($enabledController, 'store'))
                        {
                            return $enabledController->store((object)$_POST);
                        }
                        else
                        {
                            // method not found
                        }
                    }
                }
                elseif($_SERVER['REQUEST_METHOD'] == "GET")
                {
                    if(empty($check))
                    {
                        if(method_exists($enabledController, 'index'))
                        {
                            return $enabledController->index();
                        }
                        else
                        {
                            // method not found
                        }
                    }
                    else
                    {
                        $data = explode('/', $check,3);
                        if($data[1] == 'edit')
                        {
                            if(method_exists($enabledController, 'edit'))
                            {
                                return $enabledController->edit((object)$data);
                            }
                            else
                            {
                                // method not found
                            }
                        }
                        elseif($data[0] == 'create')
                        {
                            if(method_exists($enabledController, 'create'))
                            {
                                return $enabledController->create((object)$data);
                            }
                            else
                            {
                                // method not found
                            }
                        }
                        elseif(method_exists($enabledController, 'show'))
                        {
                            return $enabledController->show((object)$data);
                        }
                        else
                        {
                            // method not found
                        }
                    }
                }
            }
            else
            {
                if(method_exists($enabledController, $selectedMethod))
                {
                    return $enabledController->$selectedMethod();
                }
                else
                {
                    // method not found
                }
            }
        }
        
        // View::display(json_encode(Array($pathRoute, $controllerRoute, $_SERVER['REQUEST_URI'])));

    }


}