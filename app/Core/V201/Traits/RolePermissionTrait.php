<?php namespace App\Core\V201\Traits;

use App\Models\Role;
use Illuminate\Support\Facades\Auth;

trait RolePermissionTrait
{
    protected $mappings = [
        'GET'    => [],
        'POST'   => ['add'],
        'PUT'    => ['edit'],
        'PATCH'  => ['edit'],
        'DELETE' => ['delete']
    ];

    protected function getRolesOfUser($roleId)
    {
        $role = Role::find($roleId);

        return json_decode($role->permissions);
    }

    public function checkPermission($route)
    {
        $namespace   = $route->getAction()['namespace'];
        $bool        = false;
        $currentUser = Auth::user();
        $role        = $this->getRolesOfUser($currentUser->role_id);

        if ($route->uri() == "activity" || $currentUser->role_id == 1 || $currentUser->role_id == 3 || $currentUser->role_id == 4) {
            return true;
        }

        if ($namespace == "App\\Http\\Controllers\\Complete\\Activity" || $namespace == "App\\Http\\Controllers\\Complete\\Organization") {
            $maps = $this->mappings[$route->getMethods()[0]];

            if (emptyArray($maps) && !emptyArray($role)) {
                $bool = true;
            } else {
                if ($role != []) {
                    foreach ($role as $rol) {
                        if (in_array($rol, $maps)) {
                            $bool = true;
                        }
                    }
                } elseif ($route->getMethods()[0] == "GET") {
                    $bool = true;
                }
            }
        } else {
            $controller     = $route->getAction()['controller'];
            $explode        = explode('\\', explode('@', $controller)[0]);
            $controllerName = $explode[count($explode) - 1];

            if ($controllerName == "SettingsController" && in_array('settings', $role)) {
                $bool = true;
            } elseif ($controllerName == "WorkflowController" && in_array('publish', $role)) {
                $bool = true;
            } elseif ($controllerName == "UpgradeController" && $currentUser->isAdmin()) {
                $bool = true;
            } elseif ($controllerName == "UserLogController" && $currentUser->isAdmin()) {
                $bool = true;
            } elseif ($route->uri() == "document/{id}/delete" && in_array('delete', $role)) {
                $bool = true;
            } elseif ($controllerName == "DocumentController" && $route->uri() != "document/{id}/delete") {
                $bool = true;
            } elseif ($controllerName == "DownloadController") {
                $bool = true;
            }
        }

        return $bool;
    }
}
