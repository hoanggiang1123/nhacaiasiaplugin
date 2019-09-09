<?php 

class NHACAI_META {

    public function __construct() {
        add_action('add_meta_boxes', array($this, 'create_events_meta_box'),10,2);
        add_action('save_post',array($this,'events_save_nhacai'));
    }

    public function create_events_meta_box() {
        add_meta_box('nhacai_fields','Danh Sách Nhà Cái', array($this,'display'),'events','side','high');
    }
    
    public function display($post) {
        $nhacai_post_id = get_post_meta($post->ID,'nhacai_post_id', true);
        $args = array('posts_per_page'=>-1,'post_type'=>'listing','orderby'=>'ID','order'=>'DESC');
        $nhacai = new wp_query($args);
        wp_nonce_field('nhacai_fields','nhacai_fields');
        
        if($nhacai ->have_posts()):
        ?>
            
            
            <div class="nhacai">
                <div class="nhacai__select">
                  
                    <select name="nhacai_post_id" id="nhacai_post_id" class="nhacaiid" style="width:100%;">
                            <option value="">Chọn Nhà Cái</option>
                        <?php while($nhacai->have_posts()) : $nhacai->the_post();?>
                            <option value="<?php echo get_the_ID();?>" <?php echo (!empty($nhacai_post_id) && $nhacai_post_id == get_the_ID())? 'selected': ''; ?>><?php the_title();?></option>
                        <?php endwhile; wp_reset_postdata();?>
                    </select>
                </div>
            </div>

        <?php endif;
    }

    public function events_save_nhacai($post_id) {
        if (!isset($_POST['nhacai_fields']) || !wp_verify_nonce($_POST['nhacai_fields'], 'nhacai_fields')) return;

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        
        if (!current_user_can('edit_post', $post_id)) return;

        $value = get_post_meta($post_id,'nhacai_post_id',true);

        if($_POST['nhacai_post_id'] != '' && $value != $_POST['nhacai_post_id']) {

            $nhacaiId = $_POST['nhacai_post_id'];

            if($value != '') {
                $oldnc = get_post_meta($value,'khuyen_mai_post_id',true);

                $oldarr = explode(',',$oldnc);

                $newa = [$post_id];

                $res = array_diff($oldarr,$newa);

                if(count($res) >1) {

                    $new = implode(',',$res);

                    update_post_meta($value,'khuyen_mai_post_id',$new);

                } else {

                    update_post_meta($value,'khuyen_mai_post_id',$res[0]);
                }
                
            }

            update_post_meta($post_id,'nhacai_post_id',$nhacaiId);

            $nhacai_km = get_post_meta($nhacaiId,'khuyen_mai_post_id',true);

            if($nhacai_km == '') {

                update_post_meta($nhacaiId,'khuyen_mai_post_id',$post_id);
            } else {
                
                $nhacaipmarr = explode(',',$nhacai_km);

                if(!in_array($post_id,$nhacaipmarr)) {

                    $new_promo = $nhacai_km.','.$post_id;

                    update_post_meta($nhacaiId,'khuyen_mai_post_id',$new_promo);
                }
                
            }
        }
    }
   
}