<?php
namespace WZ\ChildFree\Shortcodes;

use WZ\ChildFree\Models\Donation;
class DashboardCandidateRecentDonationsView
{
    public function __invoke($atts = [])
    {
        extract(shortcode_atts([
            'user_id' => get_the_ID(), // setting default value to current user id
        ], $atts));

        $recent_donations = $this->getRecentDonations($user_id);

        return $recent_donations;
    }

    private function getRecentDonations($user_id)
    {

        ob_start();

        $recent_donations = Donation::get($user_id);

        $index = 0;

        ?>
        <div id="candidate-recent-donations">
            <?php foreach ($recent_donations as $donation) : 
                $order_id = $donation->order_id;
                $order = wc_get_order($order_id);
                $order_total = $order->get_total();
                $referral_amount = get_post_meta($order_id, '_refering_amount', true);
                $discount_total = $order->get_total_discount();

                if ($discount_total > 0) {
                    $order_total = (int)$discount_total + $order_total;
                }

                if (!empty($referral_amount)) {
                    // $order_total = $order_total - $referral_amount;
                }

                $timestamp = $donation->date->getTimestamp();
                $formatted_date = date('j F, g:i A', $timestamp);
            ?>

            <div class="donationItem flex flex-col sm:flex-row items-center w-full justify-between pb-6 border-b border-gray-300">
                <div class="flex gap-4"> 
                    <?php echo !isset($order_image) ? '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    ' :  esc_html($order_image) ?>
                    <div class="flex flex-col gap-1">
                        <span class="text-primary text-base font-normal"><?php echo !isset($donation->name) || empty(trim($donation->name)) ? 'Anonymous' : esc_html($donation->name); ?></span>
                        <span class="flex text-sm font-normal text-info gap-2" style="color: #8497AB !important">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
                                <path d="M13 2.5H11.5V2C11.5 1.86739 11.4473 1.74021 11.3536 1.64645C11.2598 1.55268 11.1326 1.5 11 1.5C10.8674 1.5 10.7402 1.55268 10.6464 1.64645C10.5527 1.74021 10.5 1.86739 10.5 2V2.5H5.5V2C5.5 1.86739 5.44732 1.74021 5.35355 1.64645C5.25979 1.55268 5.13261 1.5 5 1.5C4.86739 1.5 4.74021 1.55268 4.64645 1.64645C4.55268 1.74021 4.5 1.86739 4.5 2V2.5H3C2.73478 2.5 2.48043 2.60536 2.29289 2.79289C2.10536 2.98043 2 3.23478 2 3.5V13.5C2 13.7652 2.10536 14.0196 2.29289 14.2071C2.48043 14.3946 2.73478 14.5 3 14.5H13C13.2652 14.5 13.5196 14.3946 13.7071 14.2071C13.8946 14.0196 14 13.7652 14 13.5V3.5C14 3.23478 13.8946 2.98043 13.7071 2.79289C13.5196 2.60536 13.2652 2.5 13 2.5ZM4.5 3.5V4C4.5 4.13261 4.55268 4.25979 4.64645 4.35355C4.74021 4.44732 4.86739 4.5 5 4.5C5.13261 4.5 5.25979 4.44732 5.35355 4.35355C5.44732 4.25979 5.5 4.13261 5.5 4V3.5H10.5V4C10.5 4.13261 10.5527 4.25979 10.6464 4.35355C10.7402 4.44732 10.8674 4.5 11 4.5C11.1326 4.5 11.2598 4.44732 11.3536 4.35355C11.4473 4.25979 11.5 4.13261 11.5 4V3.5H13V5.5H3V3.5H4.5ZM13 13.5H3V6.5H13V13.5Z" fill="#8497AB"></path>
                            </svg>
                            <?php echo $formatted_date ?>
                        </span>
                    </div>
                </div>
                <div class="flex flex-row sm:flex-col gap-1 items-center">
                    <span class="text-base font-medium text-primary">$<?php echo $order_total ?></span>
                    <!-- <span class="text-success font-normal text-sm">+3.8%</span> -->
                </div>
            </div>             
            <?php endforeach; ?>
        </div>
        <?php

        $recentDonations = ob_get_clean();
        return $recentDonations;
    }
}
