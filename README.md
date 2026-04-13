# CDMN School Handbook

A WordPress plugin designed to enable Student and Staff Handbook functionality for schools within the Catholic Diocese of Maitland-Newcastle (CDMN). This plugin is specifically tailored for use with the **cso-master** WordPress theme.

## Features

- **Automated Page Provisioning**: Automatically creates "Student Handbook" and "Staff Handbook" pages (as drafts) upon plugin activation if they do not already exist.
- **Custom Page Templates**: Provides a specialized `Handbook` template (`templates/handbook.php`) for rendering handbook content.
- **ACF-Powered Layouts**: Utilizes Advanced Custom Fields and ACF Extended for modular content blocks and flexible layouts.
- **Built-in Assets**: Includes specialized styles and scripts for a responsive, interactive handbook experience, including Slick Carousel integration.

## Dependencies

This plugin requires the following to be installed and active:

1.  **WordPress**: Core CMS (developed and tested for modern WP versions).
2.  **Advanced Custom Fields (ACF)**: Required for data management and custom fields.
3.  **Advanced Custom Fields: Extended (ACFE)**: **Strictly Required.** The plugin uses ACFE for rendering flexible content layouts and specific admin enhancements.
4.  **CSO Master Theme**: The plugin is designed to integrate with the hooks, styles, and template structure of the `cso-master` theme.

## Installation & Setup

1.  Upload the `cdmn-school-handbook` folder to your `/wp-content/plugins/` directory.
2.  Activate the plugin through the 'Plugins' menu in WordPress.
3.  Upon activation, two draft pages ("Student Handbook" and "Staff Handbook") will be created automatically.
4.  Ensure that **Advanced Custom Fields: Extended** is active; otherwise, a notice will appear in the admin dashboard.

## Updater

The plugin features a built-in update mechanism via the `BEECH_Updater` class located in `updater.php`.

-   **Source**: GitHub (`BeechAgency/cdmn-school-handbook`)
-   **Functionality**: It hooks into the standard WordPress update transient to check for new releases on GitHub. When a new version is tagged/released, it will appear as an update in the WordPress dashboard.
-   **Configuration**: The updater is initialized in `school-handbook.php`. For private repositories, an authorization token can be provided using `$updater->authorize('TOKEN')`.

## Version Management & Releases

To push an update to WordPress sites using this plugin, follow these steps:

1.  **Update Plugin Version**: Increment the `Version` string in the plugin header of `school-handbook.php`.
2.  **Commit & Push**: Commit your changes and push them to the GitHub repository.
3.  **Create GitHub Release**:
    -   Go to the "Releases" section of the GitHub repository.
    -   Draft a new release.
    -   **Important**: The **Tag version** must match the version number exactly (e.g., `1.1`, `2.0`).
    -   Publish the release.
4.  **WordPress Detection**: The `BEECH_Updater` compares the local version against the latest GitHub tag. If the tag is higher, WordPress will display an update notification in the 'Plugins' dashboard.

## Development

-   **ACF JSON**: Custom field configurations are synced via the `acf/acf-json` directory.
-   **Flexible Layouts**: Layout templates are located in `acf-layouts/` and are automatically loaded via the `acfe/flexible/render/template` filter.
-   **Assets**: CSS and JS for the handbook are located in the `assets/` directory and are conditionally enqueued only on pages using the handbook template.
