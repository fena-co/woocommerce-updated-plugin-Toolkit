<?php

namespace FenaCommerceGateway;

use Fena\PaymentSDK\Connection;
use Fena\PaymentSDK\DeliveryAddress;
use Fena\PaymentSDK\Error;
use Fena\PaymentSDK\Helper\NumberFormatter;
use Fena\PaymentSDK\Item;
use Fena\PaymentSDK\Payment;
use Fena\PaymentSDK\User;

class PaymentProcess
{
    public static function process($order_id, $terminal_id, $terminal_secret, $bankAccount, $send_email)
    {

        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $send_mail = true;
        $order = new \WC_Order($order_id);
        $previousUrl = $order->get_meta('_fena_payment_url');

         if (!empty($previousUrl)) {
             return array(
                 'result' => 'success',
                 'redirect' => $previousUrl
             );
         }

       // Check if the wc_seq_order_number_pro plugin is active
if (function_exists('wc_seq_order_number_pro')) {
    $order_number = wc_seq_order_number_pro()->find_order_by_order_number($order_id);
} elseif (method_exists($order, 'get_order_number')) {
    // If the plugin is not active, check if the order object has a get_order_number method
    $order_number = $order->get_order_number();
    // Use $order_number for further processing
} else {
    // If neither the plugin nor the method exists, fall back to using $order_id
    $order_number = $order_id;
}

if($send_email){
    $send_mail = true;


}else{
    $send_mail = false;
}



        $connection = Connection::createConnection(
            $terminal_id,
            $terminal_secret
        );

        if ($connection instanceof Error) {
            return array(
                'result' => 'failure',
                'messages' => 'Something went wrong. Please contact support.'
            );
        }

        if ($order->get_total() < 0.01) {
            return array(
                'result' => 'failure',
                'messages' => 'Total amount should be greater than 0.50.'
            );
        }

        $checkoutUrl = $order->get_checkout_order_received_url();

        error_log($checkoutUrl);

        $payment = Payment::createPayment(
            $connection,
            $order->get_total(),
            $order_number,
            $bankAccount,
            $checkoutUrl,
        );

        if ($payment instanceof Error) {
            return array(
                'result' => 'failure',
                'messages' => 'Something went wrong. Please contact support.'
            );
        }

        $user = User::createUser(
            $order->get_billing_email(),
            $order->get_billing_first_name(),
            $order->get_billing_last_name(),
            $order->get_billing_phone()
        );

        if ($user instanceof Error) {
            return array(
                'result' => 'failure',
                'messages' => 'Something went wrong. Please contact support.'
            );
        }

        // payment object
        $payment->setUser($user);

        // add items in the cart
        foreach ($order->get_items() as $item) {
            $item = Item::createItem(
                $item->get_name(),
                $item->get_quantity()
            );
            if ($item instanceof Item) {
                $payment->addItem($item);
            }
        }

        // add delivery address
        if ($order->get_shipping_address_1() != '') {
            $country = $order->get_billing_country();

            if ($country == 'GB') {
                $country = 'UK';
            }

            if (strlen($country) != '2') {
                $country = 'UK';
            }

            $deliveryAddress = DeliveryAddress::createDeliveryAddress(
                $order->get_shipping_address_1(),
                $order->get_shipping_address_2(),
                $order->get_shipping_postcode(),
                $order->get_shipping_city(),
                $country
            );
        } else {
            $country = $order->get_billing_country();

            if ($country == 'GB') {
                $country = 'UK';
            }

            if (strlen($country) != '2') {
                $country = 'UK';
            }

            $deliveryAddress = DeliveryAddress::createDeliveryAddress(
                $order->get_billing_address_1(),
                $order->get_billing_address_2(),
                $order->get_billing_postcode(),
                $order->get_billing_city(),
                $country
            );
        }

        if ($deliveryAddress instanceof DeliveryAddress) {
            $payment->setDeliveryAddress($deliveryAddress);
        }

        $url = $payment->process();


        $order->update_status('awaiting_payment', 'Awaiting payment');

        $hashed = $payment->getHashedId();

        $order->add_meta_data('_fena_payment_hashed_id', $hashed);
        $order->add_meta_data('_fena_payment_url', $url);
        $order->save_meta_data();

        return array(
            'result' => 'success',
            'redirect' => $url
        );
    }

}
