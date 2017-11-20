var hoApp = angular.module('hoBuilder', ['as.sortable']);
hoApp.controller('init', function($scope, $sce, $compile, $http, $httpParamSerializerJQLike){
	var file_frame;
	$scope.baseURL = bootstrap_builder_baseurl;
	$scope.postID = bootstrap_builder_postid;
	$scope.postURL = bootstrap_builder_posturl;
	$scope.content = [];
	$scope.gridType = $scope.defaultGridType = 'column';
	$scope.contentHasChanged = false;
	$scope.editor = {
		'contentTypes': [
			{
				'type': 'jumbotron',
				'title': 'Jumbotron',
				'description': 'Use this content type for the lead-in title on a marketing page',
				'attributes': { 'class' : 'jumbotron' },
				'content' : '<div class="container"><h1>Hello, world!</h1><p>This is a template for a simple marketing or informational website. It includes a large callout called a jumbotron and three supporting pieces of content. Use it as a starting point to create something more unique.</p><p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more »</a></p></div>',
			},
			{
				'type': 'carousel',
				'title': 'Carousel',
				'description': 'Use this content type on a marketing page to animate through multiple text blocks and links',
				'attributes': {
					'slides' : [{
						'image_src': $scope.baseURL+'/assets/img/slides/image01867.jpg',
						'content' : '<h1>Example headline.</h1><p>Note: If you\'re viewing this page via a <code>file://</code> URL, the "next" and "previous" Glyphicon buttons on the left and right might not load/display properly due to web browser security rules.</p><p><a class="btn btn-lg btn-primary" href="#" role="button">Sign up today</a></p>'
					}] 
				},
			},
			{
				'type': 'supporting',
				'title': 'Supporting Content',
				'description': 'Block of text containing sub heading, paragraph and link',
				'attributes': {},
				'content': '<h2>Heading</h2><p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p><p><a class="btn btn-default" href="#" role="button">View details »</a></p>',
			},
			{
				'type': 'thumbnail',
				'title': 'Thumnbnail',
				'description': 'Gallery thumbnail with descriptive text and secondary button',
				'attributes': { 'class': 'thumbnail' },
				'content' : '<img data-src="holder.js/100%x200" alt="100%x200" class="img-responsive" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMTkyIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDE5MiAyMDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjwhLS0KU291cmNlIFVSTDogaG9sZGVyLmpzLzEwMCV4MjAwCkNyZWF0ZWQgd2l0aCBIb2xkZXIuanMgMi42LjAuCkxlYXJuIG1vcmUgYXQgaHR0cDovL2hvbGRlcmpzLmNvbQooYykgMjAxMi0yMDE1IEl2YW4gTWFsb3BpbnNreSAtIGh0dHA6Ly9pbXNreS5jbwotLT48ZGVmcz48c3R5bGUgdHlwZT0idGV4dC9jc3MiPjwhW0NEQVRBWyNob2xkZXJfMTUxOGNkNDZiYzkgdGV4dCB7IGZpbGw6I0FBQUFBQTtmb250LXdlaWdodDpib2xkO2ZvbnQtZmFtaWx5OkFyaWFsLCBIZWx2ZXRpY2EsIE9wZW4gU2Fucywgc2Fucy1zZXJpZiwgbW9ub3NwYWNlO2ZvbnQtc2l6ZToxMHB0IH0gXV0+PC9zdHlsZT48L2RlZnM+PGcgaWQ9ImhvbGRlcl8xNTE4Y2Q0NmJjOSI+PHJlY3Qgd2lkdGg9IjE5MiIgaGVpZ2h0PSIyMDAiIGZpbGw9IiNFRUVFRUUiLz48Zz48dGV4dCB4PSI3MC4wNjI1IiB5PSIxMDQuNSI+MTkyeDIwMDwvdGV4dD48L2c+PC9nPjwvc3ZnPg==" data-holder-rendered="true"> <div class="caption"> <h3>Thumbnail label</h3> <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p> <p><a href="#" class="btn btn-default" role="button">Button</a></p> </div>'
			}
		],
		'count': function(){
			return $scope.content.length;
		},
		'loaded': false,
		'rowID': null,
		'columnID': null,
		'elementID': null,
		'editing': false,
		'activate': function(evt, elementID) {
			var indexes = $scope.editor.findElement(elementID);
			$scope.editor.editing = true;
			$scope.editor.elementID = elementID;
			$scope.editor.rowID = indexes[0];
			$scope.editor.columnID = indexes[1];
			var objElement = jQuery(evt.target),
				objRow = objElement.parents('.row').first();
			if ( objElement.filter('[ng-attr-contenteditable]').length === 0 ) {
				objElement = objElement.parents('[ng-attr-contenteditable]').first();
			}
			var pos = objRow.offset();
			
			objRow.find('.ho_handle_row').css('left', (0-pos.left-50)+'px');
			if ( objElement.filter('[ng-attr-contenteditable]').length > 0 ) {
				setTimeout(function(){
					objElement.get(0).focus();
				}, 100);
			}
			evt.stopPropagation();
			evt.preventDefault();
			
		},
		/**
		 * Looks for all editable content and synchs the current values to the stored values
		 */
		'updateAllContent': function() {
			angular.forEach(angular.element('[data-column-id]'), function(domElement){
				$scope.editor.updateContent({target: domElement}, domElement.getAttribute('data-column-id'));
			});
		},
		/**
		 * Updates the content of a single editable element
		 * @param evt       - the scripting event with the target of the update
		 * @param elementID - the DOM ID for the element being updated
		 */
		'updateContent': function(evt, elementID) {
			var 	indexes = $scope.editor.findElement(elementID),
					rowID = indexes[0],
					columnID = indexes[1];
			jQuery(evt.target).find('.selected').removeClass('selected');
			if ( jQuery(evt.target).children('div.carousel').length === 0 ) {
				// any markup inside is part of the content
				$scope.content[rowID][columnID].content = evt.target.innerHTML;
			} else {
				// all markup is generated, slide content is stored as attributes of the carousel element
				angular.forEach(angular.element('#ho_carousel_'+elementID+' div.carousel-caption'), function(domElement, index){
					$scope.editor.updateSlide(index, 'content', domElement.innerHTML, rowID, columnID);
				});
			}
			$scope.contentHasChanged = true;
		},
		/**
		 * Updates a CSS attribute for the currently editing element
		 * @param key   - the attribute name
		 * @param value - the value of the attribute
		 */
		'updateStyle': function(key, value) {
			var rowID = $scope.editor.rowID;
			var columnID = $scope.editor.columnID;
			$scope.content[rowID][columnID].styles[key] = value;
			$scope.contentHasChanged = true;
		},
		'updateAlignment': function(alignment) {
			var rowID = $scope.editor.rowID;
			var columnID = $scope.editor.columnID;
			if ( typeof $scope.content[rowID][columnID].attributes['class'] == 'undefined' ) {
				$scope.content[rowID][columnID].attributes['class'] = alignment;
			} else {
				$scope.content[rowID][columnID].attributes['class']
						= $scope.content[rowID][columnID].attributes['class'].replace(/text-(left|center|right|justify)/, '')
						+ ' ' + alignment;
			}
			$scope.contentHasChanged = true;
		},
		'updateLink': function() {
			var link_href = '';
			var link_target = '';
			var link_button = '';
			var possibleBtnClasses = ['btn-default', 'btn-secondary', 'btn-primary', 'btn-success', 'btn-info', 'btn-warning', 'btn-danger'];
			//TODO: use angular native approach instead of jQuery
			(function($){
				var selectedLink = $('[contenteditable] a.selected');
				if ( selectedLink.length > 0 ) {
					link_href = selectedLink.attr('href');
					link_target = selectedLink.attr('target');
					var foundBtnClass = possibleBtnClasses.filter(function(className){
						return selectedLink.hasClass(className);
					})
					if ( foundBtnClass.length > 0 ) {
						link_button = foundBtnClass.shift();
					}
				} else {
					var selection = document.getSelection();
					link_href = '#';
					document.execCommand('createLink', false, link_href);
					selection.anchorNode.parentElement.className = 'selected';
					$scope.contentHasChanged = true;
				}
				$('#link_href').val(link_href);
				$('#link_target').val(link_target);
				$('#link_button').val(link_button);
			})(jQuery);
		},
		'removeStyle': function(key) {
			var rowID = $scope.editor.rowID;
			var columnID = $scope.editor.columnID;
			delete $scope.content[rowID][columnID].styles[key];
			$scope.contentHasChanged = true;
		},
		'updateSlide': function(index, key, value) {
			var rowID = typeof arguments[3] != 'undefined' ? arguments[3] : $scope.editor.rowID;
			var columnID = typeof arguments[4] != 'undefined' ? arguments[4] : $scope.editor.columnID;
			$scope.content[rowID][columnID].attributes.slides[index][key] = value;
			$scope.contentHasChanged = true;
		},
		'updateSlideContent': function(index, evt) {
			$scope.editor.updateSlide(index, 'content', evt.target.innerHTML);
			$scope.contentHasChanged = true;
		},
		'deleteSlide': function(index) {
			var rowID = $scope.editor.rowID;
			var columnID = $scope.editor.columnID;
			$scope.content[rowID][columnID].attributes.slides.splice(index, 1);
			$scope.contentHasChanged = true;
		},
		'deactivate': function(evt) {
			var objTarget = jQuery(evt.target);
			if ( objTarget.parents('#ho_application').length === 0 
					&& objTarget.parents('[data-ho-grid="column"] menu').length === 0 ) {
				$scope.editor.editing = false;
				$scope.editor.rowID = null;
				$scope.editor.columnID = null;
				$scope.editor.updateAllContent();
			}
		},
		'delete': function(elementID, evt) {
			var 	indexes = $scope.editor.findElement(elementID),
					rowID = indexes[0],
					columnID = indexes[1];
			$scope.content[rowID].splice(columnID, 1);
			if ( $scope.content[rowID].length === 0 ) {
				$scope.content.splice(rowID, 1);
				$scope.contentHasChanged = true;
			}
			$scope.editor.editing = false;
		},
		'button': function(cmd) {
			document.execCommand('styleWithCSS', false, false);
			document.execCommand(cmd, false, '');
			$scope.contentHasChanged = true;
		},
		'dropdown': function(cmd, arg) {
			document.execCommand(cmd, false, arg);
			$scope.contentHasChanged = true;
		},
		findElement: function(elementID) {
			var rowID = columnID = false;
			for ( var r = 0 ; r < $scope.content.length ; r++ ) {
				for ( var c = 0 ; c < $scope.content[r].length ; c++ ) {
					if ( $scope.content[r][c].id == elementID ) {
						columnID = c;
						break;
					}
				}
				if ( columnID !== false ) {
					rowID = r;
					break;
				}
			}
			return [r, c];
		},
		elementIsNotLast: function(elementID) {
			var 	indexes = $scope.editor.findElement(elementID),
					rowID = indexes[0],
					columnID = indexes[1];
			return ( $scope.content[rowID].length > columnID + 1 );
		}
	};
	$scope.getPartial = function(filename) {
		return $scope.baseURL + '/_partials/' + filename;
	};
	$scope.getSaveButton = function(){
		return ( $scope.contentHasChanged )
			? $scope.baseURL + '/assets/img/icons/btn-save.png'
			: $scope.baseURL + '/assets/img/icons/btn-saved.png';
	};
	$scope.setStyleFromContent = function(contentStyles) {
		var str = '';
		for ( var key in contentStyles ) {
			str += key + ': ' + contentStyles[key] + '; ';
		}
		return str;
	};
	$scope.setClassFromContent = function(columnAttrs) {
		return strClass = ( 'class' in columnAttrs ) ? columnAttrs['class'] : '';
	};
	$scope.setSpanFromContent = function(elementID) {
		var 	indexes = $scope.editor.findElement(elementID),
				rowID = indexes[0],
				columnID = indexes[1],
				column = $scope.content[rowID][columnID];
		var strClass = '';
		if ( column.span != 'auto' ) {
			strClass += ' span'+column.span;
		} else if ( $scope.content[rowID].length > 1 ) {
			var totalSpan = 12;
			var totalColumns = $scope.content[rowID].length;
			for ( var c in $scope.content[rowID] ) {
				if ( $scope.content[rowID].hasOwnProperty(c) && !isNaN($scope.content[rowID][c].span) ) {
					totalSpan = totalSpan - $scope.content[rowID][c].span;
					totalColumns = totalColumns - 1;
				}
			}
			var thisSpan = Math.floor(totalSpan / totalColumns);
			if ( columnID === $scope.content[rowID].length - 1 ) {
				thisSpan += ( totalSpan - ( thisSpan * totalColumns ) );
			}
			strClass += ' col-md-'+thisSpan;
		}
		return strClass;
	};
	$scope.renderHTML = function(content){
		return $sce.trustAsHtml(content);
	};
	$scope.renderAttributes = function(attributes) {
		str = '';
		for ( key in attributes ) {
			str += ' '+key+'="'+attributes[key]+'"';
		}
		return str;
	};
	$scope.insert = function(type) {
		var elementID = new Date().getTime();
		if ( $scope.gridType == 'row' ) {
			$scope.content.push([{'id':elementID,'type':type.type,'attributes':type.attributes,'content':type.content,'span':'auto','styles':{}}]);
		} else if ( 'duplicate' == type ) {
			var column = $scope.content[$scope.editor.rowID][$scope.editor.columnID],
				newColumn = {
					'id': elementID,
					'type': column.type.toString(),
					'attributes': {},
					'content': column.content.toString(),
					'span': 'auto',
					'styles': {}
				};
				for ( key in column.attributes ) {
					newColumn.attributes[key] = column.attributes[key];
				}
				for ( key in column.styles ) {
					newColumn.styles[key] = column.styles[key];
				}
			$scope.content[$scope.editor.rowID].push(newColumn);
		} else {
			$scope.content[$scope.editor.rowID].push({'id':elementID,'type':type.type,'attributes':type.attributes,'content':type.content,'span':'auto','styles':{}});
		}
		$scope.contentHasChanged = true;
	};
	$scope.insertSlide = function(slide) {
		var rowID = $scope.editor.rowID;
		var columnID = $scope.editor.columnID;
		$scope.content[rowID][columnID].attributes.slides.push(slide);
		jQuery('#ho_carousel_'+$scope.editor.elementID).carousel({
		  interval:false // remove interval for manual sliding
		});
		$scope.contentHasChanged = true;
	};
	$scope.changeGridType = function(gridType) {
		if ( typeof gridType == 'undefined' ) {
			gridType = $scope.defaultGridType;
		}
		$scope.gridType = gridType;
	};
	$scope.sortableOptions = {
		'containment': '#ho_content'
	};
	$scope.showMediaDialog = function(handler_input) {
		var targetInput = angular.element('#'+handler_input);
		targetInput.val('');
		
		file_frame = wp.media.frames.file_frame = wp.media({
			'title': 'Select an Image',
			'multiple': false
		});
		file_frame.off('select');
		file_frame.on( 'select', function() {
			var selections = file_frame.state().get( 'selection' );
			var json = selections.first().toJSON();
	 		targetInput.val(json.url).triggerHandler('change');
	 		file_frame.close();
	 	});
	 	file_frame.open();
	};

	$scope.saveContent = function() {
		$scope.editor.updateAllContent();
		$http({
			'method': 'POST',
			'url': document.getElementById('frmSave').action,
			'data': $httpParamSerializerJQLike({contents: JSON.stringify($scope.content)}),
			'headers': {'Content-Type': 'application/x-www-form-urlencoded'}
		}).then(function(){
			$scope.contentHasChanged =false;
		});
	}

	//TODO: add vertical and horizontal drag/resize handler
	//TODO: add config dialog for element and for app

	$http({
		'method': 'GET',
		'url': $scope.baseURL+'/index.php/page/contents/'+$scope.postID
	}).then(function(resp) {
		$scope.content = resp.data;
		$scope.editor.loaded = true;
		jQuery('[data-toggle=tooltip]').tooltip();
		//jQuery('#nav-add-row').tooltip('show');
	});
});

