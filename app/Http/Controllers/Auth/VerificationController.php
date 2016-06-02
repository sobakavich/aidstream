<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\RequestManager\Password;
use App\Services\Verification;

class VerificationController extends Controller
{
    /**
     * @var Verification
     */
    protected $verificationManager;

    public function __construct(Verification $verificationManager)
    {
        $this->verificationManager = $verificationManager;
    }

    public function verifyUser($code)
    {
        return $this->verificationManager->verifyUser($code);
    }

    public function saveRegistryInfo($code)
    {
        $registryInfo = request()->all();
        if ($this->verificationManager->saveRegistryInfo($code, $registryInfo)) {
            return redirect()->to('/auth/login')->withMessage('Registry Info Saved Successfully.');
        } else {
            return redirect()->to('/auth/login')->withErrors(['Failed to save Registry Info.']);
        }
    }
}
