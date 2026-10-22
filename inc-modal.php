<!-- ~ ここにモーダルのhtmlを書く~ -->
<style>
    /*モーダルを開くボタン*/
.modal-open{
    position: fixed;
    display: flex;
    align-items: center;
    justify-content: center;
    top: 50%;
    left: 50%;
    width: 300px;
    height: 50px;
    font-weight: bold;
    color: #fff;
    background: #000;
    margin: auto;
    cursor: pointer;
    transform: translate(-50%,-50%);
}
/*モーダル本体の指定 + モーダル外側の背景の指定*/
.modal-container{
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    text-align: center;
    background: rgba(0,0,0,50%);
    padding: 40px 20px;
    overflow: auto;
    opacity: 0;
    visibility: hidden;
    transition: .3s;
    box-sizing: border-box;
}
/*モーダル本体の擬似要素の指定*/
.modal-container:before{
    content: "";
    display: inline-block;
    vertical-align: middle;
    height: 100%;
}
/*モーダル本体に「active」クラス付与した時のスタイル*/
.modal-container.active{
    opacity: 1;
    visibility: visible;
    z-index: 9999;
}
/*モーダル枠の指定*/
.modal-body{
    position: relative;
    display: inline-block;
    vertical-align: middle;
    max-width: 500px;
    width: 90%;
}
/*モーダルを閉じるボタンの指定*/
.modal-close{
    position: absolute;
    display: flex;
    align-items: center;
    justify-content: center;
    top: 16px;
    right: 16px;
    width: 40px;
    height: 40px;
    font-size: 40px;
    color: #fff;
    cursor: pointer;
    background-color: #61c2da;
    z-index: 99999;
}
/*モーダル内のコンテンツの指定*/
.modal-content{
    background: #fff;
    text-align: left;
    padding: 30px;
}
</style>

<script>
<?php
    // 開いてるページのカテゴリーのデータを取得
    $cat_ID = 0;
    
    if ( !empty( get_the_category()) && is_single()) {
        //これが見てる記事のカテゴリID   ↑ 投稿ページならtrue
        $cat_ID = get_the_category()[0]->cat_ID; 
    }
        
    $in_cat_id = 'false'; // javascript で使うので文字列にしておく
    
    // wp_optionsテーブルから特定のキーで値を取得
    $modal_settings = get_option('modal_scroll');
    
    // DBに無いと falseを返してくるので､初期値を入れる
    if (!$modal_settings) {
        $modal_settings = [
            'image_path' => '',
            'top' => 0,
            'cat_id' => [],
            'url' => ''
        ];
    }

    // カテゴリはキーが cat_id､ 値は配列になってる
    foreach ($modal_settings['cat_id'] as $cat_id){
        if ($cat_ID == $cat_id){
            $in_cat_id = 'true';
            break;  // 一致したらループを抜ける    
        }
    }
?>

    
jQuery(function ($) {
    
    // 変数に要素を入れる
    var open = $('.modal-open'),
        close = $('.modal-close'),
        container = $('.modal-container'),
        not_deleted = true ,
        in_cat_id = <?=$in_cat_id?> ;
        
        
    //閉じるボタンをクリックしたらモーダルを閉じる
    close.on('click',function(){	
        container.removeClass('active');
        not_deleted = false;
    });
    
    //モーダルの外側をクリックしたらモーダルを閉じる
    $(document).on('click',function(e) {
        if(!$(e.target).closest('.modal-body').length) {
            container.removeClass('active');
            not_deleted = false;
        }
    });


    // スクロールイベント    
    $(window).on("scroll", function () {
        if ($(this).scrollTop() > <?= $modal_settings['top'] ?> 
        && not_deleted 
        && in_cat_id ) {
            // ページトップ以外の処理
            // console.log($(this).scrollTop()) // ← コンソール確認
            container.addClass('active');
            return false;
        } else {
            // ページトップの処理
        }
    });
});

</script>



<!-- モーダル本体 -->
<div class="modal-container">
    <div class="modal-body">
        <!-- 閉じるボタン -->
        <div class="modal-close">×</div>
        <!-- モーダル内のコンテンツ -->
        <div class="modal-content">
            <a href="<?=$modal_settings['url']?>">
                <img src="<?=$modal_settings['image_path']?>" alt="">
            </a>

        </div>
    </div>
</div>