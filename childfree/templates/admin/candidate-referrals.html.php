<?php

?>
<div id="candidate_referrals" class="panel woocommerce_options_panel padded">

    <?php if ( $candidate->get_stripe_account() === false ) : ?>
        <span class="wp-ui-notification">Candidate has not created a Stripe account yet.</span>
    <?php endif; ?>

    <?php if ( ! empty( $referrals ) ) : ?>
        <table class="widefat">
            <thead>
            <tr>
                <th>Name</th>
                <th>Sign Up Date</th>
                <th>Paid Date</th>
                <th class="action-links">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ( $referrals as $referral ) : ?>
                <tr>
                    <td><?php echo $referral->get_name(); ?></td>
                    <td><?php echo $referral->get_date_created(); ?></td>
                    <td><?php echo $referral->get_date_paid() ?? 'Pending'; ?></td>
                    <td class="action-links">
                        <?php if (! $referral->get_date_paid()) : ?>
                            <?php if ( $candidate->get_stripe_account() !== false ) : ?>
                                <a href="<?php echo $referral->get_action_link( 'direct', $candidate->get_id() ); ?>" class="button button-primary">Pay Directly</a>
                            <?php endif; ?>

                            <a href="<?php echo $referral->get_action_link( 'goal', $candidate->get_id() ); ?>" class="button button-primary">Apply To Goal</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>

        <p>No referrals found</p>

    <?php endif; ?>

</div>
