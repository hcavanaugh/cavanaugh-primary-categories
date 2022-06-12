# Cavanaugh Primary Categories plugin for WordPress

Tested with:
    
Theme: TwentyTwentyOne
    
WP Version: 6.0

FAQs:

- When I click on a category page, it returns a 404 error
    - This can happen, especially on a new install when adding new categories. Flush your permalinks:
        - Go to the WordPress Admin -> Settings -> Permalinks. Change the permalink setting to Post name 
        (if it is already set to that, change it to something else, save, then change the setting back to Post name)
        - Click 'Save Changes'. If the 404 error still persists, clear your cache.

- How do I query for posts in a primary category?
    - In PHP, run WP_Query with the following args:
    ```php
    $args = [
            'post_type' => $post_type, //post or custom post type
            'meta_key' => '_primary_post_'.$taxonomy_name, //post category or custom taxonomies, $taxonomy_name will always include taxonomy name. eg 'category', 'movie_genres'
            'meta_value' => $term //name of term you wish to query by (which is the name of term) Eg. 'Sample Category', 'Horror'
        ]
    ```

