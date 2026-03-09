<?php

class BEECH_Updater {
    private $file;    
    private $plugin;    
    private $basename;    
    private $active;    
    private $username;    
    private $repository;    
    private $authorize_token;
    private $github_response;

    public function __construct( $file ) {
        $this->file = $file;
        add_action( 'admin_init', array( $this, 'set_plugin_properties' ) );

        return $this;
    }

    public function set_plugin_properties() {
        $this->plugin	= get_plugin_data( $this->file );
        $this->basename = plugin_basename( $this->file );
        $this->active	= is_plugin_active( $this->basename );
    }

    public function set_username( $username ) {
        $this->username = $username;
    }

    public function set_repository( $repository ) {
        $this->repository = $repository;
    }

    public function authorize( $token ) {
        $this->authorize_token = $token;
    }

    private function get_repository_info() {
        // Caches the response once fetched so we don't hit GitHub every time.
        if ( is_null( $this->github_response ) ) {
            $request_uri = sprintf( 'https://api.github.com/repos/%s/%s/releases', $this->username, $this->repository );

            $args = [];
            if ( $this->authorize_token ) { // Add auth header if available.
                $args['headers']['Authorization'] = "token {$this->authorize_token}";
            }

            $remote = wp_remote_get( $request_uri, $args );
            if ( is_wp_error( $remote ) ) {
                // store empty array so we don't keep retrying on failure
                $this->github_response = [];
                return $this->github_response;
            }

            $body = wp_remote_retrieve_body( $remote );
            $response = json_decode( $body, true );

            if ( is_array( $response ) && ! empty( $response ) ) {
                $response = current( $response );
            }

            // Ensure we always have an array to avoid warnings downstream.
            $this->github_response = is_array( $response ) ? $response : [];
        }

        return $this->github_response;
    }

    public function initialize() {
        add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'modify_transient' ), 10, 1 );
        add_filter( 'plugins_api', array( $this, 'plugin_popup' ), 10, 3);
        add_filter( 'upgrader_post_install', array( $this, 'after_install' ), 10, 3 );
        
        // Add Authorization Token to download_package
        add_filter( 'upgrader_pre_download',
            function() {
                add_filter( 'http_request_args', [ $this, 'download_package' ], 15, 2 );
                return false; // upgrader_pre_download filter default return value.
            }
        );
    }

    public function modify_transient( $transient ) {

        if( property_exists( $transient, 'checked') ) { // Check if transient has a checked property

            if( $checked = $transient->checked ) { // Did Wordpress check for updates?
                $this->get_repository_info(); // Get the repo info

                $info = $this->github_response ?: $this->get_repository_info();

                if ( empty( $info ) || ! isset( $info['tag_name'] ) ) {
                    // no valid release data available so bail out quietly
                    return $transient;
                }

                $current_version = isset( $checked[ $this->basename ] ) ? $checked[ $this->basename ] : '';
                $out_of_date = $current_version && version_compare( $info['tag_name'], $current_version, 'gt' );

                if ( $out_of_date ) {

                    $new_files = $info['zipball_url'];

                    // If there are release assets attached, use those instead.
                    if ( isset( $info['assets'] ) && is_countable( $info['assets'] ) && count( $info['assets'] ) > 0 ) {
                        // assets are decoded as arrays
                        $new_files = $info['assets'][0]['browser_download_url'];
                    }

                    $slug = current( explode('/', $this->basename ) ); // Create valid slug

                    $plugin = array( // setup our plugin info
                        'url' => $this->plugin["PluginURI"],
                        'slug' => $slug,
                        'package' => $new_files,
                        'new_version' => $this->github_response['tag_name']
                    );

                    $transient->response[$this->basename] = (object) $plugin; // Return it in response
                }
            }
        }

        return $transient; // Return filtered transient
    }

    public function plugin_popup( $result, $action, $args ) {

        if( ! empty( $args->slug ) ) { // If there is a slug
            
            if( $args->slug == current( explode( '/' , $this->basename ) ) ) { // And it's our slug

                $this->get_repository_info(); // Get our repo info

                // Set it to an array
                $plugin = array(
                    'name'				=> $this->plugin["Name"],
                    'slug'				=> $this->basename,
                    'requires'					=> '5.1',
                    'tested'						=> '6.0.2',
                    'rating'						=> '100.0',
                    'num_ratings'				=> '5',
                    'downloaded'				=> '5',
                    'added'							=> '2020-07-08',
                    'version'			=> $this->github_response['tag_name'],
                    'author'			=> $this->plugin["AuthorName"],
                    'author_profile'	=> $this->plugin["AuthorURI"],
                    'last_updated'		=> $this->github_response['published_at'],
                    'homepage'			=> $this->plugin["PluginURI"],
                    'short_description' => $this->plugin["Description"],
                    'sections'			=> array(
                        'Description'	=> $this->plugin["Description"],
                        'Updates'		=> $this->github_response['body'],
                    ),
                    'download_link'		=> $this->github_response['zipball_url']
                );

                return (object) $plugin; // Return the data
            }

        }
        return $result; // Otherwise return default
    }

    public function download_package( $args, $url ) {
        if ( null !== $args['filename'] ) {
            if( $this->authorize_token ) { 
                $args = array_merge( $args, array( "headers" => array( "Authorization" => "token {$this->authorize_token}" ) ) );
            }
        }
        
        remove_filter( 'http_request_args', [ $this, 'download_package' ] );

        return $args;
    }

    public function after_install( $response, $hook_extra, $result ) {
        global $wp_filesystem; // Get global FS object

        $install_directory = plugin_dir_path( $this->file ); // Our plugin directory
        $wp_filesystem->move( $result['destination'], $install_directory ); // Move files to the plugin dir
        $result['destination'] = $install_directory; // Set the destination for the rest of the stack

        if ( $this->active ) { // If it was active
            activate_plugin( $this->basename ); // Reactivate
        }

        return $result;
    }
}
