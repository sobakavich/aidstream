<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class UserPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:userPermission {method} {pageNo=1} {--verify}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new user permission from roles and users table';
    protected $user;

    /**
     * Create a new command instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        parent::__construct();
        $this->user = $user;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $method = $this->argument('method');

        if (method_exists($this, $method)) {
            $this->$method();
        }
    }

    protected function userPermissionExcel()
    {
        Excel::create(
            'userInfo',
            function ($excel) {

                $excel->sheet(
                    'userInfo',
                    function ($sheet) {

                        $sheet->fromArray($this->userInfoData());

                    }
                );

            }
        )->store('xls');

        $this->info('User Permission Excel has been generated.');
    }

    protected function userInfoData()
    {
        $users    = $this->user->getAllUsers();
        $userInfo = [];

        foreach ($users as $user) {
            $userPermission = '';
            if (!is_null($user->user_permission)) {
                $userPermission = (array_key_exists('add', $user->user_permission) && $user->user_permission['add'] != "") ? $user->user_permission['add'] : '';
                $userPermission .= (array_key_exists('edit', $user->user_permission) && $user->user_permission['edit'] != "") ? ',' . $user->user_permission['edit'] : '';
                $userPermission .= (array_key_exists('delete', $user->user_permission) && $user->user_permission['delete'] != "") ? ',' . $user->user_permission['delete'] : '';
                $userPermission .= (array_key_exists('publish', $user->user_permission) && $user->user_permission['publish'] != "") ? ',' . $user->user_permission['publish'] : '';
            }

            $userInfo[] = [
                'User Id'         => $user->id,
                'Username'        => $user->username,
                'Email'           => $user->email,
                'Org Id'          => $user->org_id,
                'Role Id'         => $user->role_id,
                'User Permission' => $userPermission
            ];
        }

        return $userInfo;
    }

    protected function saveNewRoleId()
    {
        $users = $this->user->getAllUsers();

        foreach ($users as $user) {
            $permission = $user->user_permission;
            if (!is_null($permission)) {
                if (array_key_exists('publish', $permission) && $permission['publish'] != "") {
                    $user->role_id = 2;
                } elseif (!array_key_exists('publish', $permission) && !isEmpty($permission)) {
                    $user->role_id = 6;
                } else {
                    $user->role_id = 7;
                }

                $user->save();
            }
        }
    }
}
