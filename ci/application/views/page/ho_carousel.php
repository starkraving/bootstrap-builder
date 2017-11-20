	<div class="<?= setClassFromContent($column['attributes']); ?>" style="<?= setStyleFromContent($column['styles']); ?>">
 		<div id="ho_carousel_<?= $column['id']; ?>" class="carousel" data-ride="carousel">
			<ol class="carousel-indicators"><?php foreach($column['attributes']['slides'] as $index => $slide) : ?>
				<li data-target="#ho_carousel_<?= $column['id']; ?>" data-slide-to="<?= $index; ?>" class="<?= $index === 0 ? 'active' : ''; ?>"></li><?php endforeach; ?>
			</ol>
			<div class="carousel-inner" role="listbox"><?php foreach ( $column['attributes']['slides'] as $index => $slide) : ?>
				<div class="<?= $index === 0 ? 'item active' : 'item'; ?>">
					<img src="<?= $slide['image_src']; ?>" alt="Slideshow Image" />
					<div class="carousel-caption" ><?= $slide['content']; ?></div>
				</div><?php endforeach; ?>
			</div>
			<a class="left carousel-control" href="#ho_carousel_<?= $column['id']; ?>" role="button" data-slide="prev"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span><span class="sr-only">Previous</span></a>
			<a class="right carousel-control" href="#ho_carousel_<?= $column['id']; ?>" role="button" data-slide="next"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span><span class="sr-only">Next</span></a>
		</div>
	</div>
