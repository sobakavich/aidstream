<?php namespace App\Services;

use App\User;
use Illuminate\Contracts\Mail\Mailer;

class Verification
{
    /**
     * @var User
     */
    protected $user;
    /**
     * @var Mailer
     */
    protected $mailer;

    public function __construct(Mailer $mailer, User $user)
    {
        $this->mailer = $mailer;
        $this->user   = $user;
    }

    /**
     * sends email to all users
     * @param $user User
     */
    public function sendVerificationEmail($user)
    {
        $user   = $this->generateVerificationCode($user);
        $method = [
            1 => 'getAdminComponents',
            2 => 'getUserComponents',
            5 => 'getSecondaryComponents'
        ];
        $data   = $user->toArray();

        $emailComponent = $this->$method[$user->role_id]($data);

        $this->mailer->send($emailComponent['view'], $data, $emailComponent['callback']);
    }

    protected function getAdminComponents($data)
    {
        $callback = function ($message) use ($data) {
            $message->subject('Welcome to AidStream');
            $message->from(config('mail.from.address'), config('mail.from.name'));
            $message->to($data['email']);
        };

        return ['view' => 'emails.admin', 'callback' => $callback];
    }

    protected function getUserComponents($data)
    {
        $callback = function ($message) use ($data) {
            $message->subject('Welcome to AidStream');
            $message->from(config('mail.from.address'), config('mail.from.name'));
            $message->to($data['email']);
        };

        return ['view' => 'emails.user', 'callback' => $callback];
    }

    protected function getSecondaryComponents(&$data)
    {
        $org              = $this->user->find($data['id'])->organization;
        $orgName          = $org->reporting_org[0]['narrative'][0]['narrative'];
        $data['admin']    = $org->users->where('role_id', 1)->first()->toArray();
        $data['org_name'] = $orgName;
        $callback         = function ($message) use ($data, $orgName) {
            $message->subject(sprintf('%s is now live on AidStream', $orgName));
            $message->from(config('mail.from.address'), config('mail.from.name'));
            $message->to($data['email']);
        };

        return ['view' => 'emails.secondary', 'callback' => $callback];
    }

    protected function generateVerificationCode($user)
    {
        $user->verification_code       = hash_hmac('sha256', str_random(40), config('app.key'));
        $user->verification_created_at = date('Y-m-d H:i:s', time());
        $user->save();

        return $user;
    }

    public function verifyUser($code)
    {
        $user = $this->user->where('verification_code', $code)->first();
        if (!$user) {
            $message = 'The verification code is invalid.';
        } elseif ($user->update(['verified' => true])) {
            $method = [
                1 => 'verifyAdmin',
                2 => 'verifyOrgUser',
                5 => 'verifySecondary'
            ];

            return $this->$method[$user->role_id]($user);
        } else {
            $message = 'Failed to verify your account.';
        }

        return redirect()->to('/auth/login')->withErrors([$message]);

    }

    protected function verifyAdmin($user)
    {
        $users = $this->user->where('org_id', $user->org_id)->orderBy('id', 'asc')->get();
        $this->sendVerificationEmails($users);
        $message = view('verification.admin', compact('users', 'user'));

        return redirect()->to('/auth/login')->withVerificationMessage($message->__toString());
    }

    protected function verifySecondary($user)
    {
        $message = view('verification.secondary', compact('user'));

        return redirect()->to('/')->withVerificationMessage($message->__toString());
    }

    protected function verifyOrgUser($user)
    {
        return redirect()->route('show-create-password', $user->verification_code);
    }

    public function saveRegistryInfo($code, $registryInfo)
    {
        return false;
    }

    protected function sendVerificationEmails($allUsers)
    {
        $secondary = $allUsers->where('role_id', 5)->first();
        $users     = $allUsers->where('role_id', 2);

        $this->sendVerificationEmail($secondary);
        $this->sendVerificationEmailToUsers($users);
    }

    protected function sendVerificationEmailToUsers($users)
    {
        foreach ($users as $user) {
            $this->sendVerificationEmail($user);
        }
    }
}