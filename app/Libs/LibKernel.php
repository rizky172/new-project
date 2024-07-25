<?php

namespace App\Libs;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Payload;
use Auth;
use DB;
use Log;
use Carbon\Carbon;

use App\WevelopeLibs\AuthHelper;

use App\Libs\Repository\User;
use App\Libs\Repository\Meta;
use App\Libs\Repository\AbstractRepository;
use App\Libs\Repository\Customer;
use App\Libs\Repository\Config as ConfigRepo;
use App\Libs\Libs\WeXendit;
use App\Libs\Mails\ForgotPassword;

use App\Models\User as UserModel;
use App\Models\Category;
use App\Models\Config;
use App\Models\LogM;

class LibKernel
{
    // This function always called before App start
    public static function init()
    {
        // Prepare all neccesery directory
        $ds = DIRECTORY_SEPARATOR;
        $list = [
            $ds . 'tmp',
            $ds . 'uploads',
            $ds . 'uploads' . $ds . 'config',
        ];

        foreach($list as $x) {
            // Check if $x folder is exists
            $path = public_path() . $x;
            if(!file_exists($path) && !mkdir($path, 0755, true)) {
                die('Cannot make ' . $path . ' folder');
            }
        }
    }

    private static function validateLogin($username, $password, $ip)
    {
        // Validation
        $fields = [
            'username' => $username,
            'password' => $password,
            'ip' => $ip
        ];

        $rules = [
            'username' => 'required',
            'password' => 'required',
            'ip' => 'required|ip',
        ];

        $validator = Validator::make($fields, $rules);
        AbstractRepository::validOrThrow($validator);
    }

    public static function login($username, $password, $ip)
    {
        self::validateLogin($username, $password, $ip);

        $token = Auth::attempt(['username' => $username, 'password' => $password]);

        if (!empty($token)) {
            // $user = Auth::user();

            // if (!empty($user->person_id)) {
            //     if (empty($user->person->is_active)) {
            //         $message = "Akun anda sudah tidak aktif.";
            //         throw new UnauthorizedHttpException($message, $message);
            //     }
            // }

            self::saveUserActivity($ip,'login');

            return $token;
        }

        $message = "Kombinasi Username atau Password salah.";
        throw new UnauthorizedHttpException($message, $message);
    }

    /* Engine log auth user
     * @param object $request, string $type
     * @return Boolean
    */
    public static function saveUserActivity($ip, $type)
    {
        // get category user activity
        $category = Category::where([
            'group_by' => 'log',
            'name' => 'user-activity'
        ])->first();

        $notes = ucfirst(Auth::user()->name) . " melakukan " . $type;

        LogM::create([
            'fk_id' => $category->id,
            'table_name' => 'log',
            'notes' => $notes . " dengan IP " . $ip,
            'created' => new \DateTime
        ]);
    }

    /* Generate new token if would be expired in 15 minutes or less
     *
     * @return string new token
     */
    public static function generateNewTokenIfNeccesery($user, $token)
    {
        $hours = 1;
        $payload = auth()->payload();
        $payload = $payload->toArray();

        $exp = Carbon::now();
        $exp->setTimestamp($payload['exp']);
        if($exp->diffInHours(Carbon::now()) < $hours) {
            $token = Auth::login($user);
        }

        return $token;
    }

    /*public static function logout(UserModel $user)
    {
        if(!empty($user)) {
            $user->removeApiToken();
            $user->save();

            // Set cookies
            AuthHelper::setCookie(null);

            Auth::logout($user);

            return $user;
        }

        throw new \Exception("Logout error");
    }*/

    /* Engine logout
     * @param object $request
     * @return Boolean
    */
    public static function logout($ip){
        try {
            self::saveUserActivity($ip, 'logout');

            AuthHelper::setCookie(null);
            Auth::logout();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public static function getPaginate()
    {
        return 40;
    }

    public static function getDateFormat()
    {
        return 'd-m-Y';
    }

    public static function register($request){}

    public static function forgotPassword($email)
    {
        $fields = ['email' => $email];
        $rules = ['email' => 'required|email'];

        $validator = Validator::make($fields, $rules);
        AbstractRepository::validOrThrow($validator);

        $user = UserModel::where('email', $email)->first();

        if ($user) {
            $resetToken = Str::random(64);

            $mail = new ForgotPassword($user);
            $mail->setResetToken($resetToken);
            $mail->sendMail();

            $user->reset_token = $resetToken;
            $user->reset_token_expired = Carbon::now()->addHours(3);
            $user->save();
        }

        return true;
    }

    public static function resetPassword($request)
    {
        $user = UserModel::where('reset_token', $request['hash'])->first();
        $resetTokenExpired = $user->reset_token_expired ?? null;
        $resetTokenExpired = Carbon::parse($resetTokenExpired);

        $fields = [
            'password' => $request['password'],
            'password_confirmation' => $request['password_confirmation'],
            'hash' => $request['hash'],
            'reset_token_expired' => $resetTokenExpired
        ];

        $rules = [
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
            'hash' => 'required|exists:user,reset_token',
            'reset_token_expired' => 'required|date|after:now'
        ];

        $messages = [
            'password_confirmation.same' => 'Password konfirmasi harus sama dengan password',
            'rest_token_expired.after' => 'Link sudah kadaluarsa.',
            'hash.exists' => 'Token reset password tidak valid.'
        ];

        $validator = Validator::make($fields, $rules, $messages);
        AbstractRepository::validOrThrow($validator);

        $user->password = bcrypt($request['password']);
        $user->reset_token = null;
        $user->reset_token_expired = null;
        $user->save();

        return self::login($user->username, $request['password'], $request['ip']);
    }

    public static function smptFromDb()
    {
        $newConfig = [
            'mail.host' => env('MAIL_HOST'),
            'mail.port' => env('MAIL_PORT'),
            'mail.from.address' => env('MAIL_FROM_ADDRESS'),
            'mail.from.name' => env('MAIL_FROM_NAME'),
            'mail.username' => env('MAIL_USERNAME'),
            'mail.password' => env('MAIL_PASSWORD')
        ];

        config($newConfig);
    }
}
