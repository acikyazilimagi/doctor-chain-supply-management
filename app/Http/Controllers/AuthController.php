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
     * PasswordTokenGenerate Generate Token For Reset Password
     *
     * @param[string] email
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function passwordTokenGenerate(Request $request) {

        $data = $request->validate([
            'email' => 'required|string|email|exist:users'
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return response()->json([
                "status" => false ,
                "message" => [
                    "title" => "Hata",
                    "body" => "Mail adresi bulunamadı!",
                    "type" => "error",
                ]
            ]);
        }

        $count = ResetPassword::where('email',$data['email'])->count();
        if($count){
            $record = ResetPassword::where('email',$data['email'])->last();
            $lastGenTime = $record->created_at;
            $differenceTime = Carbon::now()->timestamp - strtotime($lastGenTime);
            if($differenceTime<180){
                return response()->json([
                    'status' => false,
                    "message" => [
                        "title" => "Hata!",
                        "body" => "Zaten bir sıfırlama bağlantısı talebiniz mevcut. Lütfen e-posta kutunuzu kontrol edin.",
                        "type" => "error",
                    ]
                ]);
            }
        }

        $resetPassword = ResetPassword::updateOrCreate(
            [
                'email' => $user->email,
            ],
            [
                'email' => $user->email,
                'token' => Str::random(45),
            ]
        );
        if ($user && $resetPassword) {
            //TODO:: Maile Sıfırlama Linki Gönderilecek @param $resetpassword->token.
            return response()->json([
                'status' => true,
                "message" => [
                    "title" => "Başarılı",
                    "body" => "Sıfırlama bağlantısı mailinize başarıyla gönderildi.",
                    "type" => "success",
                ]
            ]);
        }
    }
    /**
     * If Click Link On Mail Check Token Status
     *
     * @param[string] token
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function passwordTokenCheck($token) {
        $resetpassword = ResetPassword::where('token', $token)->first();
        if (!$token) {
            return response()->json([
                "status" => false ,
                "message" => [
                    "title" => "Hata",
                    "body" => "Bağlantı geçersiz!",
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
                    "body" => "Bağlantı geçersiz!",
                    "type" => "error",
                ]
            ]);
        }
        return response()->json([
            "status" => true ,
            "message" => [
                "title" => "Başarılı",
                "body" => '',
                "type" => "success",
            ]
        ]);
    }
    /**
     * PasswordResetWithToken Reset Password
     *
     * @param[string] email
     * @param[string] password
     * @param[string] password_confirmation
     * @param[string] token
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function passwordResetWithToken(Request $request) {
        $data = $request->validate([
            'email' => 'required|string|email|exists:users',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ]);

        $resetPassword = ResetPassword::updateOrCreate(
            [
                'email' => $data['email'],
                'token' => $data['token'],
            ]
        )->first();
        if (!$resetPassword) {
            return response()->json([
                "status" => false ,
                "message" => [
                    "title" => "Hata",
                    "body" => "Mail adresi bulunamadı!",
                    "type" => "error",
                ]
            ]);
        }
        $user = User::where('email', $resetPassword->email)->first();
        if (!$user) {
            return response()->json([
                "status" => false ,
                "message" => [
                    "title" => "Hata",
                    "body" => "Kullanıcı bulunamadı!",
                    "type" => "error",
                ]
            ]);
        }
        $user->password = bcrypt($request->password);

        if($user->save()){
            $resetPassword->delete();
            //Şifre değiştildiğine dair bağlantı için $resetpassword->token olarak kullanabilirsiniz.
            //TODO:: Eposta adresine Şifre değiştiğine dair mail Gönderilecek.
            return response()->json([
                'status' => true,
                "message" => [
                    "title" => "Başarılı",
                    "body" => "Kullanıcı Şifresi Başarıyla Değiştirildi.",
                    "type" => "success",
                ]
            ]);
        }else{
            return response()->json([
                'status' => false,
                "message" => [
                    "title" => "Hata",
                    "body" => "Şifre güncelleme sırasında teknik bir hata meydana geldi!",
                    "type" => "success",
                ]
            ]);

        }
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
    public function passwordResetForProfile(Request $request) {
        $data = $request->validate([
            'old_password' => 'required|string|min:8',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ]);

        $user = User::where('email', Auth::user()->email)->first();
        $storedPassword = User::where('email',Auth::user()->email)->value('password');
        if (!$user) {
            return response()->json([
                "status" => false ,
                "message" => [
                    "title" => "Hata",
                    "body" => "Kullanıcı bulunamadı!",
                    "type" => "error",
                ]
            ]);
        }elseif(!Hash::check($data['old_password'], $storedPassword)){
            return response()->json([
                "status" => false ,
                "message" => [
                    "title" => "Hata",
                    "body" => "Eski Şifre Hatalı Bunun Yerine Şifremi Unuttum Bağlantısını Kullanarak E-Posta üzerinden sıfırlayabilirsiniz!",
                    "type" => "error",
                ]
            ]);
        }
        $user->password = bcrypt($data['password']);

        if($user->save()){
            //TODO:: Eposta adresine Şifre değiştiğine dair mail Gönderilecek.
            return response()->json([
                'status' => true,
                "message" => [
                    "title" => "Başarılı",
                    "body" => "Kullanıcı şifresi başarıyla değiştirildi.",
                    "type" => "success",
                ]
            ]);
        }else{
            return response()->json([
                'status' => false,
                "message" => [
                    "title" => "Hata",
                    "body" => "Şifre güncelleme esnasında teknik bir hata meydana geldi!",
                    "type" => "error",
                ]
            ]);
        }
    }

}
