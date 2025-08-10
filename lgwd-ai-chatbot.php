<?php
/**
 * Plugin Name: LGWD AI Chatbot
 * Plugin URI: https://github.com/stuelrick/lgwd-ai-chatbot
 * Description: Professional AI Chatbot with n8n Chat Trigger Integration. Fully customizable, mobile-responsive chatbot for WordPress with advanced lead generation capabilities.
 * Version: 1.2.0
 * Author: Stuart Elrick - Lions Gate Web Design
 * Author URI: https://lionsgatewebdesign.com
 * Text Domain: lgwd-ai-chatbot
 * Domain Path: /languages
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * Network: false
 * 
 * LGWD AI Chatbot is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 * 
 * LGWD AI Chatbot is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with LGWD AI Chatbot. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 * 
 * Developed by Lions Gate Web Design - Vancouver Web Design Agency
 * Specializing in WordPress Development, SEO, and n8n Integrations
 * Contact: https://lionsgatewebdesign.com
 */

// Exit if accessed directly.
if ( ! defined( "ABSPATH" ) ) exit;

// Define plugin constants
define( 'LGWD_AI_CHATBOT_VERSION', '1.2.0' );
define( 'LGWD_AI_CHATBOT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'LGWD_AI_CHATBOT_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

// Add plugin menu
function lgwd_ai_chatbot_menu() {
    add_menu_page(
        "LGWD AI Chatbot Settings",
        "AI Chatbot",
        "manage_options",
        "lgwd-ai-chatbot",
        "lgwd_ai_chatbot_settings_page",
        "dashicons-format-chat"
    );
}
add_action( "admin_menu", "lgwd_ai_chatbot_menu" );

// Create settings page
function lgwd_ai_chatbot_settings_page() {
    ?>
    <div class="wrap">
        <h1>LGWD AI Chatbot Settings</h1>
        <div class="notice notice-success">
            <p><strong>🚀 Professional WordPress Plugin by <a href="https://lionsgatewebdesign.com" target="_blank">Lions Gate Web Design</a></strong></p>
            <p>Need help setting up n8n workflows or custom chatbot development? <a href="https://lionsgatewebdesign.com" target="_blank">Contact us</a> for professional consulting services!</p>
        </div>
        
        <form method="post" action="options.php">
            <?php
            settings_fields( "lgwd-ai-chatbot-settings" );
            do_settings_sections( "lgwd-ai-chatbot" );
            submit_button();
            ?>
        </form>
        
        <div class="notice notice-info">
            <p><strong>About this Plugin:</strong> This plugin uses n8n Chat Trigger nodes for AI integration. Configure your n8n workflow and enter the Chat Trigger URL above.</p>
            <p><strong>Support:</strong> Visit our <a href="https://github.com/stuelrick/lgwd-ai-chatbot" target="_blank">GitHub repository</a> for documentation and support.</p>
        </div>
        
        <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-left: 4px solid #007cba;">
            <h3>🛠️ Need Professional Setup?</h3>
            <p>Lions Gate Web Design offers comprehensive n8n integration and chatbot customization services:</p>
            <ul style="margin-left: 20px;">
                <li>✅ Complete n8n workflow setup</li>
                <li>✅ Custom AI agent configuration</li>
                <li>✅ Lead generation optimization</li>
                <li>✅ Advanced integrations (CRM, Email, SMS)</li>
                <li>✅ Custom branding and styling</li>
            </ul>
            <p><a href="https://lionsgatewebdesign.com" target="_blank" class="button button-primary">Get Professional Setup →</a></p>
        </div>
    </div>
    <?php
}

// Register settings
function lgwd_ai_chatbot_register_settings() {
    register_setting( "lgwd-ai-chatbot-settings", "lgwd_ai_chatbot_chat_url" );
    register_setting( "lgwd-ai-chatbot-settings", "lgwd_ai_chatbot_position" );
    register_setting( "lgwd-ai-chatbot-settings", "lgwd_ai_chatbot_title" );
    register_setting( "lgwd-ai-chatbot-settings", "lgwd_ai_chatbot_primary_color" );
    register_setting( "lgwd-ai-chatbot-settings", "lgwd_ai_chatbot_secondary_color" );
    register_setting( "lgwd-ai-chatbot-settings", "lgwd_ai_chatbot_site_name" );
    register_setting( "lgwd-ai-chatbot-settings", "lgwd_ai_chatbot_greeting_message" );
    register_setting( "lgwd-ai-chatbot-settings", "lgwd_ai_chatbot_placeholder_text" );
    register_setting( "lgwd-ai-chatbot-settings", "lgwd_ai_chatbot_ai_name" );
    register_setting( "lgwd-ai-chatbot-settings", "lgwd_ai_chatbot_fallback_message" );

    add_settings_section( "lgwd_ai_chatbot_section", "Chatbot Configuration", "lgwd_ai_chatbot_section_callback", "lgwd-ai-chatbot" );

    add_settings_field( "chat_url", "n8n Chat Trigger URL", "lgwd_ai_chatbot_chat_url_callback", "lgwd-ai-chatbot", "lgwd_ai_chatbot_section" );
    add_settings_field( "site_name", "Site/Company Name", "lgwd_ai_chatbot_site_name_callback", "lgwd-ai-chatbot", "lgwd_ai_chatbot_section" );
    add_settings_field( "ai_name", "AI Assistant Name", "lgwd_ai_chatbot_ai_name_callback", "lgwd-ai-chatbot", "lgwd_ai_chatbot_section" );
    add_settings_field( "title", "Chat Window Title", "lgwd_ai_chatbot_title_callback", "lgwd-ai-chatbot", "lgwd_ai_chatbot_section" );
    add_settings_field( "greeting_message", "Initial Greeting Message", "lgwd_ai_chatbot_greeting_message_callback", "lgwd-ai-chatbot", "lgwd_ai_chatbot_section" );
    add_settings_field( "placeholder_text", "Input Placeholder Text", "lgwd_ai_chatbot_placeholder_text_callback", "lgwd-ai-chatbot", "lgwd_ai_chatbot_section" );
    add_settings_field( "fallback_message", "Fallback Error Message", "lgwd_ai_chatbot_fallback_message_callback", "lgwd-ai-chatbot", "lgwd_ai_chatbot_section" );
    add_settings_field( "position", "Chat Icon Position", "lgwd_ai_chatbot_position_callback", "lgwd-ai-chatbot", "lgwd_ai_chatbot_section" );
    add_settings_field( "primary_color", "Primary Color", "lgwd_ai_chatbot_primary_color_callback", "lgwd-ai-chatbot", "lgwd_ai_chatbot_section" );
    add_settings_field( "secondary_color", "Secondary Color", "lgwd_ai_chatbot_secondary_color_callback", "lgwd-ai-chatbot", "lgwd_ai_chatbot_section" );
}
add_action( "admin_init", "lgwd_ai_chatbot_register_settings" );

// Section callback
function lgwd_ai_chatbot_section_callback() {
    echo '<p>Configure your AI chatbot settings below. Need help? <a href="https://lionsgatewebdesign.com" target="_blank">Contact Lions Gate Web Design</a> for professional setup services.</p>';
}

// Callback functions for settings fields
function lgwd_ai_chatbot_chat_url_callback() {
    $value = get_option( "lgwd_ai_chatbot_chat_url", "" );
    echo "<input type=\"text\" id=\"lgwd_ai_chatbot_chat_url\" name=\"lgwd_ai_chatbot_chat_url\" value=\"" . esc_attr( $value ) . "\" class=\"regular-text\" placeholder=\"https://your-n8n-instance.com/webhook/uuid/chat\">";
    echo "<p class=\"description\">Enter the Chat URL from your n8n Chat Trigger node (ends with /chat). Need help setting up n8n? <a href=\"https://lionsgatewebdesign.com\" target=\"_blank\">We can help!</a></p>";
}

function lgwd_ai_chatbot_site_name_callback() {
    $value = get_option( "lgwd_ai_chatbot_site_name", get_bloginfo('name') );
    echo "<input type=\"text\" id=\"lgwd_ai_chatbot_site_name\" name=\"lgwd_ai_chatbot_site_name\" value=\"" . esc_attr( $value ) . "\" class=\"regular-text\">";
    echo "<p class=\"description\">Your website/company name (defaults to WordPress site name)</p>";
}

function lgwd_ai_chatbot_ai_name_callback() {
    $value = get_option( "lgwd_ai_chatbot_ai_name", get_bloginfo('name') . " AI" );
    echo "<input type=\"text\" id=\"lgwd_ai_chatbot_ai_name\" name=\"lgwd_ai_chatbot_ai_name\" value=\"" . esc_attr( $value ) . "\" class=\"regular-text\">";
    echo "<p class=\"description\">Name for your AI assistant (e.g., \"Website AI\", \"Support Bot\")</p>";
}

function lgwd_ai_chatbot_title_callback() {
    $value = get_option( "lgwd_ai_chatbot_title", "Chat with Us!" );
    echo "<input type=\"text\" id=\"lgwd_ai_chatbot_title\" name=\"lgwd_ai_chatbot_title\" value=\"" . esc_attr( $value ) . "\" class=\"regular-text\">";
    echo "<p class=\"description\">Title shown in the chat window header</p>";
}

function lgwd_ai_chatbot_greeting_message_callback() {
    $site_name = get_option( "lgwd_ai_chatbot_site_name", get_bloginfo('name') );
    $default_message = "Hello! How can I help you with " . $site_name . " today?";
    $value = get_option( "lgwd_ai_chatbot_greeting_message", $default_message );
    echo "<textarea id=\"lgwd_ai_chatbot_greeting_message\" name=\"lgwd_ai_chatbot_greeting_message\" rows=\"3\" class=\"large-text\">" . esc_textarea( $value ) . "</textarea>";
    echo "<p class=\"description\">Initial message shown when chat opens</p>";
}

function lgwd_ai_chatbot_placeholder_text_callback() {
    $value = get_option( "lgwd_ai_chatbot_placeholder_text", "Ask a question..." );
    echo "<input type=\"text\" id=\"lgwd_ai_chatbot_placeholder_text\" name=\"lgwd_ai_chatbot_placeholder_text\" value=\"" . esc_attr( $value ) . "\" class=\"regular-text\">";
    echo "<p class=\"description\">Placeholder text in the message input field</p>";
}

function lgwd_ai_chatbot_fallback_message_callback() {
    $value = get_option( "lgwd_ai_chatbot_fallback_message", "Sorry, I could not understand that. Please try rephrasing your question." );
    echo "<textarea id=\"lgwd_ai_chatbot_fallback_message\" name=\"lgwd_ai_chatbot_fallback_message\" rows=\"2\" class=\"large-text\">" . esc_textarea( $value ) . "</textarea>";
    echo "<p class=\"description\">Message shown when AI cannot provide a response</p>";
}

function lgwd_ai_chatbot_position_callback() {
    $value = get_option( "lgwd_ai_chatbot_position", "bottom-right" );
    echo "<select id=\"lgwd_ai_chatbot_position\" name=\"lgwd_ai_chatbot_position\">
            <option value=\"bottom-right\"" . selected( $value, "bottom-right", false ) . ">Bottom Right</option>
            <option value=\"bottom-left\"" . selected( $value, "bottom-left", false ) . ">Bottom Left</option>
            <option value=\"top-right\"" . selected( $value, "top-right", false ) . ">Top Right</option>
            <option value=\"top-left\"" . selected( $value, "top-left", false ) . ">Top Left</option>
          </select>";
}

function lgwd_ai_chatbot_primary_color_callback() {
    $value = get_option( "lgwd_ai_chatbot_primary_color", "#007cba" );
    echo "<input type=\"color\" id=\"lgwd_ai_chatbot_primary_color\" name=\"lgwd_ai_chatbot_primary_color\" value=\"" . esc_attr( $value ) . "\">";
}

function lgwd_ai_chatbot_secondary_color_callback() {
    $value = get_option( "lgwd_ai_chatbot_secondary_color", "#005177" );
    echo "<input type=\"color\" id=\"lgwd_ai_chatbot_secondary_color\" name=\"lgwd_ai_chatbot_secondary_color\" value=\"" . esc_attr( $value ) . "\">";
}

// Enqueue chat widget script and styles
function lgwd_ai_chatbot_enqueue_scripts() {
    if ( is_admin() ) return; // Do not load on admin pages
    
    $chat_url = get_option( "lgwd_ai_chatbot_chat_url", "" );
    if ( empty( $chat_url ) ) return; // Do not load if no chat URL
    
    $position = get_option( "lgwd_ai_chatbot_position", "bottom-right" );
    $title = get_option( "lgwd_ai_chatbot_title", "Chat with Us!" );
    $primary_color = get_option( "lgwd_ai_chatbot_primary_color", "#007cba" );
    $secondary_color = get_option( "lgwd_ai_chatbot_secondary_color", "#005177" );
    $site_name = get_option( "lgwd_ai_chatbot_site_name", get_bloginfo('name') );
    $ai_name = get_option( "lgwd_ai_chatbot_ai_name", get_bloginfo('name') . " AI" );
    $greeting_message = get_option( "lgwd_ai_chatbot_greeting_message", "Hello! How can I help you with " . $site_name . " today?" );
    $placeholder_text = get_option( "lgwd_ai_chatbot_placeholder_text", "Ask a question..." );
    $fallback_message = get_option( "lgwd_ai_chatbot_fallback_message", "Sorry, I could not understand that. Please try rephrasing your question." );

    wp_enqueue_script( "lgwd-ai-chatbot", LGWD_AI_CHATBOT_PLUGIN_URL . "chatbot.js", array(), LGWD_AI_CHATBOT_VERSION, true );
    
    wp_localize_script( "lgwd-ai-chatbot", "LGWDChatbotConfig", array(
        "chatUrl" => $chat_url,
        "position" => $position,
        "title" => $title,
        "primaryColor" => $primary_color,
        "secondaryColor" => $secondary_color,
        "siteName" => $site_name,
        "aiName" => $ai_name,
        "greetingMessage" => $greeting_message,
        "placeholderText" => $placeholder_text,
        "fallbackMessage" => $fallback_message,
    ) );
}
add_action( "wp_enqueue_scripts", "lgwd_ai_chatbot_enqueue_scripts", 20 );

// Add settings link to plugin page
function lgwd_ai_chatbot_settings_link( $links ) {
    $settings_link = '<a href="admin.php?page=lgwd-ai-chatbot">Settings</a>';
    $support_link = '<a href="https://github.com/stuelrick/lgwd-ai-chatbot" target="_blank">Support</a>';
    $pro_link = '<a href="https://lionsgatewebdesign.com" target="_blank" style="color: #e26f56; font-weight: bold;">Professional Setup</a>';
    array_unshift( $links, $settings_link, $support_link, $pro_link );
    return $links;
}
add_filter( "plugin_action_links_" . plugin_basename( __FILE__ ), "lgwd_ai_chatbot_settings_link" );

// Add admin footer text
function lgwd_ai_chatbot_admin_footer_text( $footer_text ) {
    $screen = get_current_screen();
    if ( $screen->id === 'toplevel_page_lgwd-ai-chatbot' ) {
        $footer_text = 'Thank you for using <strong>LGWD AI Chatbot</strong> by <a href="https://lionsgatewebdesign.com" target="_blank">Lions Gate Web Design</a> | <a href="https://github.com/stuelrick/lgwd-ai-chatbot" target="_blank">GitHub Repository</a>';
    }
    return $footer_text;
}
add_filter( 'admin_footer_text', 'lgwd_ai_chatbot_admin_footer_text' );
