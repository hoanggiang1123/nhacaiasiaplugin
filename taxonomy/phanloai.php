<?php 

class CustomPhanLoaiNhaCai{

	public function __construct(){

		add_action('init',array($this,'create_phan_loai'));
	}

	public function create_phan_loai(){

		$labels = array(
			'name'				=> __( 'Phân Loại ', 'nhacaiasia' ),
			'singular' 			=> __( 'Phân Loại ', 'nhacaiasia' ),
			'menu_name'			=> __( 'Phân Loại ', 'nhacaiasia' ),
			'edit_item'			=> __( 'Chỉnh Sửa', 'nhacaiasia' ),
			'update_item'		=> __( 'Cập Nhật', 'nhacaiasia' ),
			'add_new_item'		=> __( 'Thêm Phân Loại ', 'nhacaiasia' ),
			'search_items'		=> __( 'Tìm Kiếm', 'nhacaiasia' ),
			'popular_items'		=> __( 'Phân Loại Đang Dùng', 'nhacaiasia' ),
			'separate_items_with_commas' => __( 'Ngăn Cách Bằng Dấu Phẩy', 'nhacaiasia' ),
			'choose_from_most_used' => __( 'Chọn Từ Phân Loại Nổi Bật', 'nhacaiasia' ),
			'not_found'			=> __( 'Không Thấy Phân Loại ', 'nhacaiasia' )

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
			'rewrite'				=> array('slug' => 'phan-loai'),
		);
		register_taxonomy('phan-loai', 'listing',$args);
	}
}
