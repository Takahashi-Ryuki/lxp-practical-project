<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// 必要なモデルをuseします（Reviewモデルがあると仮定）
use App\Models\Review;
use Auth;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, $productId)
    {
        // バリデーション
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:100',
        ]);

        // レビューをデータベースに保存
        Review::create([
            'product_id' => $productId,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // レビュー投稿後に商品詳細ページにリダイレクト
        return redirect()->back()->with('success', '評価とコメントを登録しました');
    }
}
