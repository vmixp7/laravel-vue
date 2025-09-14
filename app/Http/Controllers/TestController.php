<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class TestController extends Controller
{
    // 字串反轉
    public function revers(Request $request, $id)
    {
        $str = $id;
        $reversed = collect(mb_str_split($str))->reverse()->join('');
        return $reversed;
    }
    // 防止超賣或重複扣款
    public function lockForUpdate(Request $request, $id)
    {
        $count = DB::transaction(function () use ($id) {
            // 鎖定 ID 為 1 的使用者
            $user = User::where('id', $id)->lockForUpdate()->first();

            // 進行一些邏輯判斷或運算
            if ($user->balance >= 100) {
                $user->balance -= 100;
                $user->save();
            }
            return $user->balance;
        });
        return $count;
    }
    // 寫入後立刻查詢, 確保資料一致性
    public function storeAndCheck(Request $request)
    {
        // 寫入資料
        $user = User::create([
            'name' => '七哥',
            'email' => 'qige@example.com',
            'password' => bcrypt('secret'),
        ]);

        // 立刻查詢
        $fresh = User::where('id', $user->id)->first();

        if (!$fresh) {
            return response()->json(['error' => '寫入後查詢失敗'], 500);
        }

        return response()->json(['success' => true, 'data' => $fresh]);
    }

}
