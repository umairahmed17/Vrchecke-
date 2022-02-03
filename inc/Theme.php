<?php
/**
 * VRCHECKE\VRCHECKE\Theme class
 *
 * @package vrchecke
 */

namespace VRCHECKE\VRCHECKE;

use InvalidArgumentException;

/**
 * Main class for the theme.
 *
 * This class takes care of initializing theme features and available template tags.
 */
class Theme
{

    /**
     * Associative array of theme components, keyed by their slug.
     *
     * @var array
     */
    protected $components = array();

    /**
     * The template tags instance, providing access to all available template tags.
     *
     * @var Template_Tags
     */
    protected $template_tags;

    /**
     * Constructor.
     *
     * Sets the theme components.
     *
     * @param array $components Optional. List of theme components. Only intended for custom initialization, typically
     *                          the theme components are declared by the theme itself. Each theme component must
     *                          implement the Component_Interface interface.
     *
     * @throws InvalidArgumentException Thrown if one of the $components does not implement Component_Interface.
     */
    public function __construct( array $components = array() )
    {
        if ( empty( $components ) ) {
            $components = $this->get_default_components();
        }

        // Set the components.
        foreach ( $components as $component ) {

            // Bail if a component is invalid.
            if ( ! $component instanceof Component_Interface ) {
                throw new InvalidArgumentException(
                    sprintf(
                        /* translators: 1: classname/type of the variable, 2: interface name */
                        __( 'The theme component %1$s does not implement the %2$s interface.', 'vrchecke' ),
                        gettype( $component ),
                        Component_Interface::class
                    )
                );
            }

            $this->components[$component->get_slug()] = $component;
        }

        // Instantiate the template tags instance for all theme templating components.
        $this->template_tags = new Template_Tags(
            array_filter(
                $this->components,
                function ( Component_Interface $component ) {
                    return $component instanceof Templating_Component_Interface;
                }
            )
        );
    }

    /**
     * Adds the action and filter hooks to integrate with WordPress.
     *
     * This method must only be called once in the request lifecycle.
     */
    public function initialize()
    {
        array_walk(
            $this->components,
            function ( Component_Interface $component ) {
                $component->initialize();
            }
        );
        add_action( 'after_switch_theme', array( $this, 'action_activation_theme' ) );
        add_action( 'init', array( $this, 'custom_post_type' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_custom_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_custom_meta_data' ) );
    }

    /**
     * Retrieves the template tags instance, the entry point exposing template tag methods.
     *
     * Calling `vrchecke()` is a short-hand for calling this method on the main theme instance. The instance then allows
     * for actual template tag methods to be called. For example, if there is a template tag called `posted_on`, it can
     * be accessed via `vrchecke()->posted_on()`.
     *
     * @return Template_Tags Template tags instance.
     */
    public function template_tags(): Template_Tags
    {
        return $this->template_tags;
    }

    /**
     * Retrieves the component for a given slug.
     *
     * This should typically not be used from outside of the theme classes infrastructure.
     *
     * @param string $slug Slug identifying the component.
     * @return Component_Interface Component for the slug.
     *
     * @throws InvalidArgumentException Thrown when no theme component with the given slug exists.
     */
    public function component( string $slug ): Component_Interface
    {
        if ( ! isset( $this->components[$slug] ) ) {
            throw new InvalidArgumentException(
                sprintf(
                    /* translators: %s: slug */
                    __( 'No theme component with the slug %s exists.', 'vrchecke' ),
                    $slug
                )
            );
        }

        return $this->components[$slug];
    }

    /**
     * Gets the default theme components.
     *
     * This method is called if no components are passed to the constructor, which is the common scenario.
     *
     * @return array List of theme components to use by default.
     */
    protected function get_default_components(): array
    {
        $components = array(
            new Localization\Component(),
            new Base_Support\Component(),
            new Editor\Component(),
            new Accessibility\Component(),
            new Image_Sizes\Component(),
            new AMP\Component(),
            new PWA\Component(),
            new Comments\Component(),
            new Nav_Menus\Component(),
            new Sidebars\Component(),
            new Custom_Background\Component(),
            new Custom_Header\Component(),
            new Custom_Logo\Component(),
            new Post_Thumbnails\Component(),
            new EZ_Customizer\Component(),
            new Styles\Component(),
            new Excerpts\Component(),
            new JavaScript\Component(),
            new Custom_Colors_Theme\Component(),
            new VRCHECKE_Form\Component(),
            new Google_Sign_In\Component(),
            new Login\Component(),
            new Admin_Form\Component(),
            new Customer_Portal\Component(),
            new Form_Customizer\Component(),
            new Email_Template\Component(),
        );

        if ( defined( 'JETPACK__VERSION' ) ) {
            $components[] = new Jetpack\Component();
        }

        return $components;
    }

    /**
     * Creates the Database Table for form Entries
     *
     * @return null
     */
    private function create_db_table()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name      = $wpdb->prefix . 'form_entries';

        $sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        user_id mediumint(9) NOT NULL,
		first_echeck BOOL NOT NULL,
		number_of_devices smallint(5) NOT NULL,
		type_of_devices text NOT NULL,
        postal_code VARCHAR(10) NOT NULL ,
        company_first_option VARCHAR(10) DEFAULT NULL ,
        company_second_option VARCHAR(10)DEFAULT NULL ,
        company_third_option VARCHAR(10)DEFAULT NULL ,
        company_selected_option VARCHAR(10) DEFAULT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );
    }

