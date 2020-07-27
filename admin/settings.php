<?php
/**
*
* @return settings
* @author Sina Shamsizadeh
* @version 1.0.0
**/

if ( ! defined('ABSPATH') ) {
    exit;
} ?>
<div class="wrap">
    <div class="invoice-validator-settings">
        <div class="invoice-validator-settings-header">
            <h1 class="wp-heading-inline"><?php echo esc_html__( 'Invoice Validator Settings', 'invoice-validator' ); ?></h1>
        </div>
        <form class="invoice-validator-form" methor="post" action="options.php" name="form">
            <?php
            settings_fields( 'invocie_validator_options_group' );
            do_settings_sections( 'invocie_validator_options_group' );
            ?>
            <!-- Company -->
            <div class="invi-setting">
                <label for="<?php echo esc_attr( INVLIBASEID . '_company_name' ); ?>"><h3><?php echo esc_html__( 'Company', 'invoice-validator' ); ?></h3></label>
                <input type="text" id="<?php echo esc_attr( INVLIBASEID . '_company_name' ); ?>" name=<?php echo esc_attr( INVLIBASEID . '_company_name' ); ?> value="<?php echo esc_attr( get_option(INVLIBASEID . '_company_name') ); ?>">
            </div>
            <!-- Vat Number -->
            <div class="invi-setting">
                <label for="<?php echo esc_attr( INVLIBASEID . '_vat_number' ); ?>"><h3><?php echo esc_html__( 'Vat Number', 'invoice-validator' ); ?></h3></label>
                <input type="text" id="<?php echo esc_attr( INVLIBASEID . '_vat_number' ); ?>" name=<?php echo esc_attr( INVLIBASEID . '_vat_number' ); ?> value="<?php echo esc_attr( get_option(INVLIBASEID . '_vat_number') ); ?>">
            </div>
            <!-- Tax -->
            <div class="invi-setting">
                <label for="<?php echo esc_attr( INVLIBASEID . '_tax' ); ?>"><h3><?php echo esc_html__( 'Tax', 'invoice-validator' ); ?></h3></label>
                <input type="text" id="<?php echo esc_attr( INVLIBASEID . '_tax' ); ?>" name=<?php echo esc_attr( INVLIBASEID . '_tax' ); ?> value="<?php echo esc_attr( get_option(INVLIBASEID . '_tax') ); ?>">
            </div>
            <!-- Aditional Tax -->
            <div class="invi-setting">
                <label for="<?php echo esc_attr( INVLIBASEID . '_additional_tax' ); ?>"><h3><?php echo esc_html__( 'Additional Tax', 'invoice-validator' ); ?></h3></label>
                <input type="number" id="<?php echo esc_attr( INVLIBASEID . '_additional_tax' ); ?>" name=<?php echo esc_attr( INVLIBASEID . '_additional_tax' ); ?> value="<?php echo esc_attr( get_option(INVLIBASEID . '_additional_tax') ); ?>">
            </div>
            <!-- Phone Number -->
            <div class="invi-setting">
                <label for="<?php echo esc_attr( INVLIBASEID . '_phone_number' ); ?>"><h3><?php echo esc_html__( 'Phone Number', 'invoice-validator' ); ?></h3></label>
                <input type="tel" id="<?php echo esc_attr( INVLIBASEID . '_phone_number' ); ?>" name=<?php echo esc_attr( INVLIBASEID . '_phone_number' ); ?> value="<?php echo esc_attr( get_option(INVLIBASEID . '_phone_number') ); ?>">
            </div>
            <!-- Address -->
            <div class="invi-setting">
                <label for="<?php echo esc_attr( INVLIBASEID . '_address' ); ?>"><h3><?php echo esc_html__( 'Address', 'invoice-validator' ); ?></h3></label>
                <textarea id="<?php echo esc_attr( INVLIBASEID . '_address' ); ?>" name=<?php echo esc_attr( INVLIBASEID . '_address' ); ?> value="<?php echo esc_attr( get_option(INVLIBASEID . '_address') ); ?>" id="address" cols="80" rows="10"> <?php echo esc_html( get_option(INVLIBASEID . '_address') ); ?> </textarea>
            </div>
            <!-- Plaque -->
            <div class="invi-setting">
                <label for="<?php echo esc_attr( INVLIBASEID . '_plaque' ); ?>"><h3><?php echo esc_html__( 'Plaque', 'invoice-validator' ); ?></h3></label>
                <input type="number" id="<?php echo esc_attr( INVLIBASEID . '_plaque' ); ?>" name=<?php echo esc_attr( INVLIBASEID . '_plaque' ); ?> value="<?php echo esc_attr( get_option(INVLIBASEID . '_plaque') ); ?>">
            </div>
            <!-- Insurance -->
            <div class="invi-setting">
                <label for="<?php echo esc_attr( INVLIBASEID . '_insurance' ); ?>"><h3><?php echo esc_html__( 'Insurance', 'invoice-validator' ); ?></h3></label>
                <input type="number" id="<?php echo esc_attr( INVLIBASEID . '_insurance' ); ?>" name=<?php echo esc_attr( INVLIBASEID . '_insurance' ); ?> value="<?php echo esc_attr( get_option(INVLIBASEID . '_insurance') ); ?>">
            </div>
            <input type="hidden" name="formaction" value="default">
            <!-- Submit -->
            <div class="invi-setting">
                <?php submit_button(); ?>
            </div>
        </form>
    </div>
</div>
<?php
// if ( isset( $_REQUEST['settings-updated'] ) ) {
//     if ( $_REQUEST['settings-updated'] == 'true' ) {
//     }
// }