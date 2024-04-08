<?php
namespace WZ\ChildFree;

use WZ\ChildFree\Models\Candidate;
use WZ\ChildFree\Models\Notification;
use WZ\ChildFree\Models\GeneralDonation;
use WZ\ChildFree\Models\ExpansionDonation;
use WZ\ChildFree\Models\LocationDonation;
use WZ\ChildFree\Models\FundAllCandidates;

use WZ\ChildFree\Actions\UploadFileToGoogleDrive;

use WZ\ChildFree\Actions\Account\FilterPasswordResetEmail;
use WZ\ChildFree\Actions\Account\FilterPasswordResetLink;
use WZ\ChildFree\Actions\Account\SetCheckoutDonorAccountRole;
use WZ\ChildFree\Actions\Account\ReferralLinkContent;
use WZ\ChildFree\Actions\Account\FilterPasswordResetErrorMessage;

use WZ\ChildFree\Actions\Admin\HideAdminProductDataTabs;
use WZ\ChildFree\Actions\Admin\AddCandidateDataTab;
use WZ\ChildFree\Actions\Admin\AddCandidateDataPanel;
use WZ\ChildFree\Actions\Admin\AddCandidateExportColumns;
use WZ\ChildFree\Actions\Admin\AddCandidateExportData;
use WZ\ChildFree\Actions\Admin\HandleBulkActions;

use WZ\ChildFree\Actions\Candidate\FilterProductLabels;
use WZ\ChildFree\Actions\Candidate\FilterProductTypes;
use WZ\ChildFree\Actions\Candidate\HandleAddToCart;
use WZ\ChildFree\Actions\Candidate\HandleAddCartItem;
use WZ\ChildFree\Actions\Candidate\HandleVoucherCoupon;
use WZ\ChildFree\Actions\Candidate\HandleCalculateTotals;
use WZ\ChildFree\Actions\Candidate\AddSingleProductToCart;
use WZ\ChildFree\Actions\Candidate\FilterProductClass;
use WZ\ChildFree\Actions\Candidate\FilterGeneralDonationProducts;
use WZ\ChildFree\Actions\Candidate\AddImagesToProductGallery;
use WZ\ChildFree\Actions\Candidate\CalculateLookupData;
use WZ\ChildFree\Actions\Candidate\CreatePostEditEntry;
use WZ\ChildFree\Actions\Candidate\HandleMiniCartItems;

use WZ\ChildFree\Actions\Physicians\StorePhysicianMetadata;

use WZ\ChildFree\Actions\Donations\ClearCartItems;
use WZ\ChildFree\Actions\Donations\UpdateCartItemPrice;
use WZ\ChildFree\Actions\Donations\RemoveCartItem;
use WZ\ChildFree\Actions\Donations\AutoCompleteDonation;
use WZ\ChildFree\Actions\Donations\HandleSpecificTabOnCheckoutPage;
use WZ\ChildFree\Actions\Donations\HandleExpansionTabOnCheckoutPage;
use WZ\ChildFree\Actions\Donations\HandleLocationTabOnCheckoutPage;
use WZ\ChildFree\Actions\Donations\HandleDonateAnonymously;
use WZ\ChildFree\Actions\Donations\HandleGeneralTabOnCheckoutPage;
use WZ\ChildFree\Actions\Donations\FilterBillingFields;
use WZ\ChildFree\Actions\Donations\FilterCheckoutFields;
use WZ\ChildFree\Actions\Donations\RedirectShopToCandidatesPage;
use WZ\ChildFree\Actions\Donations\HandleAddZipToLocationName;
use WZ\ChildFree\Actions\Donations\HandleFundAllCandidates;
use WZ\ChildFree\Actions\Donations\HandleThankyouPage;
use WZ\ChildFree\Actions\Donations\HandleDonationProductsAccess;
use WZ\ChildFree\Actions\Donations\AddFundAllDonationToCandidates;
use WZ\ChildFree\Actions\Donations\FilterStripeCheckoutDetails;
use WZ\ChildFree\Actions\Donations\FilterPayPalCheckoutDetails;

use WZ\ChildFree\Actions\Referrals\AddCandidateReferralDataPanel;
use WZ\ChildFree\Actions\Referrals\AddReferralToSession;
use WZ\ChildFree\Actions\Referrals\AddReferralToCandidate;

