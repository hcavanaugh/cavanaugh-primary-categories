<?php
 
/**
 * The Metaboxes class.
 */

if(!class_exists('Primary_Categories_Metaboxes')):
    class Primary_Categories_Metaboxes {
    
        public function __construct() {
            add_action( 'add_meta_boxes', array( $this, 'add_primary_categories_meta_boxes' ) );
            add_action( 'save_post',      array( $this, 'save_primary_categories' ) );
        }

        /**
         * Create the meta box for primary categories for each taxonomy (category and custom taxonomies)
         *
         * @param string $post_type The post type (post or custom post type)
         */
        public function add_primary_categories_meta_boxes($post_type) {
            $taxonomies = get_object_taxonomies( $post_type, 'objects' );
            foreach($taxonomies as $taxonomy):
                if($taxonomy->name !== 'post_tag' && $taxonomy->name !== 'post_format'):
                    add_meta_box(
                        $taxonomy->name.'_primary_category',
                        __( 'Primary '.$taxonomy->labels->singular_name, 'textdomain' ),
                        [ self::class, 'get_categories_for_metabox' ],
                        $post_type,
                        'side',
                        'high',
                        array( '__block_editor_compatible_meta_box' => true, 'taxonomy' => $taxonomy ),
                    );
                endif;
            endforeach;
        }
    
        /**
         * Save primary categories metadata when the post is saved
         *
         * @param int $post_id The post ID being saved
         */
        public function save_primary_categories( $post_id ) {
			$post_type = get_post_type( $post_id );
            $taxonomies = get_object_taxonomies( $post_type, 'objects' );

            //Ensure nonce is set and verified. If false, don't save
            if ( ! isset( $_POST['primary_category_metabox_nonce'] ) ):
                return $post_id;
			endif;
    
            $nonce = $_POST['primary_category_metabox_nonce'];
    
            if ( ! wp_verify_nonce( $nonce, 'primary_category_metabox' ) ):
                return $post_id;
			endif;
    
            //Prevent action if this is an autosave.
            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE || wp_is_post_autosave( $post_id ) ):
                return $post_id;
			endif;
    
            //Only allow those who can edit posts to save
            if ( ! current_user_can( 'edit_post', $post_id ) ):
                return $post_id;
			endif;
    
            foreach( $taxonomies as $taxonomy ):
                $taxonomy_name = $taxonomy->name;
                //Ignore post tags and post format
                if( $taxonomy_name !== 'post_tag' && $taxonomy_name !== 'post_format' ):
                    //Get all taxonomy vallues
                    $tax_values = wp_get_post_terms( $post_id, $taxonomy_name, array( 'fields' => 'names' ) );

                    $saved_value = sanitize_text_field( $_POST[$taxonomy_name.'_primary_field'] );
                    //Verify that saved category value are from existing terms
                    if( in_array( $saved_value, $tax_values ) ):
                        update_post_meta ( $post_id, '_primary_post_'.$taxonomy_name, $saved_value );
					endif;
                endif;
            endforeach;
        }
    
    
        /**
         * Render the Primary Category Meta Box content.
         *
         * @param WP_Post $post The post object.
         * @param array $args The add_meta_box callback args
         */
        public static function get_categories_for_metabox( $post, $args ) {
			//Get taxonomy for this meta box
            $taxonomy = $args["args"]["taxonomy"];
            $taxonomy_name = $taxonomy->name;
            $taxonomy_label = $taxonomy->labels->singular_name;
            // $taxonomies = get_object_taxonomies( $post->post_type, 'objects' );
            // var_dump($taxonomies);

            // Add an nonce field so we can check on save
            wp_nonce_field( 'primary_category_metabox', 'primary_category_metabox_nonce' );
    
            // Use get_post_meta to retrieve an existing value from the database.
            $cat_value = get_post_meta( $post->ID, '_primary_post_'.$taxonomy_name, true );
            $terms = wp_get_post_terms( $post->ID, $taxonomy_name, array( 'fields' => 'names' ) );
            // Display the form
            ?>
            <select name="<?php echo $taxonomy_name ?>_primary_field" id="<?php echo $taxonomy_name ?>_primary_field" aria-label="Select a primary <?php echo $taxonomy_label; ?>">
                <option value="0">Select <?php echo $taxonomy_label; ?></option>
                <?php
                foreach($terms as $term):
                    //Render each term in dropdown. If post has a primary category selected, add selected markup to option element.
                    echo '<option value="' . $term . '"' . selected( $cat_value, $term ) . '>' . $term . '</option>';
                endforeach;
                ?>
            </select>
            <?php

        }
    }

endif;