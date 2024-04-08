<?php
namespace WZ\ChildFree\Shortcodes;

use WZ\ChildFree\Models\Donation;
class DashboardCandidateRecentDonations
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

        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $items_per_page = 10;

        $start_index = ($page - 1) * $items_per_page;
        $end_index = $start_index + $items_per_page;

        $recent_donations = array_slice($recent_donations, $start_index, $items_per_page);

        $index = 0;

        ?>
         <div class="overflow-x-auto">
            <table class="table" id="candidate-recent-donations">
                <!-- head -->
                <thead>
                <tr class="text-xs font-semibold bg-primary bg-opacity-5 text-info border-b border-borderColor h-14">
                    <th>Donor</th>
                    <th>My Donations Amount</th>
                    <th>Donation Date</th>
                    <th>Donation Number</th>
                    <th>Payment Method</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($recent_donations as $donation) : 
                        $order_id = $donation->order_id;
                        $order = wc_get_order($order_id);
                        $order_total = $order->get_total();
                        $referral_amount = get_post_meta($order_id, '_refering_amount', true);
                        $discount_total = $order->get_total_discount();

                        if ($discount_total > 0) {
                            $order_total = (int)$discount_total + $order_total;
                        }
                ?>
                <tr class="border-0 <?php echo ($index++ % 2 === 0) ? 'bg-primary bg-opacity-5' : ''; ?>">
                    <td>
                        <div class="flex items-center space-x-3">
                            <div class="avatar">
                            <div class="mask mask-squircle w-6 h-6">
                                <?php echo !isset($user_image) ? '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
</svg>
' :  esc_html($user_image) ?>
                            </div>
                            </div>
                            <div>
                            <div class="font-normal text-base text-primary underline">
                                <div class="font-normal text-base text-primary underline">
                                    <?php echo !isset($donation->name) || empty(trim($donation->name)) ? 'Anonymous' : esc_html($donation->name); ?>
                                </div>
                            </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="flex items-center text-textColor text-base gap-2">$<?php echo $order_total; ?></div>
                    </td>
                    <td><?php echo esc_html(human_time_diff($donation->date->getTimestamp(), time())) . ' ago'; ?></td>
                    <td>#<?php echo $order_id ?></td>
                    <td>Credit Card / Debit Card</td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="w-full flex justify-center mt-8 gap-3.5 items-center">
            <?php
            
            $total_pages = ceil(count($recent_donations) / $items_per_page);
            
            for ($i = 1; $i <= $total_pages; $i++) {
                $active_class = $i === $page ? 'active' : '';
                echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" class="cursor-pointer">
                    <g clip-path="url(#clip0_1049_13851)">
                    <path d="M15 4.5L7.5 12L15 19.5" stroke="#333333" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M15 4.5L7.5 12L15 19.5" stroke="black" stroke-opacity="0.2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M15 4.5L7.5 12L15 19.5" stroke="black" stroke-opacity="0.2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M15 4.5L7.5 12L15 19.5" stroke="black" stroke-opacity="0.2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>
                    <defs>
                    <clipPath id="clip0_1049_13851">
                        <rect width="24" height="24" fill="white" transform="matrix(0 -1 -1 0 24 24)"/>
                    </clipPath>
                    </defs>
                </svg>';
                echo '<a href="?page=' . $i . '" class="page-link w-10 h-10 border-0 bg-transparent flex items-center justify-center ' . $active_class . '">' . $i . '</a>';
                echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" class="cursor-pointer">
                    <g clip-path="url(#clip0_1049_13883)">
                    <path d="M9 4.5L16.5 12L9 19.5" stroke="#333333" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9 4.5L16.5 12L9 19.5" stroke="black" stroke-opacity="0.2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9 4.5L16.5 12L9 19.5" stroke="black" stroke-opacity="0.2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9 4.5L16.5 12L9 19.5" stroke="black" stroke-opacity="0.2" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>
                    <defs>
                    <clipPath id="clip0_1049_13883">
                        <rect width="24" height="24" fill="white" transform="matrix(0 -1 1 0 0 24)"/>
                    </clipPath>
                    </defs>
                </svg>'; 
            }
            ?>
        </div>
        <?php

        $recentDonations = ob_get_clean();
        return $recentDonations;
    }
}