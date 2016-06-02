<?php namespace App\Services;

use App\Core\V201\Repositories\Organization\OrganizationRepository;
use App\Models\Organization\Organization;
use App\User;
use Illuminate\Contracts\Logging\Log as Logger;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class Registration
 * @package App\Services
 */
class Registration
{
    /**
     * @var Logger
     */
    protected $logger;
    /**
     * @var OrganizationRepository
     */
    protected $orgRepo;
    /**
     * @var DatabaseManager
     */
    protected $database;

    /**
     * @param Logger                 $logger
     * @param DatabaseManager        $database
     * @param OrganizationRepository $orgRepo
     */
    public function __construct(Logger $logger, DatabaseManager $database, OrganizationRepository $orgRepo)
    {
        $this->logger   = $logger;
        $this->orgRepo  = $orgRepo;
        $this->database = $database;
    }

    /**
     * registers organization info and users
     * @param $orgInfo
     * @param $users
     * @return array|bool
     */
    public function register($orgInfo, $users)
    {
        try {
            $this->database->beginTransaction();
            $organization = $this->saveOrganization($orgInfo);
            $users        = $this->saveUsers($users, $organization);
            $this->database->commit();

            return $organization;
        } catch (\Exception $exception) {
            $this->database->rollback();
            $this->logger->error($exception, ['org_info' => $orgInfo, 'users' => $users]);
        }

        return false;
    }

    /**
     * saves organization and return organization model
     * @param $orgInfo
     * @return Organization
     */
    protected function saveOrganization($orgInfo)
    {
        $orgData = $this->prepareOrganization($orgInfo);

        return $this->orgRepo->createOrganization($orgData);
    }

    /**
     * returns mapped organization data
     * @param $orgInfo
     * @return array
     */
    protected function prepareOrganization($orgInfo)
    {
        $orgData = [];

        $orgData['name']                = $orgInfo['organization_name'];
        $orgData['user_identifier']     = $orgInfo['organization_name_abbr'];
        $orgData['address']             = $orgInfo['organization_address'];
        $orgData['country']             = $orgInfo['country'];
        $orgData['registration_agency'] = $orgInfo['organization_registration_agency'];
        $orgData['registration_number'] = $orgInfo['registration_number'];
        $orgData['reporting_org']       = [
            [
                "reporting_organization_identifier" => sprintf('%s-%s', $orgInfo['organization_registration_agency'], $orgInfo['registration_number']),
                "reporting_organization_type"       => $orgInfo['organization_type'],
                "narrative"                         => [
                    [
                        "narrative" => $orgInfo['organization_name'],
                        "language"  => ""
                    ]
                ]
            ]
        ];

        return $orgData;
    }

    /**
     * saves organization users
     * @param $users
     * @param $organization Organization
     * @return array
     */
    protected function saveUsers($users, $organization)
    {
        $users['role']     = '1';
        $users['username'] = sprintf('%s_admin', $organization->user_identifier);
        $admin             = $this->prepareUser($users);
        $secondary         = $this->prepareUser(['email' => $users['secondary_contact'], 'username' => $users['secondary_contact'], 'role' => '5']);
        $users             = $this->getUsers(getVal($users, ['user'], []), $organization->user_identifier);
        $allUsers          = array_merge([$admin, $secondary], $users);

        return $organization->users()->createMany($allUsers);
    }

    /**
     * returns prepared users
     * @param $users
     * @param $userIdentifier
     * @return array
     */
    protected function getUsers($users, $userIdentifier)
    {
        $orgUsers = [];
        foreach ($users as $user) {
            $user['role']     = 2;
            $user['username'] = sprintf('%s_%s', $userIdentifier, $user['username']);
            $orgUsers[]       = $this->prepareUser($user);
        }

        return $orgUsers;
    }

    /**
     * returns prepared user data
     * @param $user
     * @return array
     */
    protected function prepareUser($user)
    {
        $userData = [];

        $userData['first_name'] = getVal($user, ['first_name']);
        $userData['last_name']  = getVal($user, ['last_name']);
        $userData['email']      = getVal($user, ['email']);
        $userData['username']   = getVal($user, ['username']);
        $password               = getVal($user, ['password']);
        $password               = $password ? bcrypt($password) : '';
        $userData['password']   = $password;
        $userData['role_id']    = getVal($user, ['role']);
        $permission_roles       = getVal($user, ['user_permission']);
        $permission_roles       = $permission_roles ? explode(',', $permission_roles) : [];

        $permissions = [];
        foreach ($permission_roles as $role) {
            $role                  = explode(':', $role);
            $permissions[$role[0]] = $role[1];
        }
        $permissions ?: $permissions = null;
        $userData['user_permission'] = $permissions;


        return $userData;
    }

    /**
     * return collection of similar organizations
     * @param $orgName
     * @return Collection
     */
    public function getSimilarOrg($orgName)
    {
        $ignoreList      = ['and', 'of', 'the', 'an', 'a'];
        $orgNameWordList = preg_split('/[\ ]+/', strtolower($orgName));
        $keywords        = array_filter(
            $orgNameWordList,
            function ($value) use ($ignoreList) {
                return !in_array($value, $ignoreList);
            }
        );

        return $this->orgRepo->getSimilarOrg($keywords);
    }

    /**
     * return similar organization array with admin email and organization name
     * @param $similarOrg
     * @return array
     */
    public function prepareSimilarOrg($similarOrg)
    {
        $similarOrgList = [];
        foreach ($similarOrg as $org) {
            $orgName                     = $org->reporting_org[0]['narrative'][0]['narrative'];
            $adminEmail                  = $org->users->where('role_id', 1)->first()->email;
            $similarOrgList[$adminEmail] = sprintf('%s - %s', $orgName, $adminEmail);
        }

        return $similarOrgList;
    }
}