use WZ\ChildFree\Actions\Notifications\RegisterDataStore as RegisterNotificationsDataStore;
use WZ\ChildFree\Actions\Notifications\RegisterPostType as RegisterNotificationPostType;
use WZ\ChildFree\Actions\Notifications\AddCandidateReferralNotification;
use WZ\ChildFree\Actions\Notifications\SendEmailNotification;

use WZ\ChildFree\Actions\Verification\HandleUserRegistered;
use WZ\ChildFree\Actions\Verification\HandleNewsletterConfirmation;

use WZ\ChildFree\Actions\FormidableForms\RemoveRegistrationErrorsOfEmailUsername;

use WZ\ChildFree\Shortcodes\CandidateProgress;
use WZ\ChildFree\Shortcodes\CandidateSeeking;
use WZ\ChildFree\Shortcodes\CandidateLocation;
use WZ\ChildFree\Shortcodes\CandidatesCount;
use WZ\ChildFree\Shortcodes\AllCandidatesRemainingAmount;
use WZ\ChildFree\Shortcodes\CandidateRemainingAmount;
use WZ\ChildFree\Shortcodes\CandidateGoalAmount;
use WZ\ChildFree\Shortcodes\CandidateAmountRaised;
use WZ\ChildFree\Shortcodes\CandidateGender;
use WZ\ChildFree\Shortcodes\CandidateRecentDonations;
use WZ\ChildFree\Shortcodes\DashboardCandidateRecentDonations;
use WZ\ChildFree\Shortcodes\DashboardCandidateRecentDonationsView;
/* Stripe */
use WZ\ChildFree\Shortcodes\UserStripe;
use WZ\ChildFree\Shortcodes\UserStripeCheck;
/* Stripe end */
/* QR advocate */
// use QRGenerator\QRCodeGenerator;
/* end QR */
use WZ\ChildFree\Shortcodes\CandidateGenderCard;
use WZ\ChildFree\Shortcodes\CandidateTopDonations;
use WZ\ChildFree\Shortcodes\ShowReferralId;


class App {
    /**
     * List of actions and their handlers
     *
     * @var array
     */
    protected array $actions = array(
        // admin actions
        AddCandidateDataPanel::class,
        HandleBulkActions::class,

        // candidate actions
        AddSingleProductToCart::class,
        HandleAddToCart::class,
        HandleCalculateTotals::class,
        AddImagesToProductGallery::class,
        CalculateLookupData::class,
        CreatePostEditEntry::class,
        HandleVoucherCoupon::class,
        HandleMiniCartItems::class,

        // donation actions
        FilterGeneralDonationProducts::class,
        ClearCartItems::class,
        UpdateCartItemPrice::class,
        RemoveCartItem::class,
        AutoCompleteDonation::class,
        HandleSpecificTabOnCheckoutPage::class,
        HandleGeneralTabOnCheckoutPage::class,
        HandleExpansionTabOnCheckoutPage::class,
        HandleLocationTabOnCheckoutPage::class,
        HandleDonateAnonymously::class,
        RedirectShopToCandidatesPage::class,
        HandleAddZipToLocationName::class,
        HandleFundAllCandidates::class,
        HandleThankyouPage::class,
        HandleDonationProductsAccess::class,
        AddFundAllDonationToCandidates::class,
        FilterStripeCheckoutDetails::class,
        FilterPayPalCheckoutDetails::class,

        // physician actions
        StorePhysicianMetadata::class,

        // account actions
        SetCheckoutDonorAccountRole::class,
        FilterPasswordResetErrorMessage::class,

        // verification actions
        HandleUserRegistered::class,
        HandleNewsletterConfirmation::class,
        RemoveRegistrationErrorsOfEmailUsername::class,

        // affiliate actions
//        HandleRegisterAffiliate::class,

        // transfer actions
//        SendCandidateTransfer::class,

        // notification actions
        RegisterNotificationPostType::class,
        AddCandidateReferralNotification::class,
        SendEmailNotification::class,

        // referral actions
        AddCandidateReferralDataPanel::class,
        AddReferralToSession::class,
        AddReferralToCandidate::class,
        ReferralLinkContent::class,

        // post actions
//        CalculateAvgReadingTime::class,


        // Custom Newsletter subscription using ajax
//        SubscribeNewsletter::class,
    );

