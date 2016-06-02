<?php namespace App\Http\Controllers\Auth;

use App\Core\Form\BaseForm;
use App\Http\Controllers\Controller;
use App\Services\Registration;
use App\Services\RequestManager\RegisterOrganization;
use App\Services\RequestManager\RegisterUsers;
use App\Services\Verification;

/**
 * Class RegistrationController
 * @package App\Http\Controllers\Auth
 */
class RegistrationController extends Controller
{
    /**
     * @var BaseForm
     */
    protected $baseForm;
    /**
     * @var Registration
     */
    protected $registrationManager;
    /**
     * @var Verification
     */
    protected $verificationManager;

    /**
     * @param BaseForm     $baseForm
     * @param Registration $registrationManager
     * @param Verification $verificationManager
     */
    public function __construct(BaseForm $baseForm, Registration $registrationManager, Verification $verificationManager)
    {
        $this->middleware('guest', ['except' => 'getLogout']);
        $this->baseForm            = $baseForm;
        $this->registrationManager = $registrationManager;
        $this->verificationManager = $verificationManager;
    }

    /**
     * returns organization registration view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showOrgForm()
    {
        $orgType      = $this->baseForm->getCodeList('OrganizationType', 'Organization', false);
        $countries    = $this->baseForm->getCodeList('Country', 'Organization', false);
        $orgRegAgency = $this->baseForm->getCodeList('OrganisationRegistrationAgency', 'Organization', false);

        return view('auth.organization', compact('orgType', 'countries', 'orgRegAgency'));
    }

    /**
     * saves organization and redirect to user registration page
     * @param RegisterOrganization $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveOrganization(RegisterOrganization $request)
    {
        $orgInfo = request()->except('_token');
        session()->put('org_info', $orgInfo);

        $similarOrg = $this->registrationManager->getSimilarOrg($orgInfo['organization_name']);

        if ($similarOrg->count()) {
            return redirect()->route('registration.similar-organizations')->withSimilarOrg($similarOrg);
        }

        return redirect()->route('registration.users');
    }

    /**
     * returns users registration view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showUsersForm()
    {
        $orgInfo = session('org_info');

        if (!$orgInfo) {
            return redirect()->route('registration.organization');
        }

        $roles = $this->baseForm->getCodeList('Permissions', 'Organization', false);

        return view('auth.users', compact('roles'));
    }

    /**
     * save organization info and users
     * @param RegisterUsers $request
     * @return mixed
     */
    public function completeRegistration(RegisterUsers $request)
    {
        $users = request()->except('_token');
        session()->put('org_users', $users);
        $orgInfo = session('org_info');

        if ($organization = $this->registrationManager->register($orgInfo, $users)) {
            return $this->postRegistration($organization);
        } else {
            $response = ['type' => 'danger', 'code' => ['failed_registration']];

            return redirect()->back()->withInput()->withResponse($response);
        }
    }

    /**
     * sends emails to users after registration
     * @param $organization
     * @return mixed
     */
    protected function postRegistration($organization)
    {
        session()->forget('org_info');
        session()->forget('users');
        $user = $organization->users->where('role_id', 1)->first();
        $this->verificationManager->sendVerificationEmail($user);

        return redirect()->to('/auth/login')->withMessage(
            sprintf(
                'A verification email has been sent to %s. Please check your email inbox and click on the link in the email to verify your email address.',
                $user->email
            )
        );
    }

    public function showSimilarOrganizations()
    {
        $similarOrg = $this->registrationManager->prepareSimilarOrg(session('similar_org'));

        return view('auth.similarOrg', compact('similarOrg'));
    }

    public function submitSimilarOrganization()
    {
        if ($adminEmail = request('similar_organization')) {
            return redirect()->to('/password/email')->withInput(['email' => $adminEmail, 'redirectedFrom' => 'registration']);
        }

        return redirect()->route('registration.users');
    }

}
