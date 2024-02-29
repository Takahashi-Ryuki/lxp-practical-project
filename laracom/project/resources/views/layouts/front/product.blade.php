<div class="row">
    <div class="col-md-6 col-sm-12">
        @if (!empty($product->cover))
            <ul id="thumbnails" class="col-md-4 list-unstyled">
                <li>
                    <a href="javascript: void(0)">
                        <img class="img-responsive img-thumbnail" src="{{ $product->cover }}" alt="{{ $product->name }}" />
                    </a>
                </li>
                @if (isset($images) && !$images->isEmpty())
                    @foreach ($images as $image)
                        <li>
                            <a href="javascript: void(0)">
                                <img class="img-responsive img-thumbnail" src="{{ asset("storage/$image->src") }}"
                                    alt="{{ $product->name }}" />
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
            <figure class="text-center product-cover-wrap col-md-8">
                <img id="main-image" class="product-cover img-responsive" src="{{ $product->cover }}?w=400"
                    data-zoom="{{ $product->cover }}?w=1200">
            </figure>
        @else
            <figure>
                <img src="{{ asset('images/NoData.png') }}" alt="{{ $product->name }}"
                    class="img-bordered img-responsive">
            </figure>
        @endif
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="product-description">
            <h1>{{ $product->name }}
                <small><span>{{ $product->price * 140 }}円</span>+ 送料 ¥980</small>
            </h1>
            <div class="sku">
                <strong>SKU:</strong> {{ $product->sku }} <!-- SKUを表示 -->
            </div>
            <div class="description">{!! $product->description !!}</div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    @include('layouts.errors-and-messages')
                    <form action="{{ route('cart.store') }}" class="form-inline" method="post">
                        {{ csrf_field() }}
                        @if (isset($productAttributes) && !$productAttributes->isEmpty())
                            <div class="form-group">
                                <label for="productAttribute">Choose Combination</label> <br />
                                <select name="productAttribute" id="productAttribute" class="form-control select2">
                                    @foreach ($productAttributes as $productAttribute)
                                        <option value="{{ $productAttribute->id }}">
                                            @foreach ($productAttribute->attributesValues as $value)
                                                {{ $value->attribute->name }} : {{ ucwords($value->value) }}
                                            @endforeach
                                            @if (!is_null($productAttribute->sale_price))
                                                ({{ config('cart.currency_symbol') }}
                                                {{ $productAttribute->sale_price }})
                                            @elseif(!is_null($productAttribute->price))
                                                ( {{ config('cart.currency_symbol') }} {{ $productAttribute->price }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <hr>
                        @endif
                        <div class="form-group">
                            <input type="text" class="form-control" name="quantity" id="quantity"
                                placeholder="Quantity" value="{{ old('quantity') }}" />
                            <input type="hidden" name="product" value="{{ $product->id }}" />
                        </div>
                        <button type="submit" class="btn btn-warning"><i class="fa fa-cart-plus"></i> カゴに追加
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ログインしているユーザーのみ評価とコメントを入力できるフォームを表示 -->
@auth
    <div class="col-md-12">
        <h4>あなたの評価とコメントを追加</h4>
        <form id="review-form" action="{{ route('review.store') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            
            <div class="form-group">
                <label for="rating">評価</label>
                <select name="rating" id="rating" class="form-control" required>
                    <option value="">選択してください</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>

            <div class="form-group">
                <label for="comment">コメント</label>
                <textarea name="comment" id="comment" class="form-control" rows="3" maxlength="100" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary" disabled>登録</button>
        </form>
    </div>
    @else
    <div class="col-md-12">
        <p>評価とコメントを追加するには、<a href="{{ route('login') }}">ログイン</a>してください。</p>
    </div>
@endauth
@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            var productPane = document.querySelector('.product-cover');
            var paneContainer = document.querySelector('.product-cover-wrap');

            new Drift(productPane, {
                paneContainer: paneContainer,
                inlinePane: false
            });
        });
        <script>
        $(document).ready(function() {
            // フォームの入力を検証して送信ボタンを活性化する
            function validateForm() {
                const rating = $('#rating').val().trim();
                const comment = $('#comment').val().trim();
                $('#review-form button[type="submit"]').prop('disabled', rating === '' || comment === '' || comment.length > 100);
            }

            $('#rating, #comment').on('change keyup', validateForm);

            // フォームの送信処理
            $('#review-form').submit(function(e) {
                e.preventDefault();
                // ここにAJAX送信の処理を追加
                // 成功した場合の処理もここに追加
            });
        });
        </script>
    </script>
    
@endsection