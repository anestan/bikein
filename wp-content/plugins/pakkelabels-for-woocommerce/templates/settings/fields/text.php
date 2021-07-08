<?php
	/**
	 * @var string $name
	 * @var string $value
	 */
?>
<input type='text' name='<?php echo $name; ?>>' value='<?php echo $value; ?>' style="width: 400px; max-width: 100%;<?php echo !empty($error_message) ? ' border-color: red;' : ''; ?>">
<?php
	if(!empty($error_message)) {
		?>
		<div style="color: red;margin-top: 3px;"><?php echo $error_message; ?></div>
		<?php
	}