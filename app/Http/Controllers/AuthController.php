<?php

namespace App\Http\Controllers;

use App\Models\ReferralLink;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\ResetPassword;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class AuthController
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:30'],
            'email' => ['required', 'string', 'email', 'max:60', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:25', 'confirmed'],
            'legal_text' => ['required'],
            'kvkk_text' => ['required'],
            'specialty' => ['required', 'min:1'],
            'referral_code' => ['required', 'min:16', 'max:16'],
        ]);

        $referral_code = ReferralLink::where(['code' => $request->get('referral_code')])->first();

        if (!$referral_code){
            return response()->json([
                "status" => false ,
                "message" => [
                    "title" => "Hata !",
                    "body" => "Referans kodu doğru değil.",
                    "type" => "error",
                ]
            ]);
        }

        if ($referral_code->count === 0){
            return response()->json([
                "status" => false ,
                "message" => [
                    "title" => "Hata !",
                    "body" => "Bu referans kodu kullanım limitine ulaşmıştır.",
                    "type" => "error",
                ]
            ]);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'legal_text' => $data['legal_text'],
            'kvkk_text' => $data['kvkk_text'],
            'specialty' => $data['specialty'],
            'referral_link_code' => $referral_code->code,
            'verified' => false,
        ]);

        if ($user) {
            $referral_code->count -= 1;
            $referral_code->save();

            ReferralLink::create([
                'user_id' => $user->id,
                'code' => strtoupper(Str::random()),
                'count' => 50000,
            ]);

            event(new Registered($user));

            $token = $user->createToken('access_token')->plainTextToken;

            return response()->json([
                "token" => $token ,
                "type" => "Bearer",
                "status" => true ,
                "message" => [
                    "title" => "Başarılı",
                    "body" => "Kayıt başarıyla tamamlandı. Yönlendiriliyorsunuz",
                    "type" => "success",
                ]
            ]);
        }

        return response()->json(["status" => false , "message" => "Kayıt başarısız oldu."]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials['verified'] = true;

        if (!$this->guard()->attempt($credentials)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.'
            ], 422);
        }

        $this->guard()->attempt($credentials);
        $token = $this->guard()->user()->createToken('auth-token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        return response()->json([
            'status_code' => '200',
            'message' => 'logged out successfully'
        ]);
    }

    public function guard($guard = 'web')
    {
        return Auth::guard($guard);
    }

    /**
     * PasswordStoreToken Token Store
     *
     * @param[string] email
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function password_store_token(Request $request) {
        $rules = [
            'email' => 'required|string|email|exists:users'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                "status" => false ,
                "message" => [
                    "title" => "Hata",
                    "body" => $validator->errors(),
                    "type" => "error",
                ]
            ]);
        }

        $user = User::where('email', $request['email'])->first();
        if (!$user) {

            return response()->json([
                "status" => false ,
                "message" => [
                    "title" => "Hata",
                    "body" => "Mail Adresi Bulunamadı!",
                    "type" => "error",
                ]
            ]);
        }
        $resetpassword = ResetPassword::updateOrCreate(
            [
                'email' => $user->email,
            ],
            [
                'email' => $user->email,
                'token' => Str::random(45),
            ]
        );
        if ($user && $resetpassword) {
            //Sıfırlama bağlantısı için $resetpassword->token olarak kullanabilirsiniz.
            //TODO:: Maile Sıfırlama Linki Gönderilecek.
        }
        return response()->json([
            'status' => true,
            "message" => [
                "title" => "Başarılı",
                "body" => "Sıfırlama bağlantısı mailinize başarıyla gönderildi. Sıfırlama Bağlantısı: ".$resetpassword->token,
                "type" => "success",
            ]
        ]);
    }
    /**
     * Find Token
     *
     * @param[string] token
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function password_find_token($token) {
        $resetpassword = ResetPassword::where('token', $token)->first();
        if (!$token) {
            return response()->json([
                "status" => false ,
                "message" => [
                    "title" => "Hata",
                    "body" => "Bağlantı Geçersiz",
                    "type" => "error",
                ]
            ]);
        }
        if (Carbon::parse($resetpassword->created_at)->addMinutes(720)->isPast()) {
            $resetpassword->delete();
            return response()->json([
                "status" => false ,
                "message" => [
                    "title" => "Hata",
                    "body" => "Bağlantı Geçersiz",
                    "type" => "error",
                ]
            ]);
        }
        return response()->json([
            "status" => true ,
            "message" => [
                "title" => "Başarılı",
                "body" => $resetpassword,
                "type" => "success",
            ]
        ]);
    }
    /**
     * ResetPassword Token Store
     *
     * @param[string] email
     * @param[string] password
     * @param[string] password_confirmation
     * @param[string] token
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function password_reset(Request $request) {
        $rules = [
            'email' => 'required|string|email|exists:users',
            'token' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                "status" => false ,
                "message" => [
                    "title" => "Hata",
                    "body" => $validator->errors(),
                    "type" => "error",
                ]
            ]);
        }
        $resetpassword = ResetPassword::updateOrCreate(
            [
                'email' => $request->email,
                'token' => $request->token,
            ]
        )->first();
        if (!$resetpassword) {
            return response()->json([
                "status" => false ,
                "message" => [
                    "title" => "Hata",
                    "body" => "Mail Adresi Bulunamadı",
                    "type" => "error",
                ]
            ]);
        }
        $user = User::where('email', $resetpassword->email)->first();
        if (!$user) {
            return response()->json([
                "status" => false ,
                "message" => [
                    "title" => "Hata",
                    "body" => "Kullanıcı Bulunamadı",
                    "type" => "error",
                ]
            ]);
        }
        $user->password = bcrypt($request->password);
        $user->save();
        $resetpassword->delete();
        //Şifre değiştildiğine dair bağlantı için $resetpassword->token olarak kullanabilirsiniz.
        //TODO:: Maile Sıfırlama Linki Gönderilecek.
        return response()->json([
            'status' => true,
            "message" => [
                "title" => "Başarılı",
                "body" => "Kullanıcı Şifresi Başarıyla Değiştirildi. Sıfırlama Token: ".$resetpassword->token,
                "type" => "success",
            ]
        ]);
    }
    /**
     * ProfilePasswordReset For Profile in User
     *
     * @param[string] old_password
     * @param[string] password
     * @param[string] password_confirmation
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function profile_password_reset(Request $request) {
        $rules = [
            'old_password' => 'required|string|min:6',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string|min:6',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                "status" => false ,
                "message" => [
                    "title" => "Hata",
                    "body" => $validator->errors(),
                    "type" => "error",
                ]
            ]);
        }

        $user = User::where('email', Auth::User()->email)->first();
        $storedPassword = User::where('email',Auth::User()->email)->value('password');
        if (!$user) {
            return response()->json([
                "status" => false ,
                "message" => [
                    "title" => "Hata",
                    "body" => "Kullanıcı bulunamadı!",
                    "type" => "error",
                ]
            ]);
        }elseif(!Hash::check($request->old_password, $storedPassword)){
            return response()->json([
                "status" => false ,
                "message" => [
                    "title" => "Hata",
                    "body" => "Eski Şifre Hatalı Bunun Yerine Şifremi Unuttum Bağlantısını Kullanarak E-Posta üzerinden sıfırlayabilirsiniz!",
                    "type" => "error",
                ]
            ]);
        }
        $user->password = bcrypt($request->password);
        $user->save();
        //Şifre değiştildiğine dair bağlantı için $resetpassword->token olarak kullanabilirsiniz.
        //TODO:: Maile Sıfırlama Linki Gönderilecek.
        return response()->json([
            'status' => true,
            "message" => [
                "title" => "Başarılı",
                "body" => "Kullanıcı Şifresi Başarıyla Değiştirildi.",
                "type" => "success",
            ]
        ]);
    }

}
