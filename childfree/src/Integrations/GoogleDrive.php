<?php

namespace WZ\ChildFree\Integrations;

use WZ\ChildFree\Models\Options;
use Google\Service\Drive\DriveFile;

class GoogleDrive
{
    /**
     * Options model
     *
     * @var Options
     */
    protected $options;

    /**
     * Google API Client
     *
     * @var \Google_Client
     */
    public $client;

    /**
     * Google Drive API Service
     *
     * @var \Google_Service_Drive
     */
    public $service;

    /**
     * Construct the Google Drive Service wrapper
     *
     * @throws \Google\Exception
     */
    public function __construct()
    {
        $this->options = new Options();

        $this->client = new \Google_Client();
        $this->client->setApplicationName('Procedure Notes Upload');
        $this->client->setScopes(\Google_Service_Drive::DRIVE);
        $this->client->setAccessType('offline');
        $this->client->setAuthConfig(['web' => [
            'project_id' => 'WZ-342323',
            'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
            'token_uri' => 'https://oauth2.googleapis.com/token',
            'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
            'redirect_uris' => [
                'https://childfree.WZ.dev/wp-admin/admin-post.php?action=cbc_google_drive_authorization'
            ],
            'javascript_origins' => [
                'https://childfree.WZ.dev'
            ],

            'client_id' => $this->options->google_client_id,
            'client_secret' => $this->options->google_client_secret
        ]]);

        $this->check_access_token();

        $this->service = new \Google_Service_Drive($this->client);
    }

    /**
     * Get google drive client
     *
     * @return bool
     */
    private function check_access_token() {
        $access_token = $this->options->google_access_token;

        if ($access_token) {
            $this->client->setAccessToken( $access_token );
        }

        // If there is no previous token or it's expired.
        if ( $this->client->isAccessTokenExpired() ) {
            // Refresh the token if possible, else fetch a new one.
            if ( $this->client->getRefreshToken() ) {
                $this->client->fetchAccessTokenWithRefreshToken( $this->client->getRefreshToken() );
            } else {
                // TODO: throw error

                return false;
            }

            $this->options->google_access_token = $this->client->getAccessToken();
            $this->options->save();
        }

        return true;
    }

    /**
     * Create a new auth url
     *
     * @return string
     */
    public function create_auth_url() {
        return $this->client->createAuthUrl();
    }

    /**
     * Authorize app and save new access token
     *
     * @param string $auth_token
     * @throws \Exception
     */
    public function authorize( string $auth_token ) {
        $access_token = $this->client->fetchAccessTokenWithAuthCode( $auth_token );

        // Check to see if there was an error.
        if ( array_key_exists( 'error', $access_token ) ) {
            throw new \Exception( join( ', ', $access_token ) );
        }

        $this->client->setAccessToken( $access_token );

        $this->options->google_access_token = $this->client->getAccessToken();
        $this->options->save();
    }

    /**
     * Upload file to Google Drive
     *
     * @param string $file_path
     */
    public function upload( string $file_path ) {
        // $parentId = $this->getFolderId();

        $file_name 	= basename( $file_path );
        $content 	= file_get_contents( $file_path );
        $mime_type 	= mime_content_type( $file_path );

        $file = new DriveFile();
        $file->setName( $file_name );

        $this->service->files->create(
            $file,
            array(
                'data' 		 => $content,
                'mimeType' 	 => $mime_type,
                'uploadType' => 'multipart'
            )
        );
    }

    /**
     * Get the parent folder ID
     *
     * @return string
     */
    public function get_folder_id() {
        $response = $this->service->files->listFiles([
            'q' => "name='Procedure Notes' and mimeType='application/vnd.google-apps.folder'"
        ]);

        $folders = $response->getFiles();

        if ( empty( $folders ) ) {
            $folderMeta = new DriveFile([
                'name' => 'Procedure Notes',
                'mimeType' => 'application/vnd.google-apps.folder'
            ]);

            $folder = $this->service->files->create($folderMeta);

            return $folder->getId();
        }

        return $folders[0]->getId();
    }
}
