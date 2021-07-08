<select name="<?php echo $name; ?>">
	<?php
		foreach($options as $option) {
			?>
			<option value="<?php echo $option['value']; ?>" <?php selected($value, $option['value']); ?>><?php echo $option['title']; ?></option>
			<?php
		}
	?>
</select>