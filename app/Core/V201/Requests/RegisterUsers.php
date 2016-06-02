<?php namespace app\Core\V201\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Validator;
use App\Core\V201\Traits\GetCodes;

/**
 * Class RegisterUsers
 * @package app\Core\V201\Requests
 */
class RegisterUsers extends Request
{
    use GetCodes;

    /**
     * RegisterUsers constructor.
     */
    public function __construct()
    {
        Validator::extend(
            'code_list',
            function ($attribute, $value, $parameters, $validator) {
                $listName = $parameters[1];
                $listType = $parameters[0];
                $codeList = $this->getCodes($listName, $listType);

                return in_array($value, $codeList);
            }
        );
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        $rules['first_name']        = 'required';
        $rules['last_name']         = 'required';
        $rules['email']             = 'required|email|unique:users,email';
        $rules['password']          = 'required|min:6';
        $rules['confirm_password']  = 'required|min:6|same:password';
        $rules['secondary_contact'] = 'required|unique:users,email';

        $rules = array_merge($rules, $this->getRulesForUsers($this->get('user')));

        return $rules;
    }

    /**
     * Get the Validation Error message
     * @return array
     */
    public function messages()
    {
        $messages = [];

        $messages['first_name.required']        = 'First Name is required.';
        $messages['last_name.required']         = 'Last Name is required.';
        $messages['email.required']             = 'Email is required.';
        $messages['email.email']                = 'Email is not valid.';
        $messages['email.unique']               = 'Email has already been taken.';
        $messages['password.required']          = 'Password is required.';
        $messages['password.min']               = 'Password must be at least 6 characters.';
        $messages['confirm_password.required']  = 'Confirm Password is required.';
        $messages['confirm_password.min']       = 'Confirm Password must be at least 6 characters.';
        $messages['confirm_password.same']      = 'Passwords doesn\'t match.';
        $messages['secondary_contact.required'] = 'Secondary Contact is required.';
        $messages['secondary_contact.unique']   = 'Secondary Contact Email has already been taken.';

        $messages = array_merge($messages, $this->getMessagesForUsers($this->get('user')));

        return $messages;
    }

    /**
     * return validation rules for users
     * @param $users
     * @return array
     */
    protected function getRulesForUsers($users)
    {
        $users = (array) $users;
        $rules = [];

        $dbRoles = \DB::table('role')->select('id')->whereIn('id', config('app.org_permissions'))->get();
        $roles   = [];
        foreach ($dbRoles as $role) {
            $roles[] = $role->id;
        }
        $roles = implode(',', $roles);

        foreach ($users as $userIndex => $user) {
            $rules[sprintf('user.%s.login_username', $userIndex)]  = 'required|unique:users,username';
            $rules[sprintf('user.%s.email', $userIndex)]           = 'required|email|unique:users,email';
            $rules[sprintf('user.%s.first_name', $userIndex)]      = 'required';
            $rules[sprintf('user.%s.last_name', $userIndex)]       = 'required';
            $rules[sprintf('user.%s.user_permission', $userIndex)] = 'required|code_list:Organization,Permissions';
        }

        return $rules;
    }

    /**
     * return validation messages for users
     * @param $users
     * @return array
     */
    protected function getMessagesForUsers($users)
    {
        $users    = (array) $users;
        $messages = [];

        foreach ($users as $userIndex => $user) {
            $messages[sprintf('user.%s.login_username.required', $userIndex)]   = 'Username is required.';
            $messages[sprintf('user.%s.login_username.unique', $userIndex)]     = 'Username has already been taken.';
            $messages[sprintf('user.%s.email.required', $userIndex)]            = 'Email is required.';
            $messages[sprintf('user.%s.email.email', $userIndex)]               = 'Email is not valid.';
            $messages[sprintf('user.%s.email.unique', $userIndex)]              = 'Email has already been taken.';
            $messages[sprintf('user.%s.first_name.required', $userIndex)]       = 'First Name is required.';
            $messages[sprintf('user.%s.last_name.required', $userIndex)]        = 'Last Name is required.';
            $messages[sprintf('user.%s.user_permission.required', $userIndex)]  = 'Role is required.';
            $messages[sprintf('user.%s.user_permission.code_list', $userIndex)] = 'Role is not valid.';
        }

        return $messages;
    }
}
