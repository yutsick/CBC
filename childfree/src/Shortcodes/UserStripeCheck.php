<?php

namespace WZ\ChildFree\Shortcodes;

use WZ\ChildFree\Integrations\Stripe;

class UserStripeCheck
{
    public function __invoke()
    {
        ob_start(); 

        $stripe_integration = new Stripe();        
        $user_id = get_current_user_id();
        $has_stripe = $stripe_integration->has_account($user_id);
        // onboarding_link - check if have stripe account and give link
        $onboarding_link = $stripe_integration->create_stripe_onboarding_link($user_id);
        $getAccId = $stripe_integration->get_account_id($user_id);

        // createUpdate_link - auto create stripe acc and link it
        // $createUpdate_link = $stripe_integration->create_onboarding_link($user_id);

        $acc = $stripe_integration->get_account($user_id);
        // $del = $stripe_integration->delete_account($user_id);

        echo '
            <div class="flex">
                ' . ($has_stripe ? 
                    '
                    <div class="flex flex-col gap-4 items-start">
                        <div class="flex flex-col gap-2 justify-start">
                            <span class="text-success font-semibold text-2xl opacity-80">Active</span>
                            <span class="font-semibold text-textColor text-base">ID: '. $getAccId .'</span>
                        </div>
                    </div>
                    ' :               
                    '
                    <div class="flex flex-col gap-3 md:flex-row items-start md:items-center">
                        <a href="https://dashboard.stripe.com/register" target="_blank" class="' . ($has_stripe ? 'hidden' : '') . '">
                            <button class="btn bg-white border-primary text-primary flex items-center gap-2 justify-center hover:text-primary transition-all hover:scale-105 hover:bg-transparent">
                                <img src="/wp-content/uploads/2023/11/favicon-ref.png" class="w-6 h-6" />
                                Create Stripe Account
                            </button>
                        </a>
                        <a ' . ($has_stripe ? 'href="' . esc_url($onboarding_link) . '"' : '') . '>
                            <button ' . (!$has_stripe ? 'disabled="disabled"' : '') . ' class="btn bg-white border-primary text-primary flex items-center gap-2 justify-center hover:text-primary transition-all hover:scale-105">
                                <img src="/wp-content/uploads/2023/11/favicon-ref.png" class="w-6 h-6 ' . (!$has_stripe ? 'grayscale opacity-50' : '') . '" />
                                Connect Stripe                    
                            </button>
                        </a>
                    </div>
                    '
                ) . '
            </div>
        ';

        return ob_get_clean();
    }

}