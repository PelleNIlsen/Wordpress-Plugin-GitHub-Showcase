<?php
/*
GitHub Showcase

@package            PluginPackage
@author             Pelle Nilsen
@copyright          2023 Pelle Nilsen
@license            GPL-2.0-or-later

@wordpress-plugin
Plugin Name:        GitHub Showcase
Plugin URI:         http://
Description:        Showcase your GitHub profile on your website. Display your repositories, followers, and more.
Version:            Developing 0.0.1
Requires at least:  5.2
Requires PHP:       7.2
Author:             Pelle Nilsen
Author URI:         http://
License:            GPLv2 or later
License URI:        http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain:        github-showcase
*/

function github_showcase_register_settings() {
    register_setting('github_showcase_options', 'github_username');
    register_setting('github_showcase_options', 'show_name');
    register_setting('github_showcase_options', 'show_username');
    register_setting('github_showcase_options', 'show_avatar');
    register_setting('github_showcase_options', 'show_followers');
    register_setting('github_showcase_options', 'repositories');
    register_setting('github_showcase_options', 'show_repo_name');
    register_setting('github_showcase_options', 'show_repo_description');
    register_setting('github_showcase_options', 'show_repo_creation_date');
    register_setting('github_showcase_options', 'show_repo_update_date');
    register_setting('github_showcase_options', 'show_repo_language');
    register_setting('github_showcase_options', 'show_repo_forks_count');
    register_setting('github_showcase_options', 'show_repo_stars');
}
add_action('admin_init', 'github_showcase_register_settings');


function github_showcase_admin_page() {
    ?>
    <div class="wrap">
        <h1>GitHub Showcase Settings</h1>
        <form action="options.php" method="post">
            <?php
                settings_fields('github_showcase_options');
                do_settings_sections('github_showcase');
            ?>
            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <th scope="row"><label for="github_username">GitHub Username</label></th>
                        <td>
                            <input type="text" name="github_username" placeholder="PelleNIlsen" id="github_username" value="<?php echo get_option('github_username') ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="show_name">Show Name</label></th>
                        <td>
                            <input type="checkbox" name="show_name" id="show_name" value="1" <?php checked(get_option('show_name'), 1); ?>>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="show_username">Show Username</label></th>
                        <td>
                            <input type="checkbox" name="show_username" id="show_username" value="1" <?php checked(get_option('show_username'), 1); ?>>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="show_avatar">Show Avatar</label></th>
                        <td>
                            <input type="checkbox" name="show_avatar" id="show_avatar" value="1" <?php checked(get_option('show_avatar'), 1); ?>>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="show_followers">Show Followers</label></th>
                        <td>
                            <input type="checkbox" name="show_followers" id="show_followers" value="1" <?php checked(get_option('show_followers'), 1); ?>>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="repositories">Repositories</label></th>
                        <td>
                            <input type="number" name="repositories" placeholder="3" id="repositories" value="<?php echo get_option('repositories') ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="show_repo_name">Show Repo Name</label></th>
                        <td>
                            <input type="checkbox" name="show_repo_name" id="show_repo_name" value="1" <?php checked(get_option('show_repo_name'), 1); ?>>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="show_repo_description">Show Repo Description</label></th>
                        <td>
                            <input type="checkbox" name="show_repo_description" id="show_repo_description" value="1" <?php checked(get_option('show_repo_description'), 1); ?>>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="show_repo_creation_date">Show Repo Creation Date</label></th>
                        <td>
                            <input type="checkbox" name="show_repo_creation_date" id="show_repo_creation_date" value="1" <?php checked(get_option('show_repo_creation_date'), 1); ?>>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="show_repo_update_date">Show Repo Update Date</label></th>
                        <td>
                            <input type="checkbox" name="show_repo_update_date" id="show_repo_update_date" value="1" <?php checked(get_option('show_repo_update_date'), 1); ?>>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="show_repo_language">Show Repo Language</label></th>
                        <td>
                            <input type="checkbox" name="show_repo_language" id="show_repo_language" value="1" <?php checked(get_option('show_repo_language'), 1); ?>>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="show_repo_forks_count">Show Repo Forks Count</label></th>
                        <td>
                            <input type="checkbox" name="show_repo_forks_count" id="show_repo_forks_count" value="1" <?php checked(get_option('show_repo_forks_count'), 1); ?>>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="show_repo_stars">Show Repo Stars</label></th>
                        <td>
                            <input type="checkbox" name="show_repo_stars" id="show_repo_stars" value="1" <?php checked(get_option('show_repo_stars'), 1); ?>>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function github_showcase_add_admin_page() {
    add_menu_page(
        __('GitHub Showcase', 'github-showcase'),
        __('GitHub Showcase', 'github-showcase'),
        'manage_options',
        'github_showcase',
        'github_showcase_admin_page',
        'dashicons-admin-generic',
        110
    );
}

function getUserInfo($username, $userSettings) {
    $url = "https://api.github.com/users/" . $username;
    $response = wp_remote_get($url);
    $json = wp_remote_retrieve_body($response);
    $data = json_decode($json, true);

    $info = array();
    foreach ($userSettings as $key => $value) {
        array_push($info, $data[$key]);
    }

    return $info;
}

function getReposInfo($username, $reposSettings, $repos) {
    $url = "https://api.github.com/users/" . $username . "/repos";
    $response = wp_remote_get($url);
    $json = wp_remote_retrieve_body($response);
    $data = json_decode($json, true);

    $info = array();
    for ($i = 0; $i < $repos; $i++) {
        ${'array' . $i} = array();
        foreach ($reposSettings as $key => $value) {
            array_push(${'array' . $i}, $data[$i][$key]);
        }
        array_push($info, ${'array' . $i});
    }

    return $info;
}

// function profileCard($data, $settings) {
//     foreach($settings as $key => $value) {
//         echo $data[$key] . "<br>";
//     }

//     return $settings;
// }

add_action('admin_menu', 'github_showcase_add_admin_page');

function github_showcase_shortcode_showcase_repos() {

    $username = get_option('github_username');

    $userSettings = array(
        'name' => get_option('show_name'),
        'login' => get_option('show_username'),
        'avatar_url' => get_option('show_avatar'),
        'followers' => get_option('show_followers'),
        'public_repos' => get_option('repositories'),
    );

    foreach($userSettings as $key => $value) {
        if ($value == 0 || $value == "") {
            unset($userSettings[$key]);
        }
    }

    $reposSettings = array(
        'name' => get_option('show_repo_name'),
        'description' => get_option('show_repo_description'),
        'created_at' => get_option('show_repo_creation_date'),
        'updated_at' => get_option('show_repo_update_date'),
        'language' => get_option('show_repo_language'),
        'forks_count' => get_option('show_repo_forks_count'),
        'stargazers_count' => get_option('show_repo_stars')
    );

    foreach($reposSettings as $key => $value) {
        if ($value == 0 || $value == "") {
            unset($reposSettings[$key]);
        }
    }

    $userInfo = getUserInfo($username, $userSettings);
    $reposInfo = getReposInfo($username, $reposSettings, $userSettings['public_repos']);

    // $test = profileCard($userInfo, $userSettings);

    return var_dump($userInfo);
    // return var_dump($reposInfo);
}

add_shortcode('github_showcase_repos', 'github_showcase_shortcode_showcase_repos');

?>