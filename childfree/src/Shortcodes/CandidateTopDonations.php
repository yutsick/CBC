<?php

namespace WZ\ChildFree\Shortcodes;

use WZ\ChildFree\Models\Donation;
use WZ\ChildFree\Models\ExpansionDonation;
use WZ\ChildFree\Models\GeneralDonation;
use WZ\ChildFree\Models\LocationDonation;

class CandidateTopDonations
{
    public function __invoke()
    {
        $top_donations = $this->getTopDonations();

        return $top_donations;
    }

    private function getTopDonations()
    {

        ob_start();  // Start output buffering

        // All Donations -> Top Donations (limited to show 5 top donations)
        $top_donations = Donation::get_top(get_the_ID(), 5);

        ?>

        <table id="candidate-top-donations" class="candidate-donation-table hidden">
            <tbody>
            <?php foreach ($top_donations as $donation) : ?>
                <tr>
                    <td class="donation-name">
                        <?php echo $donation->name; ?>
                    </td>
                    <td class="donation-amount">
                        <?php
                        $order_id = $donation->order_id;
                        $order = wc_get_order($order_id);
                        // get order items list
                        $items = $order->get_items();
                        //                        echo wc_price($order->get_total());
                        foreach ( $items as $item ) {
                            $product_id = $item->get_product_id();
                            // add total of all order items whose product id is not ExpansionDonation, GeneralDonation, and LocalDonation
                            if ( $product_id !== GeneralDonation::PRODUCT_ID
                                && $product_id !== ExpansionDonation::PRODUCT_ID
                                && $product_id !== LocationDonation::PRODUCT_ID ) {
                                $items_amount_total = $item->get_total();
                                $referral_amount = get_post_meta($order_id, '_refering_amount', true);
                                $discount_total = $order->get_total_discount();

                                // add voucher discount to order total, for displaying full amount received by candidate
                                if ($discount_total > 0) {
                                    $order_total = (int)$discount_total + (int)$items_amount_total;
                                }
                                else {
                                    $order_total = $items_amount_total;
                                }
                                echo wc_price($order_total);
                            }
                        }
                        ?>
                    </td>
                    <td class="donation-date">
                        <?php echo human_time_diff($donation->date->getTimestamp(), time()); ?> ago
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php

        $topDonations = ob_get_clean();  // Get the buffered output and clean the buffer
        return $topDonations;
    }

}