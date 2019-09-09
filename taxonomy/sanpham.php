<?php 

class CustomSanPhamNhaCai{

	public function __construct(){

		add_action('init',array($this,'create_san_pham'));
	}

	public function create_san_pham(){

		$labels = array(
			'name'				=> __( 'Sản Phẩm ', 'nhacaiasia' ),
			'singular' 			=> __( 'Sản Phẩm ', 'nhacaiasia' ),
			'menu_name'			=> __( 'Sản Phẩm ', 'nhacaiasia' ),
			'edit_item'			=> __( 'Chỉnh Sửa', 'nhacaiasia' ),
			'update_item'		=> __( 'Cập Nhật', 'nhacaiasia' ),
			'add_new_item'		=> __( 'Thêm Sản Phẩm ', 'nhacaiasia' ),
			'search_items'		=> __( 'Tìm Kiếm', 'nhacaiasia' ),
			'popular_items'		=> __( 'Sản Phẩm Đang Dùng', 'nhacaiasia' ),
			'separate_items_with_commas' => __( 'Ngăn Cách Bằng Dấu Phẩy', 'nhacaiasia' ),
			'choose_from_most_used' => __( 'Chọn Từ Sản Phẩm Nổi Bật', 'nhacaiasia' ),
			'not_found'			=> __( 'Không Thấy Sản Phẩm ', 'nhacaiasia' )

		);
		$args = array(
			'labels' 				=> $labels,
			'public'				=> true,
			// 'show_ui'				=> false,
			// 'show_in_nav_menus'	    => false,
			'show_tagcloud'			=> true,
			'hierarchical'			=> true,
			'show_admin_column'		=> true,
			'query_var'				=> true,
			'rewrite'				=> array('slug' => 'san-pham'),
		);
		register_taxonomy('san-pham', 'listing',$args);
	}
}
