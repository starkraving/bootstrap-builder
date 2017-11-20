<section class="container-fluid">
	<?php foreach ( $contents as $row ) : ?>
		<div class="row">
			<?php foreach ( $row as $column ) : ?>
				<div data-ho-grid="column" class="<?= setSpanFromContent($column, $row); ?>">
					<?= $this->load->view('page/ho_'.$column['type'], array('column' => $column)); ?>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endforeach; ?>
</section>