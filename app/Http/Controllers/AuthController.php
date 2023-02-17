<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPassword\ResetWithTokenRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\PasswordReset;
use App\Models\ReferralLink;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

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

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if ($this->guard()->attempt($credentials)) {
            $user = User::select(['id', 'verified'])->where('email', $request->input('email'))->first();

            if ($user->verified){
                $this->guard()->attempt($credentials);
                $token = $this->guard()->user()->createToken('auth-token')->plainTextToken;

                return response()->json([
                    "status" => true ,
                    "data" => [
                        'access_token' => $token,
                        'token_type' => 'Bearer',
                    ],
                    "message" => [
                        "title" => "Başarılı",
                        "body" => "Başarıyla giriş yaptınız.",
                        "type" => "success",
                    ]
                ]);
            }else{
                return response()->json([
                    "status" => false ,
                    "message" => [
                        "title" => "Hata !",
                        "body" => "Hesabınız onaylanmadığı için henüz giriş yapamazsınız.",
                        "type" => "error",
                    ]
                ]);
            }
        }else{
            $this->guard()->logout();
            return response()->json([
                "status" => false ,
                "message" => [
                    "title" => "Hata !",
                    "body" => "Yanlış bilgi girdiniz.",
                    "type" => "error",
                ]
            ]);
        }
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        return response()->json([
            "status" => true ,
            "message" => [
                "title" => "Başarılı !",
                "body" => "Başarıyla çıkış yaptınız.",
                "type" => "success",
            ]
        ]);
    }

    public function user(Request $request){
        return User::select(['id', 'name', 'email', 'specialty'])
            ->where(['id' => auth()->user()->id])
            ->with([
                'specialty' => function($query){
                    $query->select(['id', 'name']);
                }
            ])
            ->first()
            ->toArray();
    }

    public function guard($guard = 'web')
    {
        return Auth::guard($guard);
    }

    public function sendPasswordResetLink(ResetPasswordRequest $request) {
        $user = User::where('email', $request->get('email'))->first();
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

        $count = PasswordReset::where('email',$request->get('email'))->count();
        if($count){
            $record = PasswordReset::where('email',$request->get('email'))->first();
            $differenceTime = Carbon::parse($record->created_at)->diffInMinutes();
            if($differenceTime<3){
                return response()->json([
                    'status' => false,
                    "message" => [
                        "title" => "Hata!",
                        "body" => "Kısa bir süre önce sıfırlama bağlantısı talep ettiniz. Lütfen e-posta kutunuzu kontrol edin.",
                        "type" => "error",
                    ]
                ]);
            }
        }

        $token = Str::random(60);
        $resetPassword = PasswordReset::updateOrCreate(
            [
                'email' => $user->email,
            ],
            [
                'email' => $user->email,
                'token' => $token,
                'created_at' => now(),
            ]
        );
        $result = $resetPassword->save();

        if ($result) {
            $user->sendPasswordResetNotification($token);

            return response()->json([
                'status' => true,
                "message" => [
                    "title" => "Başarılı",
                    "body" => "Sıfırlama bağlantısı mailinize başarıyla gönderildi.",
                    "type" => "success",
                ]
            ]);
        }else{
            return response()->json([
                'status' => false,
                "message" => [
                    "title" => "Hata",
                    "body" => "Teknik bir hata sebebiyle sıfırlama bağlantısı gönderilememiştir.",
                    "type" => "error",
                ]
            ]);
        }
    }

    public function resetPassword(ResetWithTokenRequest $request) {
        $resetPassword = PasswordReset::where(['email' => $request->get('email'), 'token' => $request->get('token')])->first();
        if (!$resetPassword) {
            return response()->json([
                "status" => false ,
                "message" => [
                    "title" => "Hata",
                    "body" => "Parola yenileme talebi bulunamadı. Yanlış link kullanıyor olabilirsiniz.",
                    "type" => "error",
                ]
            ]);
        }

        $differenceTime = Carbon::parse($resetPassword->created_at)->diffInMinutes();
        if ($differenceTime > 60){
            return response()->json([
                "status" => false ,
                "message" => [
                    "title" => "Hata",
                    "body" => "Parola yenileme talebinizin süresi dolmuştur. Lütfen başka bir talepte bulununuz.",
                    "type" => "error",
                ]
            ]);
        }

        $user = User::where(['email' => $resetPassword->email])->first();
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

        $user->password = bcrypt($request->get('password'));

        if($user->save()){
            $resetPassword->delete();

            $user->notify(new PasswordResetNotification());

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
}
