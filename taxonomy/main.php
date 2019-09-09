<?php
class NHACAIASIA_CUSTOM_TAXONOMY {
    public function __construct() {
        $this->cacsanh();
        $this->sanpham();
        $this->phanloai();
    }

    public function cacsanh() {
        require_once NHACAI__PLUGIN_DIR.'/taxonomy/sanh.php';
        new CustomCacSanhNhaCai();
    }
    public function sanpham() {
        require_once NHACAI__PLUGIN_DIR.'/taxonomy/sanpham.php';
        new CustomSanPhamNhaCai();
    }

    public function phanloai() {
        require_once NHACAI__PLUGIN_DIR.'/taxonomy/phanloai.php';
        new CustomPhanLoaiNhaCai();
    }
	
}