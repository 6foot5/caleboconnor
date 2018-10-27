<?php
    $selected = ($shortcode === 'empty') ? 'selected' : '';
?>

<p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>

<?php
if ($this->shortcodes_data && count($this->shortcodes_data) > 0) {
?>
<p>
    <label for="<?php echo $this->get_field_id( 'shortcode' ); ?>"><?php _e( 'Spin:' ); ?></label>
    
    <select class="widefat" id="<?php echo $this->get_field_id( 'shortcode' ); ?>" name="<?php echo $this->get_field_name( 'shortcode' ); ?>">
        <option <?php echo $selected; ?> value="empty"></option>
        <?php
            foreach ($this->shortcodes_data as $key => $value) {
                if ( !isset( $value->shortcode ) ) {
                    $id = $value->shortcode;
                } else {
                    $id = $value->id;
                }
                $selected = ($shortcode === $value->id) ? 'selected' : '';
        ?>
        <option <?php echo $selected; ?> value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
        <?php } ?>
    </select>
</p>
<?php } ?>

<p>
    <a href="<?php echo $this->shortcode_url; ?>">Create new spin</a>
</p>
