<?php

namespace FenaCommerceGateway;

class AdminPortalOptions
{

    public static function get()
    {
        $fields = array(
            'enabled' => array(
                'title' => 'Enable/Disable',
                'type' => 'checkbox',
                'label' => 'Enable Fena Gateway',
                'default' => 'yes'
            ),
            'terminal_id' => array(
                'title' => 'Integration ID',
                'type' => 'text',
                'description' => 'Enter the terminal ID Here.',
                'default' => '',
                'desc_tip' => true,
            ),
            'terminal_secret' => array(
                'title' => 'Integration Secret',
                'type' => 'text',
                'description' => 'Enter the terminal Secret Here',
                'default' => '',
                'desc_tip' => true,
            ),
            'title' => array(
                'title' => 'Title',
                'type' => 'text',
                'description' => 'This controls the title which the user sees during checkout.',
                'default' => 'Pay by bank',
                'desc_tip' => true,
            ),
            'description' => array(
                'title' => 'Description',
                'type' => 'text',
                'desc_tip' => true,
                'description' => 'This controls the description which the user sees during checkout.',
                'default' => 'Pay instantly via online bank transfer - Supports most of the U.K banks',
            ),



            'dropdown' => array(
                'title' => 'Select Bank',
                'type' => 'select',
                'options' => array(
                    'option1' => '',


                ),
                'default' => 'option1',
                'desc_tip' => true,

            ),
            'selected_bank' => array(
                'title' => 'Selected Bank',
                'type' => 'text',
                'description' => 'The selected bank.',
                'default' => 'Default', // Set the default bank name here
                'desc_tip' => true,
                'custom_attributes' => array(
                    'readonly' => 'readonly', // Add the "readonly" attribute
                ),
            ),
            'selected_bankId' => array(
                'title' => 'Selected Bank ID',
                'type' => 'text',
                'description' => 'The selected bank.',
                'default' => '', // Set the default bank name here
                'desc_tip' => true,
                'custom_attributes' => array(
                    'readonly' => 'readonly', // Add the "readonly" attribute
                ),
            ),

            'send_email' => array(
                'title' => 'Enable/Disable',
                'type' => 'checkbox',
                'label' => 'Fena Gateway to send a payment request email to the customer',
                'default' => 'yes'
            ),


        );

        return $fields;
    }

    public static function validate($terminal_secret, $terminal_id)
    {
        if (!$terminal_secret) {
            \WC_Admin_Settings::add_error('Invalid Terminal Secret Given');
            return false;
        }

        if (!$terminal_id) {
            \WC_Admin_Settings::add_error('Invalid Terminal ID Given');
            return false;
        }

        return true;
    }
}

