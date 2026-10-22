<?php
/*
Plugin Name: modal scroll img
Description: スクロールで画像がモーダル表示
*/

add_action('admin_menu', 'my_add_admin_menu');
/**
 * 「設定」にメニューを追加
 */
function my_add_admin_menu()
{

    add_menu_page(    // ← こっちに変える
        'モーダル設定ページ',
        'モーダル設定',
        'manage_options',
        'my-original-menu',
        'my_original_menu_page'
    );
}

/**
 * 設定ページの中身を作成
 */
function my_original_menu_page()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }


    // 受信用の処理 
    if (
        !empty($_POST['image_path'])
        && !empty($_POST['cat_id'])
        && !empty($_POST['top'])
        && !empty($_POST['url'])
    ) {
        // ↓ これでoptionsテーブルに入る､無ければ追加になる
        $res = update_option('modal_scroll', $_POST);

        if ($res) {
            echo "追加しました";
        } else {
            echo "追加できませんでした";
        }
    }

    // 今更新した値を取り出してみる
    // 'modal_scroll' をアンシリアライズして配列として取得
    $modal_settings = get_option('modal_scroll');

    // !でfalseをtrueに反転
    if (!$modal_settings) {
        $modal_settings = [
            'image_path' => '',
            'top' => 0,
            'cat_id' => [],
            'url' => ''
        ];
    }
    ?>



    <!-- 送信用のHTML -->
    <div class="wrap">
        <h1>モーダル設定ページ</h1>
        <form method="POST"> <!--action は削除-->

            <p>
                <label>広告画像</label>
                <input type="text" name="image_path" value="<?= $modal_settings['image_path'] ?>">
            </p>

            <p><label>スクロールの距離 </label>
                <input type="number" name="top" value="<?= $modal_settings['top'] ?>">
            </p>

            <p><label>カテゴリ選択(複数可) </label>
                <select name="cat_id[]" multiple>

                    <?php
                    $selected = "";
                    $categories = get_categories();
                    foreach ($categories as $category) {
                        // $modal_settings['cat_id'] は配列なので､cat_IDが含まれてるかを判定
                        if (in_array($category->cat_ID, $modal_settings['cat_id'])) {
                            $selected = 'selected';
                        }
                        ?>
                        <option value="<?= $category->cat_ID ?>" <?= $selected ?>>
                            <?= $category->name ?>
                        </option>
                    <?php } ?>

                </select>
            </p>

            <p><label>広告リンク先 </label>
                <input type="url" name="url" value="<?= $modal_settings['url'] ?>">
            </p>

            <?php
            submit_button();
            ?>
        </form>
    </div>

    <?php
}
// my_original_menu_page の閉じ
add_filter(
    'the_content',
    function ($content) {
        if (!is_admin()) {
            include_once "inc-modal.php";
            return $content;
        }
    },
    10
);