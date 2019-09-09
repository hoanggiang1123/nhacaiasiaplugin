<?php 

class KHUYENMAI_META {

    public function __construct() {
        add_action('add_meta_boxes', array($this, 'create_listing_meta_box'),10,2);
        add_action('save_post',array($this,'listing_save_khuyenmai'));
        add_action('admin_enqueue_scripts', array($this,'khuyen_mai_add_scripts'));
        add_action('admin_enqueue_scripts', array($this,'khuyenmai_inline_css'));
    }

    public function create_listing_meta_box() {
        add_meta_box('khuyen_mai_fields','Danh Sách Khuyến mãi', array($this,'display'),'listing','normal','default');
    }
    
    public function display($post) {
        $khuyen_mai_post_id = get_post_meta($post->ID,'khuyen_mai_post_id', true);
        $initial = '[]';
        if($khuyen_mai_post_id != '') {
            $ids = explode(',',$khuyen_mai_post_id);
            
            $posts = get_posts(['post__in'=>$ids,'post_type'=>'events']);
            $initial = '[';

            if(count($post) > 0) {
                foreach($posts as $key => $post) {
                    if($key == count($posts) - 1) {
                        $initial.= '{post_id: '.$post->ID.',title:"'.$post->post_title.'"}';
                    } else {
                        $initial.= '{post_id: '.$post->ID.',title:"'.$post->post_title.'"},';
                    }
                    
                }
            }

            $initial.= ']';

        }
        wp_nonce_field('khuyen_mai_fields','khuyen_mai_fields');?>
            
            <div class="promo" id="app">
                <div class="msg" v-show="showMessage"><div>{{message}}</div></div>
                <div class="promo__wrap">
                    <div class="promo__header">
                        <input class="promo__search" type="text" @keyup="loadSearch" placeholder="Tìm Khyến Mãi..." v-model="query">
                        <div class="promo__res" v-if="results.length && query !==''">
                            <ul>
                                <li v-for="(res,index) in results" :key="index"><a href="javascript:;">{{res.title}}</a><a class="add" href="javascript:;" @click="addPromo(res.post_id,res.title)"><img src="<?php echo NHACAI__PLUGIN_URL.'/img/add.png';?>" ></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="promo__list" v-if="promos.length">
                        <ul>
                            <li v-for="(promo,index) in promos" :key="promo.post_id"><input type="hidden" name="khuyen_mai_post_id[]" :value="promo.post_id"><a :href="domain+'/wp-admin/post.php?post='+promo.post_id+'&action=edit'">{{promo.title}}</a><a href="javascript:;" @click="deletePromo(promo.post_id)"><img src="<?php echo NHACAI__PLUGIN_URL.'/img/delete.png';?>" ></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <script>
                const promo = new Vue({
                    el:'#app',
                    data: {
                        results:[],
                        promos:<?php echo $initial;?>,
                        query: '',
                        message: '',
                        domain: '<?php echo home_url();?>'
                    },
                    methods: {
                        async loadSearch() {
                            let headers = {'Content-type': 'application/x-www-form-urlencoded'};
                            let body = new FormData;
                            body.append('query',this.query);
                            body.append('action','load_search');
                            const res = await axios.post('<?php echo admin_url('admin-ajax.php');?>',body,{headers:headers});
                            if(res.data) {
                                this.results = res.data;
                            }
                        },
                        addPromo(post_id,title) {
                            let check = false;
                            if(this.promos.length < 1 ) {
                                check = false;
                            } else {
                                for(let i = 0; i< this.promos.length; i++) {
                                    if(this.promos[i].post_id == post_id){
                                        
                                        check = true;
                                        this.message = 'Đã có khuyến mại này';
                                        return;
                                    } else {
                                        check = false;
                                    }
                                }
                            }
                            
                            if(check == false) {
                               this.promos = [... this.promos,{post_id,title}];
                              
                            }
                        },
                        deletePromo(post_id) {
                            this.promos = this.promos.filter(promo => promo.post_id != post_id);
                        },
                    },
                    computed: {
                        showMessage() {
                            return this.message != '';
                        }
                    },
                    watch:{
                        message(val) {
                            if(val) {
                                let that = this;
                               setTimeout(function() {
                                    that.message = '';
                               },2000)
                            }
                        }
                    }
                });
            </script>
        <?php
    }