hoApp.directive('hoStyle', function(){
	return {
		restrict: 'AE',
		link: function(scope, el, attrs) {
			el.bind('change click', function(changeEvt){
				var key = attrs.hoStyle;
				switch( attrs.type ) {
					case 'checkbox' :
						var value = ( el.filter(':checked').length === 1 ) ? attrs.value : attrs.hoDefault;
						scope.editor.updateStyle(key, value);
						break;
					case 'button' :
						var value = attrs.value;
						scope.editor.updateStyle(key, value);
						break;
				}
			});
		}
	};
});

hoApp.directive('hoAttr', function(){
	return {
		restrict: 'AE',
		link: function(scope, el, attrs) {
			el.bind('change keyup', function(changeEvt){
				var originalValue = value = el.context.value;
				var selectedLink = jQuery('div[contenteditable] a.selected');
				if ( selectedLink.length === 1 ) {
					if ( el.context.id === 'link_button' ) {
						var elementValue = selectedLink.attr('class');
						value = elementValue.replace(/btn-(default|secondary|primary|success|info|warning|danger)/, '')+' '+value;
						if ( originalValue == '' ) {
							value = value.replace(/btn(-[a-z])?/g, '');
						} else if ( elementValue.indexOf('btn ') === -1 ) {
							value = 'btn ' + value;
						}
					}
					selectedLink.attr(attrs.hoAttr, value);
				}
			});
		}
	};
});

