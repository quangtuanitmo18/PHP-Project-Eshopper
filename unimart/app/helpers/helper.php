<?php

if (!function_exists('showCategories')) {
    function showCategories($categories,&$post_cats, $parent_id = 0, $char = '')
    {
        foreach ($categories as $key=>$item)
        {
            // Nếu là chuyên mục con thì hiển thị
            if ($item->parent_id == $parent_id)
            {
                $post_cats[$item->id] = array();
                $post_cats[$item->id]['name']=$char . $item->name;
     
                // Xóa chuyên mục đã lặp
                unset($categories[$key]);
                 
                // Tiếp tục đệ quy để tìm chuyên mục con của chuyên mục đang lặp
                showCategories($categories,$post_cats, $item->id, $char.'|---');
            }
        }
    }
}


?>