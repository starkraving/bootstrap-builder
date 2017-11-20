<?php
$postID = get_the_id();
$scriptMTime = filemtime( BOOTSTRAP_BUILDER_BASEDIR.'/assets/js/bootstrap-builder-app.js' );
?>
<div id="bootstrap_builder" ng-app="hoBuilder" ng-controller="init" ng-click="editor.deactivate($event)">
		<section id="ho_content" class="container-fluid" ng-model="content" as-sortable="sortableOptions">
			<div data-ho-grid="row" class="row" ng-repeat="row in content" as-sortable-item>
				<div data-ho-grid="column" ng-repeat="column in row" class="{{setSpanFromContent(column.id)}}" ng-include="'<?= BOOTSTRAP_BUILDER_BASEURL; ?>/_partials/ho_'+column.type+'.html'"></div>
				<button class="ho_handle_row" as-sortable-item-handle ng-show="editor.editing === true && editor.count() > 1 && editor.rowID === $index;" data-toggle="tooltip" data-placement="right" title="Drag row horizontally">
					<img src="<?= BOOTSTRAP_BUILDER_BASEURL; ?>/assets/img/icons/handle-row.png" alt="Drag row horizontally" /></button>
			</div>
		</section>
		<!-- include the builder interface -->
		<section id="ho_application" ng-include="'<?= BOOTSTRAP_BUILDER_BASEURL; ?>/_partials/ho_builderui.html'"></section>
		<div ng-include="'<?= BOOTSTRAP_BUILDER_BASEURL; ?>/index.php/page/save/<?= $postID; ?>'"></div>
		
		<!-- run the scripts -->
		<script type="text/javascript">
			var bootstrap_builder_baseurl = '<?= BOOTSTRAP_BUILDER_BASEURL; ?>';
			var bootstrap_builder_postid = '<?= $postID; ?>';
			var bootstrap_builder_posturl = '<?= get_permalink($postID); ?>';
		</script>
		<script type="text/javascript" src="<?= BOOTSTRAP_BUILDER_BASEURL; ?>/assets/angular/angular.min.js"></script>
		<script type="text/javascript" src="<?= BOOTSTRAP_BUILDER_BASEURL; ?>/assets/angular/ng-sortable.min.js"></script>
		<script type="text/javascript" src="<?= BOOTSTRAP_BUILDER_BASEURL; ?>/assets/js/bootstrap-builder-app.js?ver=<?= $scriptMTime; ?>"></script>
	</div>