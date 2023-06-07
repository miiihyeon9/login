<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
// use Illuminate\Mail\Mailable;
use App\Mail\AssignmentCreated;
use Illuminate\Support\Facades\Mail;
class UserController extends Controller
{
    // 회원가입 페이지 만들고
    // 인증버튼 클릭 시 이메일 발송
    // 이메일에서 인증번호 넘겨주고 
    // 
    public function login(){
        view('login');
    }

    public function registration() {
        return view('registration');
    }
    
    public function registrationpost(Request $req) {
        // 유효성 검사
        $req->validate([
            'name'      => 'required|regex:/^[가-힣]+$/|min:2|max:30'
            ,'email'    => 'required|email|max:100'
            ,'password' => 'required_with:passwordchk|same:passwordchk|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/' // required_with:passwordchk|same:passwordchk : 비밀번호와 비밀번호 확인을 비교함
        ]);
        
        $data['name'] = $req->name;
        $data['email'] = $req->email;
        $data['password'] = Hash::make($req->password); // Hash::make : 해쉬화(암호화)
        
        $user = User::create($data); // insert후 결과가 $user에 담김
        if(!$user) {
            $error = '시스템 에러가 발생하여, 회원 가입에 실패했습니다.<br>잠시 후에 다시 시도해 주십시오.';
            return redirect()
                ->route('users.registration')
                ->with('error', $error);
        }

        // Mail::to($user)->send(new SendEmail($user));
        
        // 회원가입 완료 후 로그인 페이지로 이동
        // return redirect()
        //     ->route('users.login')
        //     ->with('success', '회원가입을 완료 했습니다.<br>가입하신 아이디와 비밀번호로 로그인 해 주십시오.');

        // + 회원가입시 이메일 인증 test
        // $verification_code = Str::
        $verification_code = Str::random(8); // 인증 코드 생성
        $validity_period = now()->addMinutes(30); // 유효기간 설정

        $user->verification_code = $verification_code;
        $user->validity_period = $validity_period;
        $user->save();

        Mail::to($user->email)->send(new AssignmentCreated($user));

        return redirect()->route('users.login')->with('success', '회원가입을 완료 했습니다.<br>이메일을 확인하여 계정을 활성화해 주세요.<br>인증 유효기간은 30분입니다.');

    }

    // + 이메일 인증 test
    public function verify($code, $email) {
        $user = User::where('verification_code', $code)->where('email', $email)->first();

        if (!$user) {
            $error = '유효하지 않은 이메일 주소입니다.';
            return redirect()->route('users.login')->with('error', $error);
        }

        $currentTime = now();
        $validityPeriod = $user->validity_period;

        if ($currentTime > $validityPeriod) {
            $error = '인증 유효시간이 만료되었습니다.';
            $resendEmailUrl = route('resend.email', ['email' => $user->email]);
            return redirect()->back()->with('error', $error)->with('resend_email', true)->with('resend_email_url', $resendEmailUrl);
        }

        $user->verification_code = null;
        $user->validity_period = null;
        $user->email_verified_at = now();
        $user->save();

        $success = '이메일 인증이 완료되었습니다.<br>가입하신 아이디와 비밀번호로 로그인 해 주십시오.';
        return redirect()->route('users.login')->with('success', $success);
    }

    // + 이메일 인증 재전송 TEST
    public function resend_email(Request $req) {
        $user = User::where('email', $req->email)->first();
    
        if (!$user) {
            $error = '해당 이메일로 가입된 계정이 없습니다.';
            return redirect()->back()->with('error', $error);
        }
    
        if ($user->email_verified_at) {
            $error = '해당 계정은 이미 이메일 인증이 완료되었습니다.';
            return redirect()->back()->with('error', $error);
        }
    
        $verification_code = Str::random(30);
        $validity_period = now()->addMinutes(1);

        $user->verification_code = $verification_code;
        $user->validity_period = $validity_period;
        $user->save();
    
        Mail::to($user->email)->send(new AssignmentCreated($user));
    
        $success = '이메일 인증 메일을 재전송하였습니다.<br>이메일을 확인하여 계정을 활성화해 주세요.';
        return redirect()->back()->with('success', $success);
    }

}