    public function listing_save_khuyenmai($post_id) {

        if (!isset($_POST['khuyen_mai_fields']) || !wp_verify_nonce($_POST['khuyen_mai_fields'], 'khuyen_mai_fields')) return;

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        
        if (!current_user_can('edit_post', $post_id)) return;

        $value = get_post_meta($post_id,'khuyen_mai_post_id',true);

        if( count($_POST['khuyen_mai_post_id']) > 0) {
          
            if($value != '') {

                $valueArr = explode(',',$value);

                $khuyen_mai_id_arr = $_POST['khuyen_mai_post_id'];

                $res = array_diff($valueArr, $khuyen_mai_id_arr);

                if(count($res) > 0) {

                    foreach($res as $d) {

                        $bookerId = get_post_meta($d,'nhacai_post_id',true);

                        delete_post_meta($d,'nhacai_post_id',$bookerId);
                    }

                    $khuyen_mai_post_id = implode(',',$_POST['khuyen_mai_post_id']);

                    update_post_meta($post_id,'khuyen_mai_post_id',$khuyen_mai_post_id);
                    
                    foreach($khuyen_mai_id_arr as $kmid) {

                        update_post_meta($kmid,'nhacai_post_id',$post_id);
                    }
                }

            } else {

                $khuyen_mai_post_id = implode(',',$_POST['khuyen_mai_post_id']);

                update_post_meta($post_id,'khuyen_mai_post_id',$khuyen_mai_post_id);

                $kmarr = $_POST['khuyen_mai_post_id'];

                foreach($kmarr as $km) {
                    update_post_meta($km,'nhacai_post_id',$post_id);
                }
            }

        } else if(empty($_POST['khuyen_mai_post_id']) && $value){

            delete_post_meta($post_id,'khuyen_mai_post_id',$value);

            $valueArr = explode(',',$value);

            foreach($valueArr as $val){

                $bookerId = get_post_meta($val,'nhacai_post_id',true);

                delete_post_meta($val,'nhacai_post_id',$bookerId);
            }
        }
    }

    public function khuyen_mai_add_scripts() {
        wp_enqueue_script('vuejs','//cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.min.js',[],'1.0.0',false);
        wp_enqueue_script('axios','//cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.min.js',[],'1.0.0',false);
    }

    public function khuyenmai_inline_css() {
        ?>
            <style>
                .promo__header{position:relative;width:100%}.promo__res{position:absolute;width:100%;left:0;background:#f5f5f5;z-index:999}.promo__res li{display:flex;border-bottom:1px solid #ccc;padding:5px 0;align-items:center;justify-content:space-between}.promo__res li a{padding:5px 10px;margin-right:10px;text-decoration:none;color:#000}.promo__res li a.add img{height:30px;width:30px}.promo__wrap{display:grid;grid-template-columns:1fr 1fr;grid-gap:20px;padding:15px 0}.promo__header input{width:100%;padding:5px}.promo__list ul{margin:0}.promo__list ul li{display:flex;align-items:center;justify-content:space-between;padding:0 5px;background:#006799}.promo__list ul li a{text-decoration:none;padding:5px 0;color:#fff;font-size:.9rem}.promo__list ul li a:last-child{width:15%;text-align:right}.promo__list ul li a:first-child{width:80%;margin-right:auto}.promo__list ul li a:last-child img{width:30px;height:30px}.msg div{padding: 12px;color: #721c24;background-color: #f8d7da;border-color: #f5c6cb;}
            </style>
        <?php
    }

}