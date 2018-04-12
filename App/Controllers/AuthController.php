<?php
/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2017/5/23
 * Time: 21:16
 */

namespace App\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Slim\Http\Request;
use Slim\Http\Response;

class AuthController extends Controller
{
    public function loginView()
    {
        $smarty = $this->getSmarty();
        $smarty->display('login.tpl');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function checkView($request, $response)
    {
        $ident = $request->getParam('ident');
        $password = $request->getParam('password');
        try {
            $user = User::where('ident', $ident)->firstOrFail();
            if (md5($password . $user->salt) == $user->password) {
                $_SESSION['user'] = $ident;
                $_SESSION['eloquent'] = serialize($user);
                if (isset($_SESSION['intended.url'])) {
                    $intendedUrl = $_SESSION['intended.url'];
                    unset($_SESSION['intended.url']);
                    return $response->withRedirect($intendedUrl);
                } else {
                    return $response->withRedirect('/');
                }
            } else {
                $smarty = $this->getSmarty();
                $smarty->assign('errors', ['ident or password error']);
                $smarty->display('login.tpl');
            }
        } catch (ModelNotFoundException $e) {
            $smarty = $this->getSmarty();
            $smarty->assign('errors', ['ident or password error']);
            $smarty->display('login.tpl');
        }
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function logoutView($request, $response)
    {
        unset($_SESSION['user']);
        return $response->withRedirect('/');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function githubOauth($request, $response)
    {
        return $response->withRedirect(
            'https://github.com/login/oauth/authorize?scope=user:email&client_id=' . env('GITHUB_CLIENT_ID')
        );
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function githubOauthCallback($request, $response)
    {
        $return = $this->curl('https://github.com/login/oauth/access_token', [
            'postData' => [
                'client_id'     => env('GITHUB_CLIENT_ID'),
                'client_secret' => env("GITHUB_CLIENT_SECRET"),
                'code'          => $request->getParam('code'),
                'accept'        => 'json',
            ],
        ]);
        // access_token=0372202533a0176d499521c7136d2f67cb472a71&scope=user%3Aemail&token_type=bearer
        // error=bad_verification_code&error_description=The+code+passed+is+incorrect+or+expired.&
        //     error_uri=https%3A%2F%2Fdeveloper.github.com%2Fv3%2Foauth%2F%23bad-verification-code
//        $access_token = '0372202533a0176d499521c7136d2f67cb472a71';
        parse_str($return);
        if (isset($error)) {
            Log::error($return);
            return $response->withStatus(403, $error);
        } else if (isset($access_token)) {
            $userJson = $this->curl('https://api.github.com/user?access_token=' . $access_token);
//            $email = $this->curl('https://api.github.com/emails?access_token='.$access_token);
            $user = json_decode($userJson);
            $userJson = json_encode($user);
            $ident = 'github-' . $user->login;
            $userModel = User::where('ident', $ident)->first();
            if ($userModel) {
                $userModel->json_data = $userJson;
                $userModel->save();
            } else {
                $userModel = User::create([
                    'ident'     => $ident,
                    'json_data' => $userJson,
                ]);
            }

            $_SESSION['user'] = $ident;
            if (isset($_SESSION['intended.url'])) {
                $intendedUrl = $_SESSION['intended.url'];
                unset($_SESSION['intended.url']);
                return $response->withRedirect($intendedUrl);
            } else {
                return $response->withRedirect('/');
            }
        } else {
            Log::error($return);
            return $response->withStatus(403, "ERROR");
        }
    }
}