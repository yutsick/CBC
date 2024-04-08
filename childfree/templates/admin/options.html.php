<?php


?>
<div class="wrap">
    <h1>ChildFree By Choice - Settings</h1>

    <form action="<?= admin_url('admin-post.php') ?>" method="post" novalidate>
        <input type="hidden" name="action" value="cbc_save_options" />

        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="google_client_id">Google Project ID</label>
                </th>
                <td>
                    <input type="text" name="google_project_id" value="<?= $google_project_id ?>" class="regular-text" />
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="google_client_id">Google Client ID</label>
                </th>
                <td>
                    <input type="text" name="google_client_id" value="<?= $google_client_id ?>" class="regular-text" />
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="google_client_secret">Google Client Secret</label>
                </th>
                <td>
                    <input type="password" name="google_client_secret" value="<?= $google_client_secret ?>" class="regular-text" />
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="google_client_secret"></label>
                </th>
                <td>
                    <a href="#" class="button">Re-Authorize</a>
                </td>
            </tr>
            </tbody>
        </table>

        <hr>

        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="twilio_sid">Sales Message Token</label>
                </th>
                <td>
                    <input type="text" name="salesmsg_token" value="<?= $salesmsg_token ?>" class="regular-text" />
                </td>
            </tr>
            </tbody>
        </table>

        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="twilio_sid">Sales Message Team ID</label>
                </th>
                <td>
                    <input type="text" name="salesmsg_team" value="<?= $salesmsg_team ?>" class="regular-text" />
                </td>
            </tr>
            </tbody>
        </table>

        <hr>

        <p class="submit">
            <button class="button button-primary">Save Changes</button>
        </p>
    </form>
</div>
