<?php

namespace WZ\ChildFree\Shortcodes;

use WZ\ChildFree\Integrations\Stripe;

class UserStripe
{
    public function __invoke()
    {

        $stripe_integration = new Stripe();

        $user_id = get_current_user_id();
        $has_stripe = $stripe_integration->has_account($user_id);
        $login_link = $stripe_integration->create_login_link($user_id);
        $onboarding_link = $stripe_integration->create_onboarding_link($user_id);
        $transfers = $stripe_integration->get_transfers($user_id);

        $getAccId = $stripe_integration->get_account_id($user_id);
        $accObj = $stripe_integration->get_account($user_id);

        $bname = $accObj->name;
        $bemail = $accObj->support_email;
        $bphone = $accObj->support_phone;
        $baddress = $accObj->support_address;
        // $getCreateAcc = $stripe_integration->get_or_create_account($user_id);

        echo '
            <script>console.log(' . json_encode($accObj) . ')</script>
            <script>console.log(' . json_encode($bphone) . ')</script>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const createStripeAccountButton = document.getElementById("createStripeAccountButton");
                    createStripeAccountButton.style.display = ' . ($has_stripe ? '"none"' : '"block"') . ';

                    createStripeAccountButton.addEventListener("click", function() {
                        if (!confirm("Create Stripe account?")) {
                            return;
                        }
                        var xhr = new XMLHttpRequest();
                        xhr.open("POST", "", true);
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        xhr.onload = function() {
                            if (xhr.status >= 200 && xhr.status < 300) {
                                document.getElementById("createStripeAccountForm").innerHTML = "<p>Stripe account created successfully!</p>";
                            } else {
                                console.error("Failed to create Stripe account:", xhr.responseText);
                            }
                        };
                        xhr.send("create_stripe_account=1");
                    });
                });
            </script>

            <span>User id: '. $user_id .'</span>
            <div class="user-stripe-login">
                 ' . ($has_stripe ? '<span style="color:green;">Stripe Active</span>' : '<p style="color: red;">User does not have a Stripe account</p>') . '
                 ' . ($login_link ? '<a href="' . esc_url($login_link) . '">Enter Stripe</a>' : '<p>User does not have a Stripe account for login.</p>') . '
                 ' . ($getAcc ? '<span>' . $getAcc->id . '</span>' : 'null') . '
             </div>   
             <ul>
                 <li>'. $getAccId .'</li>
             </ul> 
             <a href="' . esc_url($onboarding_link) . '">Onboarding link</a>
             <h3>Transfers:</h3>
             
             <form id="createStripeAccountForm" method="post">
                 <input type="hidden" name="create_stripe_account" value="">
                 <button id="createStripeAccountButton" type="button">Create Stripe Account</button>
             </form>
             <ul>
             ';
             if (isset($_POST['create_stripe_account']) && !$has_stripe) {
                 $stripe_integration->create_account($user_id);
                 echo '<p>Stripe account created successfully!</p>';
             }
             foreach ($transfers as $transfer) {
                 echo '<li>Transfer ID: ' . $transfer->id . ', Amount: ' . $transfer->amount / 100 . ' USD</li>';
             }
             echo '</ul>';
    }
}

$user_stripe = new UserStripe();