    /**
     * Deleting DB Table on theme deactivation
     */
    private function delete_db_table()
    {
        global $wpdb;
        $table  = $wpdb->prefix . 'form_entries';
        $delete = $wpdb->query( "TRUNCATE TABLE $table" );
    }

    /**
     * Storing Theme Version Number
     *
     * @return null
     */
    private function theme_version()
    {
        if ( ! get_option( 'vrchecke_theme_ver' ) ) {
            add_option( 'vrchecke_theme_ver', '1.0.0' );
        }
    }

    /**
     * Deletig Theme Version Number
     *
     * @return null
     */
    private function delete_theme_version()
    {
        if ( get_option( 'vrchecke_theme_ver' ) ) {
            delete_option( 'vrchecke_theme_ver' );
        }
    }

    /**
     * Creating DB Table and option
     *
     * @return null
     */
    public function action_activation_theme()
    {
        $this->theme_version();
        $this->create_db_table();
    }

    /**
     * Deleting DB Table and option
     *
     * @return null
     */
    public function action_deactivation_theme()
    {
        $this->delete_theme_version();
        $this->delete_db_table();
    }

    /**
     *
     * Custom Post Type for selecting companies
     *
     * Start.
     */
    /*
     * Creating a function to create our CPT
     */

    public function custom_post_type()
    {

        // Set UI labels for Custom Post Type
        $labels = array(
            'name'               => _x( 'Form Company', 'Post Type General Name', 'vrchecke' ),
            'singular_name'      => _x( 'Form Comapany', 'Post Type Singular Name', 'vrchecke' ),
            'menu_name'          => __( 'Form Companies', 'vrchecke' ),
            'parent_item_colon'  => __( 'Parent Form Comapany', 'vrchecke' ),
            'all_items'          => __( 'All Form Comapanies', 'vrchecke' ),
            'view_item'          => __( 'View Form Comapany', 'vrchecke' ),
            'add_new_item'       => __( 'Add New Form Comapany', 'vrchecke' ),
            'add_new'            => __( 'Add New', 'vrchecke' ),
            'edit_item'          => __( 'Edit Form Comapany', 'vrchecke' ),
            'update_item'        => __( 'Update Form Comapany', 'vrchecke' ),
            'search_items'       => __( 'Search Form Comapany', 'vrchecke' ),
            'not_found'          => __( 'Not Found', 'vrchecke' ),
            'not_found_in_trash' => __( 'Not found in Trash', 'vrchecke' ),
        );

        // Set other options for Custom Post Type

        $args = array(
            'label'               => __( 'Form Comapanies', 'vrchecke' ),
            'description'         => __( 'Form Comapany for form', 'vrchecke' ),
            'labels'              => $labels,
            // Features this CPT supports in Post Editor
            'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields' ),
            // You can associate this CPT with a taxonomy or custom taxonomy.
            'taxonomies'          => array( '' ),
            /* A hierarchical CPT is like Pages and can have
             * Parent and child items. A non-hierarchical CPT
             * is like Posts.
             */
            'hierarchical'        => true,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'capability_type'     => 'post',
            'show_in_rest'        => true,
        );