    /**
     * List of filters and their handlers
     *
     * @var array
     */
    protected array $filters = array(
        FilterProductLabels::class,
        FilterProductTypes::class,
        HideAdminProductDataTabs::class,
        AddCandidateDataTab::class,
        AddCandidateExportColumns::class,
        AddCandidateExportData::class,
        FilterProductClass::class,
        HandleAddCartItem::class,

        FilterBillingFields::class,
        FilterCheckoutFields::class,
        FilterPasswordResetEmail::class,
        FilterPasswordResetLink::class,

        RegisterNotificationsDataStore::class,
    );

    /**
     * List of shortcodes and their handlers
     *
     * @var array
     */
    protected array $shortcodes = array(
        'candidate_progress' => CandidateProgress::class,
        'candidate_seeking' => CandidateSeeking::class,
        'candidate_location' => CandidateLocation::class,
        'candidates_count' => CandidatesCount::class,
        'all_candidates_remaining_amount' => AllCandidatesRemainingAmount::class,
        'candidate_remaining_amount' => CandidateRemainingAmount::class,
        'candidate_goal_amount' => CandidateGoalAmount::class,
        'candidate_amount_raised' => CandidateAmountRaised::class,
        'candidate_gender' => CandidateGender::class,
        'candidate_recent_donations' => CandidateRecentDonations::class,
        //Alex-start
        'dashboard_candidate_recent_donations' => DashboardCandidateRecentDonations::class,
        'dashboard_candidate_recent_donations_view' => DashboardCandidateRecentDonationsView::class,
        'candidate_gender_card' => CandidateGenderCard::class,
        'user_stripe_check' => UserStripeCheck::class,
        'user_stripe' => UserStripe::class,
        // 'qrcode_generator' => QRCodeGenerator::class,
        //Alex-end
        'candidate_top_donations' => CandidateTopDonations::class,
        'show_referral_id' => ShowReferralId::class,

        // Newsletter subscription form
//        'newsletter_subscription_form' => NewsletterForm::class,
    );

    /**
     * Instance of app
     *
     * @var App
     */
    protected static $instance;

    /**
     * Get current instance of app
     *
     * @return App
     */
    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Construct app
     */
    public function __construct()
    {
        $this->setup_hooks();
        $this->setup_shortcodes();

        add_action('wp_enqueue_scripts', array($this, 'setup_assets'));
    }

    /**
     * Set up hooks
     */
    public function setup_hooks()
    {
        foreach ($this->actions as $handler) {
            foreach ($handler::$hooks as $action) {
                add_action($action, new $handler, $handler::$priority, $handler::$arguments);
            }
        }

        foreach ($this->filters as $handler) {
            foreach ($handler::$hooks as $filter) {
                add_filter($filter, new $handler, $handler::$priority, $handler::$arguments);
            }
        }

        add_filter('woocommerce_return_to_shop_text', function () {
            return __('View Candidates');
        });

        add_filter('woocommerce_return_to_shop_redirect', function ($url) {
            return get_site_url() . '/candidates/';
        });

        add_filter('woocommerce_checkout_redirect_empty_cart', '__return_false');
        add_filter('woocommerce_checkout_update_order_review_expired', '__return_false');

        // HIDE PAYMENT METHODS when the cart total is $0
        add_filter('woocommerce_cart_needs_payment', function ($needs_payment, $cart) {
            if ($cart->get_cart_contents_total() <= 0) {
                $needs_payment = false;
            }
            return $needs_payment;
        }, 99, 2);

        add_action('affwp_register_redirect', function ($url) {
            wc_add_notice('You have been registered as an Advocate successfully. Thank you!');
        });

        add_action('affwp_affiliate_dashboard_urls_top', function () {
            wc_print_notices();
        });
    }

