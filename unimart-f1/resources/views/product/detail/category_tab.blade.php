<style>
    .review {
        background-color: #f0f0e9;
        padding: 10px;
        margin-bottom: 15px;
    }
</style>


<script>
    $(document).ready(function() {
        $(function() {
            $("#rateYo1").rateYo({
                    starWidth: "20px",
                    fullstar: true,
                })
                .on("rateyo.set", function(e, data) {
                    $('.rating_comment').val(data.rating);
                    // alert("The rating is set to " + data.rating + "!");
                });
        });
        var comments_product = document.getElementsByName('array_id[]');
        var arr_rating_cmt = [];
        for (var i = 0; i < comments_product.length; i++) {
            var id_comment = comments_product[i].value;
            var rating_cmt = $("#rating_comment_" + id_comment).data('rating');
            arr_rating_cmt.push(rating_cmt);
        }

        $(".rateYo_cmt").each(function(i) {
            $(this).rateYo({
                rating: arr_rating_cmt[i],
                starWidth: "20px",
                readOnly: true
            });
        });

        $('button.add_comment').click(function() {

            var user_name = $('.user_name').val();
            var user_email = $('.user_email').val();
            var user_id = $('.user_id').val();
            var product_id = $('.product_id').val();
            var comment = $('.comment').val();
            var rating_comment = $('.rating_comment').val();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                type: "POST",
                url: "{{ route('add.product_comment_ajax') }}",
                data: {
                    user_name: user_name,
                    user_email: user_email,
                    comment: comment,
                    user_id: user_id,
                    product_id: product_id,
                    rating_comment: rating_comment,
                    _token: _token,
                },
                success: function() {

                    $('.alert-comment').css('display', 'block');
                    $('.alert-comment').html('<p>Bạn đã thêm bình luận thành công, bình luận của bạn sẽ được admin xét duyệt trước khi đăng.</> ')
                    $('.alert-comment').fadeOut(9000);
                    $('.comment').val('');
                }
            });
        });
    });
</script>

<div class="category-tab shop-details-tab">
    <!--category-tab-->
    <div class="col-sm-12">
        <ul class="nav nav-tabs">
            <li><a href="#details" data-toggle="tab">Chi tiết </a></li>

            <li class="active"><a href="#reviews" data-toggle="tab">Đánh giá</a></li>
        </ul>
    </div>
    <div class="tab-content">
        <div style='margin-left:20px;' class="tab-pane fade" id="details">
            {!! $product->content!!}
            <!-- <div class="col-sm-3">
                <div class="product-image-wrapper">
                    <div class="single-products">
                        <div class="productinfo text-center">
                            <img src="images/home/gallery1.jpg" alt="" />
                            <h2>$56</h2>
                            <p>Easy Polo Black Edition</p>
                            <button type="button" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
                        </div>
                    </div>
                </div>
            </div>
            -->
        </div>



        <div class="tab-pane fade active in" id="reviews">
            <div class="col-sm-12">
                @if($comments->count()>0)

                @foreach($comments as $comment)
                <div class="review" style='position:relative;'>


                    <ul>
                        <li><a href=""><i class="fa fa-user"></i>{{$comment->customer->name}}</a></li>
                        <li><a href=""><i class="fa fa-clock-o"></i>{{$comment->created_at}}</a></li>
                        <input type="hidden" name="array_id[]" id="rating_comment_{{$comment->id}}" data-rating="{{$comment->rating}}" value="{{$comment->id}}">
                        <span style='position:absolute; top:10px; right:60px;' class='rating_comment_customer rateYo_cmt'>

                        </span>
                        <span style='position:absolute; top:10px; right:12px;'>({{$comment->rating}}/5)</span>
                        <!-- <li><a href=""><i class="fa fa-calendar-o"></i>31 DEC 2014</a></li> -->
                    </ul>
                    <p>{{$comment->comment}}</p>
                    <?php $reply_comment = $comment->reply_comment; ?>

                    @if($reply_comment->count()>0)
                    @foreach($reply_comment as $item)

                    <div class="review" style="margin-bottom: 0px; padding-bottom: 0px;">
                        <ul>
                            <li><a href=""><i class="fa fa-users"></i>{{$item->user->name}}</a></li>
                            <li><a href=""><i class="fa fa-clock-o"></i>{{$item->created_at}}</a></li>
                            <!-- <li><a href=""><i class="fa fa-calendar-o"></i>31 DEC 2014</a></li> -->
                        </ul>
                        <p>{{$item->comment}}</p>

                    </div>
                    @endforeach

                    @endif

                </div>

                @endforeach
                @endif
                <?php

                use Illuminate\Support\Facades\Auth;

                $user_name = "";
                $user_email = "";
                $user_id = "";
                if (Auth()->check()) {
                    $user_name = Auth()->user()->name;
                    $user_email = Auth()->user()->email;
                    $user_id = Auth()->user()->id;
                }

                ?>
                <div class="alert alert-success alert-comment" style="display:none">
                </div>
                <p style="margin-top:50px;"><b>Viết đánh giá của bạn về sản phẩm</b>
                    @if(!Auth()->check())
                    <span style='color:red; margin-left:10px;'>
                        (Bạn cần đăng nhập để đánh giá về sản phẩm này.)
                    </span>
                    @endif
                </p>

                <form>
                    @csrf
                    <span>
                        <input type="hidden" class="product_id" value="{{$product->id}}">
                        <input type="hidden" class="user_id" value="{{$user_id}}">
                        <input type="hidden" class="rating_comment" value="">
                        <input type="text" placeholder="Họ và tên" class="user_name" value="{{$user_name}}" disabled />
                        <input type="email" placeholder="Email " class="user_email" value="{{$user_email}}" disabled />
                    </span>

                    <textarea name="comment" class="comment"> </textarea>

                    <div class='ratting_cmt' style='position: relative;
    margin-bottom: 20px;'>
                        <b>Rating: </b>
                        <div id="rateYo1" style='position: absolute;
    right: 0px;
    top: 0px;'></div>
                    </div>


                    <button type="button" class="btn btn-default pull-right add_comment" name="add_comment" {{Auth()->check()?"":"disabled"}}>
                        Bình luận
                    </button>
                </form>

            </div>
        </div>



    </div>
</div>
<!--/category-tab-->