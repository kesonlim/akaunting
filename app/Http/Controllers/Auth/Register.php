<?php

namespace App\Http\Controllers\Auth;

use App\Abstracts\Http\Controller;
use App\Http\Requests\Auth\Register as Request;
use App\Jobs\Auth\DeleteInvitation;
use App\Models\Auth\UserInvitation;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Str;

class Register extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function create($token)
    {
        $invitation = UserInvitation::token($token)->first();

        if ($invitation) {
            return view('auth.register.create', ['token' => $token]);
        }

        abort(403);
    }

    public function store(Request $request)
    {
        $invitation = UserInvitation::token($request->get('token'))->first();

        // The invited user may have been deleted while the invitation was still
        // pending, which makes the invitation as invalid as a missing one.
        if (! $invitation || ! $invitation->user) {
            abort(403);
        }

        $user = $invitation->user;

        $this->dispatch(new DeleteInvitation($invitation));

        event(new Registered($user));

        if ($response = $this->registered($request, $user)) {
            return $response;
        }
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        $user->forceFill([
            'password' => $request->password,
            'remember_token' => Str::random(60),
        ])->save();

        $this->guard()->login($user);

        $message = trans('messages.success.connected', ['type' => trans_choice('general.users', 1)]);

        flash($message)->success();

        return response()->json([
            'redirect' => url($this->redirectPath()),
        ]);
    }

    public function signup(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:191',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $company_id = \DB::table('companies')->insertGetId([
            'domain' => 'localhost',
            'enabled' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \DB::table('settings')->insert([
            ['company_id' => $company_id, 'key' => 'general.company_name', 'value' => $request->company_name],
            ['company_id' => $company_id, 'key' => 'general.company_email', 'value' => $request->email],
            ['company_id' => $company_id, 'key' => 'general.default_currency', 'value' => 'SGD'],
            ['company_id' => $company_id, 'key' => 'wizard.completed', 'value' => '1'],
        ]);

        $user_id = \DB::table('users')->insertGetId([
            'name' => $request->company_name . ' Admin',
            'email' => $request->email,
            'password' => \Hash::make($request->password),
            'locale' => 'en-GB',
            'enabled' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \DB::table('user_companies')->insert([
            'user_id' => $user_id,
            'company_id' => $company_id,
        ]);

        \DB::table('currencies')->insert([
            'company_id' => $company_id,
            'name' => 'Singapore Dollar',
            'code' => 'SGD',
            'rate' => 1.0000,
            'precision' => 2,
            'symbol' => 'S$',
            'symbol_first' => 1,
            'decimal_mark' => '.',
            'thousands_separator' => ',',
            'enabled' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \DB::table('taxes')->insert([
            'company_id' => $company_id,
            'name' => 'Singapore GST (9%)',
            'rate' => 9.0000,
            'type' => 'normal',
            'enabled' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user = \App\Models\Auth\User::find($user_id);
        auth()->login($user);

        return redirect("/{$company_id}/dashboard");
    }
}
