<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function setRedis()
    {
        // 設定 key
        Redis::set('user:1', '七哥');
        // 取得 key
        $value = Redis::get('user:1');
        // dd($value); // "七哥"
        // 設定 60 秒過期
        Redis::setex('temp:key', 60, 'hello');
        return $value;
    }
    public function getRedis()
    {
        $name = Redis::get('user:1');
        return $name;
    }
    public function index()
    {
        $users = User::where('status', 1)->get();
        $arr = [];
        foreach ($users as $user) {
            $arr[] = $user->username;
        }
        return $arr;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string|max:2',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);
            return User::create($request->only(['username', 'email', 'password']));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $all = User::findOrFail($id);
        $user = User::find(1);

        // 取得關聯的訂單
        $orders = $user->orders;

        // 直接取訂單號碼
        // $orderNumber = $user->order->order_number;

        return $orders;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->only(['password']));
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return User::destroy($id);
    }
}