    /**
     * Set up assets
     */
    public function setup_assets()
    {
        wp_enqueue_style('childfree',
            WZ_CHILDFREE_URL . 'assets/css/childfree.css?h='.uniqid('', true), [],
            WZ_CHILDFREE_VERSION);

        if (is_front_page()) {
            wp_enqueue_script('childfree-frontpage',
                WZ_CHILDFREE_URL . 'assets/js/frontpage.js?h='.uniqid('', true), array('jquery'),
                WZ_CHILDFREE_VERSION, true );
            wp_localize_script('childfree-frontpage', 'ajax_object', array(
                'ajaxurl' => admin_url('admin-ajax.php'), // DO NOT change this
                'nonce' => wp_create_nonce( 'browse_candidates_nonce' ), // Create nonce for security
            ));
        }

        wp_register_script('childfree-notifications',
            WZ_CHILDFREE_URL . 'assets/js/notifications.js?h='.uniqid('', true), array('jquery'),
            WZ_CHILDFREE_VERSION, true);

        wp_register_script('childfree-radius-filter',
            WZ_CHILDFREE_URL . 'assets/js/radius-filter.js?h='.uniqid('', true),
            array('jet-smart-filters'), WZ_CHILDFREE_VERSION, true);
        wp_register_script('childfree-copy-url', WZ_CHILDFREE_URL . 'assets/js/copy-url.js?h='.uniqid('',
                true), array('jquery'), WZ_CHILDFREE_VERSION, true);

        wp_enqueue_script('childfree-tooltips', WZ_CHILDFREE_URL . 'assets/js/tooltips.js?h='.uniqid('',
                true), array('jquery', 'jquery-ui-tooltip'), WZ_CHILDFREE_VERSION, true);


        wp_enqueue_script('childfree-multi-select', WZ_CHILDFREE_URL . 'assets/js/register-as-candidate.js?h='.uniqid('',
                true), array('jquery'), WZ_CHILDFREE_VERSION, true);
        wp_localize_script('childfree-multi-select', 'cbc_multiselect_options', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cbc_sponsor_multiple_candidates')
        ));

        // enqueue browse-candidates.js only on browse candidates page /candidates/
        if (is_page('thank-you_page')) {
            wp_enqueue_script('childfree-thank-you-page',
                WZ_CHILDFREE_URL . 'assets/js/thankyoupage.js?h='.uniqid('', true), array('jquery'),
                WZ_CHILDFREE_VERSION, true);
            wp_localize_script('childfree-thank-you-page', 'ajax_object', array(
                'ajaxurl' => admin_url('admin-ajax.php'), // DO NOT change this
                'ajax_nonce' => wp_create_nonce( 'thank_you_page_nonce' ), // Create nonce for security
            ));
        }

        // enqueue browse-candidates.js only on browse candidates page /candidates/
        if (is_page(14)) {
            wp_enqueue_script('childfree-browse-candidates',
                WZ_CHILDFREE_URL . 'assets/js/browse-candidates.js?h='.uniqid('', true), array('jquery'),
                WZ_CHILDFREE_VERSION, true);
            wp_localize_script('childfree-browse-candidates', 'ajax_object', array(
                'ajaxurl' => admin_url('admin-ajax.php'), // DO NOT change this
                'ajax_nonce' => wp_create_nonce( 'browse_candidates_nonce' ), // Create nonce for security
            ));
        }

        if (is_page(52169)) {
            wp_enqueue_script('childfree-browse-candidates',
                WZ_CHILDFREE_URL . 'assets/js/physician-browse-candidates.js?h='.uniqid('', true), array('jquery'),
                WZ_CHILDFREE_VERSION, true);
            wp_localize_script('childfree-browse-candidates', 'ajax_object', array(
                'ajaxurl' => admin_url('admin-ajax.php'), // DO NOT change this
                'ajax_nonce' => wp_create_nonce( 'browse_candidates_nonce' ), // Create nonce for security
            ));
        }


        // if single product page
        if (is_product()) {
            wp_enqueue_script('childfree-single-product',
                WZ_CHILDFREE_URL . 'assets/js/single-product.js?h='.uniqid('', true),
                array('jquery'), WZ_CHILDFREE_VERSION, true);
            $product_id = get_the_ID();
            // get amount remaining from src\Models\Candidate.php for the current product
            $candidate = new Candidate($product_id);
            $remaining_amount = $candidate->get_amount_remaining();
            // Localize the script to pass data to it
            wp_localize_script('childfree-single-product', 'elementData', array(
                'remaining_amount' => $remaining_amount,  // Replace with the current element ID
                'product_id' => $product_id,  // Current Candidate ID
                'nonce' => wp_create_nonce( 'browse_candidates_nonce' ), // Create nonce for security
                'ajaxurl' => admin_url('admin-ajax.php'), // DO NOT change this
                'newId' => 'new-element-id' // Replace with the new element ID
            ));

            wp_enqueue_script('childfree-amount-selector', WZ_CHILDFREE_URL . 'assets/js/amount-selector.js?h='.uniqid('', true),
                array('jquery'), WZ_CHILDFREE_VERSION, true);
        }
    }

    /**
     * Set up shortcodes
     */
    public function setup_shortcodes()
    {
        foreach ($this->shortcodes as $shortcode => $handler) {
            add_shortcode($shortcode, new $handler);
        }
    }
}
