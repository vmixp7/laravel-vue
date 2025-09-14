<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SeckillController extends Controller
{
    public function buy(Request $request)
    {
        $userId = $request->input('user_id');
        $productId = $request->input('product_id');
        return response()->json(['ok' => $userId]);

        // $lockKey = "lock:product:{$productId}";
        // $lockValue = Str::uuid()->toString(); // 避免誤刪別人的鎖
        // $lockTtl = 5; // 鎖存活時間 (秒)

        // try {
        //     // 嘗試加鎖 (NX + EX)
        //     $acquired = Redis::set($lockKey, $lockValue, 'NX', 'EX', $lockTtl);

        //     if (!$acquired) {
        //         return response()->json(['error' => '系統繁忙，請稍後再試'], 429);
        //     }

        //     // ====== 業務邏輯 ======
        //     // 檢查庫存
        //     $stock = Redis::get("stock:product:{$productId}");
        //     if ($stock <= 0) {
        //         return response()->json(['error' => '商品已售完'], 400);
        //     }

        //     // 扣減庫存
        //     Redis::decr("stock:product:{$productId}");

        //     // 建立訂單 (假設存到 DB)
        //     \DB::table('orders')->insert([
        //         'user_id' => $userId,
        //         'product_id' => $productId,
        //         'created_at' => now(),
        //     ]);

        //     return response()->json(['success' => '購買成功']);
        // } finally {
        //     // ====== 解鎖 ======
        //     // 用 Lua 確保只有自己能刪鎖
        //     $lua = <<<LUA
        //     if redis.call("get", KEYS[1]) == ARGV[1] then
        //         return redis.call("del", KEYS[1])
        //     else
        //         return 0
        //     end
        //     LUA;
        //     Redis::eval($lua, 1, $lockKey, $lockValue);
        // }
    }
}
