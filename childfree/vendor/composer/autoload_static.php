<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInited7e9e80e4a9be7ca60bd54d4ede22e1
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WZ\\ChildFree\\' => 13,
        ),
        'D' => 
        array (
            'DASPRiD\\Enum\\' => 13,
        ),
        'B' => 
        array (
            'BaconQrCode\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WZ\\ChildFree\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'DASPRiD\\Enum\\' => 
        array (
            0 => __DIR__ . '/..' . '/dasprid/enum/src',
        ),
        'BaconQrCode\\' => 
        array (
            0 => __DIR__ . '/..' . '/bacon/bacon-qr-code/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'WZ\\ChildFree\\Actions\\Admin\\AddCandidateDataPanel' => __DIR__ . '/../..' . '/src/Actions/Admin/AddCandidateDataPanel.php',
        'WZ\\ChildFree\\Actions\\Admin\\AddCandidateDataTab' => __DIR__ . '/../..' . '/src/Actions/Admin/AddCandidateDataTab.php',
        'WZ\\ChildFree\\Actions\\Admin\\AddCandidateExportColumns' => __DIR__ . '/../..' . '/src/Actions/Admin/AddCandidateExportColumns.php',
        'WZ\\ChildFree\\Actions\\Admin\\AddCandidateExportData' => __DIR__ . '/../..' . '/src/Actions/Admin/AddCandidateExportData.php',
        'WZ\\ChildFree\\Actions\\Admin\\HideAdminProductDataTabs' => __DIR__ . '/../..' . '/src/Actions/Admin/HideAdminProductDataTabs.php',
        'WZ\\ChildFree\\Actions\\Candidate\\FilterGeneralDonationProducts' => __DIR__ . '/../..' . '/src/Actions/Candidate/FilterGeneralDonationProducts.php',
        'WZ\\ChildFree\\Actions\\Candidate\\FilterProductLabels' => __DIR__ . '/../..' . '/src/Actions/Candidate/FilterProductLabels.php',
        'WZ\\ChildFree\\Actions\\Candidate\\FilterProductTypes' => __DIR__ . '/../..' . '/src/Actions/Candidate/FilterProductTypes.php',
        'WZ\\ChildFree\\Actions\\Notifications\\RegisterDataStore' => __DIR__ . '/../..' . '/src/Actions/Notifications/RegisterDataStore.php',
        'WZ\\ChildFree\\App' => __DIR__ . '/../..' . '/src/App.php',
        'WZ\\ChildFree\\DataStores\\NotificationDataStore' => __DIR__ . '/../..' . '/src/DataStores/NotificationDataStore.php',
        'WZ\\ChildFree\\DataStores\\SubscriptionDataStore' => __DIR__ . '/../..' . '/src/DataStores/SubscriptionDataStore.php',
        'WZ\\ChildFree\\Models\\Candidate' => __DIR__ . '/../..' . '/src/Models/Candidate.php',
        'WZ\\ChildFree\\Models\\Donation' => __DIR__ . '/../..' . '/src/Models/Donation.php',
        'WZ\\ChildFree\\Models\\Funding' => __DIR__ . '/../..' . '/src/Models/Funding.php',
        'WZ\\ChildFree\\Models\\Notification' => __DIR__ . '/../..' . '/src/Models/Notification.php',
        'WZ\\ChildFree\\Models\\Provider' => __DIR__ . '/../..' . '/src/Models/Provider.php',
        'WZ\\ChildFree\\Services\\EmailVerification' => __DIR__ . '/../..' . '/src/Services/EmailVerification.php',
        'WZ\\ChildFree\\Services\\Verification' => __DIR__ . '/../..' . '/src/Services/Verification.php',
        'WZ\\ChildFree\\Shortcodes\\CandidateAmountRaised' => __DIR__ . '/../..' . '/src/Shortcodes/CandidateAmountRaised.php',
        'WZ\\ChildFree\\Shortcodes\\CandidateGender' => __DIR__ . '/../..' . '/src/Shortcodes/CandidateGender.php',
        'WZ\\ChildFree\\Shortcodes\\CandidateGoalAmount' => __DIR__ . '/../..' . '/src/Shortcodes/CandidateGoalAmount.php',
        'WZ\\ChildFree\\Shortcodes\\CandidateLocation' => __DIR__ . '/../..' . '/src/Shortcodes/CandidateLocation.php',
        'WZ\\ChildFree\\Shortcodes\\CandidateProgress' => __DIR__ . '/../..' . '/src/Shortcodes/CandidateProgress.php',
        'WZ\\ChildFree\\Shortcodes\\CandidateRecentDonations' => __DIR__ . '/../..' . '/src/Shortcodes/CandidateRecentDonations.php',
        'WZ\\ChildFree\\Shortcodes\\CandidateRemainingAmount' => __DIR__ . '/../..' . '/src/Shortcodes/CandidateRemainingAmount.php',
        'WZ\\ChildFree\\Shortcodes\\CandidateSeeking' => __DIR__ . '/../..' . '/src/Shortcodes/CandidateSeeking.php',
        'WZ\\ChildFree\\Shortcodes\\CandidateTopDonations' => __DIR__ . '/../..' . '/src/Shortcodes/CandidateTopDonations.php',
        'WZ\\ChildFree\\Shortcodes\\CandidatesCount' => __DIR__ . '/../..' . '/src/Shortcodes/CandidatesCount.php',
        'WZ\\ChildFree\\Template' => __DIR__ . '/../..' . '/src/Template.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInited7e9e80e4a9be7ca60bd54d4ede22e1::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInited7e9e80e4a9be7ca60bd54d4ede22e1::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInited7e9e80e4a9be7ca60bd54d4ede22e1::$classMap;

        }, null, ClassLoader::class);
    }
}