hoApp.directive('hoFile', function(){
	return {
		restrict: 'AE',
		link: function (scope, el, attrs) {
			el.bind('change', function(changeEvt){
				var imgSrc = el.context.value;
				switch ( attrs.hoFile ) {
					case 'jumbotron_bgimage' :
						scope.$apply(function(){
							scope.editor.updateStyle('background-image', "url('"+imgSrc+"')");
						});
						break;
					case 'slide_update' :
						scope.$apply(function(){
							scope.editor.updateSlide(attrs.index, 'image_src', imgSrc);
						});
						break;
					case 'slide_new' :
						scope.$apply(function(){
							scope.insertSlide({
								'image_src': imgSrc,
								'content': '<h1>Example headline.</h1><p>New slide contents go here</p><p><a class="btn btn-lg btn-primary" href="#" role="button">More</a></p>'
							});
						});
						break;
					case 'editor_image' :
						var selectedImage = jQuery('div[contenteditable] img.selected');
						if ( selectedImage.length === 1 ) {
							selectedImage.attr('src', imgSrc);
						} else {
							document.execCommand('insertHTML', false, '<img src="'+imgSrc+'" class="img-responsive" alt="" />');
						}
						break;
				}
			});
		}
	};
});
hoApp.directive('slideTo', function(){
	return {
		restrict: 'A',
		link: function(scope, el, attrs) {
			el.bind('click', function(){
				jQuery(attrs.target).carousel(parseInt(attrs.slideTo, 10));
			});
		}
	};
});
hoApp.directive('slide', function(){
	return {
		restrict: 'A',
		link: function(scope, el, attrs) {
			el.bind('click touchend', function(){
				var carousel = jQuery(attrs.href);
				var slide = carousel.find('li[data-slide-to].active');
				if ( slide.length === 1 ) {
					var index = parseInt(slide.data('slide-to'), 10);
					switch ( attrs.slide ) {
						case 'prev' :
							if ( index > 0 ) {
								carousel.carousel(index-1);
							}
							break;
						case 'next' :
							if ( index < slide.parent().children().length - 1 ) {
								carousel.carousel(index+1);
							}
							break;
					}
				}
			});
		}
	};
});
hoApp.directive('contenteditable', function(){
	return {
		restrict: 'A',
		link: function(scope, el, attrs) {
			el.bind('click touchend', function(evt){
				jQuery('#ho_content').find('.selected').removeClass('selected');
				if ( jQuery(evt.target).filter('img.img-responsive').length === 1 
					|| jQuery(evt.target).filter('a').length === 1 ) {
					jQuery(evt.target).addClass('selected');
				}
			});
		}
	};
});