        // Registering your Custom Post Type
        register_post_type( 'form-companies', $args );

    }

    public function add_custom_meta_boxes()
    {
        add_meta_box( 'company_option_pdf', 'Form Company', array( $this, 'company_option_pdf' ), 'form-companies', 'normal', 'high' );
        add_meta_box( 'company_option_amount', 'Form Company', array( $this, 'company_option_amount' ), 'form-companies', 'normal', 'high' );
    }

    // add_action('add_meta_boxes', 'add_custom_meta_boxes');

    public function company_option_pdf( $post )
    {

        $pdf  = get_post_meta( $post->ID, 'company_option_pdf', true );
        $html = '';
        if ( is_array( $pdf ) && ! empty( $pdf['url'] ) ) {
            $pdf_url = esc_url( $pdf['url'] );
            $html .= '<embed src="' . $pdf_url . '" type="application/pdf" width="350" height="350"></embed>';
        }
        wp_nonce_field( plugin_basename( __FILE__ ), 'company_option_pdf_nonce' );
        $html .= '<p class="description">';
        $html .= 'Upload your PDF here.';
        $html .= '</p>';
        $html .= '<input type="file" id="company_option_pdf" name="company_option_pdf" value="" size="25">';
        echo $html;
    }

    public function company_option_amount( $post )
    {
        wp_nonce_field( plugin_basename( __FILE__ ), 'company_option_amount_nonce' );
        $html = '<p class="description">';
        $html .= 'Add the amount: ';
        $html .= '</p>';
        $amount = get_post_meta( $post->ID, 'company_option_amount', true );
        $html .= '<input type="number" id="company_option_amount" name="company_option_amount" value="' . esc_attr( $amount ) . '" >';
        echo $html;
    }

    // add_action('save_post', 'save_custom_meta_data');
    public function save_custom_meta_data( $id )
    {
        if ( ! empty( $_FILES['company_option_pdf']['name'] ) ) {
            $supported_types = array( 'application/pdf' );
            $arr_file_type   = wp_check_filetype( basename( $_FILES['company_option_pdf']['name'] ) );
            $uploaded_type   = $arr_file_type['type'];

            if ( in_array( $uploaded_type, $supported_types ) ) {
                $upload = wp_upload_bits( $_FILES['company_option_pdf']['name'], null, file_get_contents( $_FILES['company_option_pdf']['tmp_name'] ) );
                if ( isset( $upload['error'] ) && $upload['error'] != 0 ) {
                    wp_die( 'There was an error uploading your file. The error is: ' . $upload['error'] );
                } else {
                    update_post_meta( $id, 'company_option_pdf', $upload );
                }
            } else {
                wp_die( "The file type that you've uploaded is not a PDF." );
            }
        }

        if ( isset( $_POST['company_option_amount'] ) ) {
            $amount = ( isset( $_POST['company_option_amount'] ) ? sanitize_text_field( $_POST['company_option_amount'] ) : '' );
            update_post_meta( $id, 'company_option_amount', $amount );
        }

    }

    public function update_edit_form()
    {
        echo ' enctype="multipart/form-data"';
    }

    /**
     *
     * Custom Post Type for selecting companies
     *
     * End.
     */
}
