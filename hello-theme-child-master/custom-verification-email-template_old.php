<!-- function custom_verification_email_template( $email, $user_id, $activate_url ) {
    $subject = 'Please Verify Your Email Address';
    
    $template_path = get_stylesheet_directory() . '/custom-verification-email-template.php';
    
    // Check if the template file exists
    if ( file_exists( $template_path ) ) {
        $message = file_get_contents( $template_path );
        // Replace placeholders with actual values
        $message = str_replace( '{activate_url}', esc_url( $activate_url ), $message );
    } else {
        // Fallback to default message if the template file doesn't exist
        $message = '<p>Thank you for registering! To complete the registration process, please click on the following link:</p>';
        $message .= '<p><a href="' . esc_url( $activate_url ) . '">' . esc_url( $activate_url ) . '</a></p>';
        $message .= '<p>If you did not sign up for our website, you can ignore this email.</p>';
    }
    
    $email['subject'] = $subject;
    $email['message'] = $message;
    
    return $email;
}

add_filter( 'wpmu_signup_user_notification_email', 'custom_verification_email_template', 10, 3 ); -->

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <title>Mail verify</title>
</head>
<body style="font-family: Be Vietnam Pro, sans-serif; background-color: #fbf8f6; color: #333; margin: 0; padding: 0;">

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin: 0 auto; max-width: 600px; border-collapse: collapse;">
        <tr>
            <td colspan="2" style="padding: 20px 0; text-align: center; background-color: #fbf8f6; color: #fff; font-size: 24px;">
                <a href="https://childfreebc.com/"><img src="https://i.ibb.co/2sXWYBq/childfree-logo-1.png" alt="logo-mail"></a>
            </td>            
        </tr>
        <tr>
            <td style="padding: 32px; background-color: #fff; border-radius: 12px 12px 0 0;" colspan="2">
                <div style="text-align: center;">
                    <a href="https://childfreebc.com/"><img src="https://i.ibb.co/tzpHLXz/Envelope.png" alt="Envelope"></a>
                </div>
                <h1 style="margin: 0 0 20px; text-align: center; font-size: 32px; color: #143a62;">Verify Your Email!</h1>
                <p style="margin: 0 0 32px; font-size: 16px; color: #143a62;">Thanks for signing up for the weekly newsletter. Please click Confirm button for subscription to start receiving our emails.</p>
                <div style="text-align: center; margin: 0 0 32px;">
                    <a style="padding: 10px 37px; text-align: center; border-radius: 12px; background: #143a62; color: white; text-decoration: none;" href={activate_url}>Confirm email</a>
                </div>
                <span style="margin: 0 0 32px; color: #143a62;">Have any question? <a href="https://childfreebc.com/contact-us/" style="color: #8701a8; text-decoration: none; font-weight: 600;">Contact us</a></span>
            </td>
        </tr>
        <tr style="background: #143a62; color: white;">
            <td style="padding: 24px 0 14px 32px;">
                <a href="https://childfreebc.com/"><img src="https://i.ibb.co/vLj4XCQ/Group-1.png" alt="logo-white"></a>
            </td>
            <td style="padding: 24px 32px 14px 32px; text-align: end;">
                <table>
                    <tr>
                        <td style="padding-right: 16px;">
                            <a href="https://www.facebook.com/ChildFreeBC/"><img src="https://i.ibb.co/7W693yn/Facebook-Logo.png" alt="Facebook-Logo"></a>
                        </td>
                        <td style="padding-right: 16px;">
                            <a href="https://twitter.com/childfreebc1"><img src="https://i.ibb.co/TtCKy9T/fi-5968958.png" alt="fi-5968958"></a>
                        </td>
                        <td style="padding-right: 16px;">
                            <a href="https://www.instagram.com/childfreebc/"><img src="https://i.ibb.co/ZHsLgFC/Instagram-Logo.png" alt="Instagram-Logo"></a>
                        </td>
                        <td style="padding-right: 16px;">
                            <a href="https://rumble.com/user/ChildFreeBC"><img src="https://i.ibb.co/pvKdTt2/rumble.png" alt="rumble"></a>
                        </td>
                        <td style="padding-right: 16px;">
                            <a href="https://www.youtube.com/@ChildFreeByChoice"><img src="https://i.ibb.co/JK5W2RB/Youtube-Logo.png" alt="Youtube-Logo"></a>
                        </td>
                        <td style="padding-right: 16px;">
                            <a href="https://www.tiktok.com/@childfreebc"><img src="https://i.ibb.co/qstZjbJ/Tiktok-Logo.png" alt="Tiktok-Logo"></a>
                        </td>
                        <td>
                            <a href="https://gab.com/ChildFreeBC"><img src="https://i.ibb.co/P4R45dY/gab.png" alt="gab"></a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr style="background: #143a62; color: white;">
            <td style="padding: 0 0 32px 32px">
                <a href="https://maps.app.goo.gl/VXXsz3zoEgG6Yd5A8" style="color: white; text-decoration: none;">
                    24044 Cinco Village Center Blvd., Ste 100 PMB 33 <br> Katy, TX 77494
                </a>
            </td>
            <td style="padding: 0px 32px 14px">
                <table style="width: 100%;">
                    <tr>
                        <td style="padding-right: 16px; text-align: right; width: 100%;">
                            <img src="https://i.ibb.co/wQKShX6/Phone.png" alt="Phone">
                        </td>
                        <td>
                            <a style="color: white; text-decoration: none;" href="tel:9377017442">(937) 701-7442</a>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-right: 16px; text-align: right; width: 100%;">
                            <img src="https://i.ibb.co/3y7fxQv/mail.png" alt="mail">
                        </td>
                        <td>
                            <a style="color: white; text-decoration: none;" href="mailto:info@ChildFreebc.com">info@ChildFreebc.com</a>
                        </td>
                    </tr>
                </table>
                <div>
            </td>
        </tr>
        <tr style="background: #143a62; color: white; text-align: center; padding-bottom: 24px;">
            <td colspan="2" style="padding: 20px; border-radius: 0 0 12px 12px;">© 2024 ChildFree By Choice, LLC. All rights reserved.</td>
        </tr>
    </table>

</body>
</html>
