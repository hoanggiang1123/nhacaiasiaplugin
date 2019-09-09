<?php 

class CustomCacSanhNhaCai{

	public function __construct(){

		add_action('init',array($this,'create_sanh'));
	}

	public function create_sanh(){

		$labels = array(
			'name'				=> __( 'Các Sảnh ', 'nhacaiasia' ),
			'singular' 			=> __( 'Các Sảnh ', 'nhacaiasia' ),
			'menu_name'			=> __( 'Các Sảnh ', 'nhacaiasia' ),
			'edit_item'			=> __( 'Chỉnh Sửa', 'nhacaiasia' ),
			'update_item'		=> __( 'Cập Nhật', 'nhacaiasia' ),
			'add_new_item'		=> __( 'Thêm Sảnh ', 'nhacaiasia' ),
			'search_items'		=> __( 'Tìm Kiếm', 'nhacaiasia' ),
			'popular_items'		=> __( 'Các Sảnh Đang Dùng', 'nhacaiasia' ),
			'separate_items_with_commas' => __( 'Ngăn Cách Bằng Dấu Phẩy', 'nhacaiasia' ),
			'choose_from_most_used' => __( 'Chọn Từ Các Sảnh Nổi Bật', 'nhacaiasia' ),
			'not_found'			=> __( 'Không Thấy Sảnh ', 'nhacaiasia' )

		);
		$args = array(
			'labels' 				=> $labels,
			'public'				=> true,
			// 'show_ui'				=> false,
			// 'show_in_nav_menus'	    => false,
			'show_tagcloud'			=> true,
			'hierarchical'			=> false,
			'show_admin_column'		=> true,
			'query_var'				=> true,
			'rewrite'				=> array('slug' => 'cac-sanh'),
		);
		register_taxonomy('cac-sanh', 'listing',$args);
	}
}
