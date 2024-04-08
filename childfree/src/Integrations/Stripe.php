<?php

namespace WZ\ChildFree\Integrations;

class Stripe {
	const ACCOUNT_ID_KEY = 'cbc_stripe_account_id';

	/**
	 * Get user account id from user meta
	 *
	 * @param int $user_id
	 * @return string
	 */
	public function get_account_id( int $user_id ) {
		return (string) get_user_meta($user_id, self::ACCOUNT_ID_KEY, true);
	}

	/**
	 * Create a new connected account for user
	 *
	 * @param int $user_id
	 * @return \stdClass
	 * @throws \WC_Stripe_Exception
	 */
	public function create_account( int $user_id ) {
		$user = get_user_by( 'ID', $user_id );

		$account = \WC_Stripe_API::request(
			[
				'type' => 'express',
				'email' => $user->user_email
			],
			'accounts'
		);

		update_user_meta( $user_id, self::ACCOUNT_ID_KEY, $account->id );

		return $account;
	}

	/**
	 * Get account by account_id from user meta
	 *
	 * @param int $user_id
	 * @return \stdClass|false
	 * @throws \WC_Stripe_Exception
	 */
	public function get_account( int $user_id ) {
		$accountID = $this->get_account_id( $user_id );

		if ( '' === $accountID ) {
			return false;
		}

		return \WC_Stripe_API::retrieve( "accounts/{$accountID}" );
	}

	/**
	 * Get or Create Stripe account ID for user
	 *
	 * @param int $user_id
	 * @return \stdClass
	 */
	public function get_or_create_account( int $user_id ) {
		if ( $this->has_account( $user_id ) ) {
			return $this->get_account( $user_id );
		}

		return $this->create_account( $user_id );
	}

	/**
	 * Create an account onboarding link for a user
	 *
	 * @param $user_id
	 * @return string
	 * @throws \WC_Stripe_Exception
	 */
	public function create_onboarding_link( int $user_id ) {
		$account = $this->get_or_create_account( $user_id );

		$response = \WC_Stripe_API::request(
			[
				'account'		=> $account->id,
				'refresh_url'	=> admin_url( 'admin-post.php?action=cbc_stripe_onboarding_redirect' ),
				'return_url'	=> admin_url( 'admin-post.php?action=cbc_stripe_onboarding_return' ),
				'type'			=> 'account_onboarding'
			],
			'account_links'
		);

		return $response->url;
	}

	/**
	 * Create just onboarding link for a user without crating account
	 *
	 * @param $user_id
	 * @return string
	 * @throws \WC_Stripe_Exception
	 */
	public function create_stripe_onboarding_link( int $user_id ) {
		$account = $this->get_account( $user_id );

        if ( ! $account ) {
            return null;
        }
		$response = \WC_Stripe_API::request(
			[
				'account'		=> $account->id,
				'refresh_url'	=> admin_url( 'admin-post.php?action=cbc_stripe_onboarding_redirect' ),
				'return_url'	=> admin_url( 'admin-post.php?action=cbc_stripe_onboarding_return' ),
				'type'			=> 'account_onboarding'
			],
			'account_links'
		);

		return $response->url;
	}

	/**
	 * Create an account loging link for a user
	 *
	 * @param $user_id
	 * @return string
	 * @throws \WC_Stripe_Exception
	 */
	public function create_login_link( int $user_id ) {
		if ( ! $this->has_account( $user_id ) ) {
			return null;
		}

		$account = $this->get_account( $user_id );

		$response = \WC_Stripe_API::request(
			[],
			"accounts/{$account->id}/login_links"
		);

		return $response->url;
	}

	/**
	 * Check if user has a connected Stripe account
	 *
	 * @param int $user_id
	 * @return bool
	 */
	public function has_account( int $user_id ) {
		return '' !== $this->get_account_id( $user_id );
	}

	/**
	 * Check if user has finished onboarding and has charges enabled
	 *
	 * @param int $user_id
	 * @return bool
	 */
	public function is_account_enabled( int $user_id ) {
		try {
			return $this->get_account( $user_id )->payouts_enabled;
		} catch (\Exception $e) {
			return false;
		}
	}

	/**
	 * Transfer funds to user
	 *
	 * @param int $user_id
	 * @param int $amount
	 * @throws \WC_Stripe_Exception
	 */
	public function transfer( int $user_id, int $amount ) {
		$account_id = $this->get_account_id( $user_id );

		if ( '' === $account_id ) {
			return false;
		}

		return \WC_Stripe_API::request(
			[
				'amount' => $amount * 100,
				'destination' => $account_id,
				'currency' => 'usd',
			],
			'transfers'
		);
	}

	/**
	 * Get transfers for user
	 *
	 * @param int $user_id
	 */
	public function get_transfers( int $user_id ) {
		$account_id = $this->get_account_id( $user_id );

		if ( '' === $account_id ) {
			return [];
		}

		$response = \WC_Stripe_API::retrieve( "transfers?destination={$account_id}" );

		if ( property_exists( $response, 'error' ) ) {
			return [];
		}

		return $response->data;
	}

	// /**
	//  * Delete Stripe account for user
	//  *
	//  * @param int $user_id
	//  * @return bool
	//  */
	// public function delete_account( int $user_id ) {
	// 	$account_id = $this->get_account_id( $user_id );

	// 	if ( '' === $account_id ) {
	// 		return false;
	// 	}

	// 	$response = \WC_Stripe_API::request(
	// 		[],
	// 		"accounts/{$account_id}",
	// 		'DELETE'
	// 	);

	// 	if ( property_exists( $response, 'error' ) ) {
	// 		return false;
	// 	}

	// 	delete_user_meta( $user_id, self::ACCOUNT_ID_KEY );
	// 	update_user_meta( $user_id, self::ACCOUNT_ID_KEY, '' );

	// 	return true;
	// }
}
