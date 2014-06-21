function LCMFUpload(uploadWrap, fieldName, options) {
	this.uploadWrap = uploadWrap || "#uploader";
	this.fieldName = fieldName || "file";
	this.option = $.extend({
		pick : {
			id : this.uploadWrap + ' .filePicker',
			innerHTML : '点击选择文件'
		},
		dnd : this.uploadWrap + ' .dndArea',
		swf : LCMF.STATIC + '/util/uploader/Uploader.swf',
		server : LCMF.UPLOAD,
		accept : {
			title : 'Images',
			extensions : 'png,gif,jpg,jpeg,bmp',
			mimeTypes : 'image/*'
		},
		fileNumLimit : 50,
		fileSizeLimit : 512,
		fileSingleSizeLimit : 20
	}, options);
	
	// 当domReady的时候开始初始化
	this.$wrap = $(this.uploadWrap);
	// 添加“添加文件”的按钮
	this.$pick2 = this.$wrap.find('.filePicker2');
	// 图片容器
	this.$queue = $('.queueList .filelist', this.$wrap);
	// 状态栏，包括进度和控制按钮
	this.$statusBar = this.$wrap.find('.statusBar');
	// 文件总体选择信息。
	this.$info = this.$statusBar.find('.info');
	// 上传按钮
	this.$upload = this.$wrap.find('.uploadBtn');
	// 没选择文件之前的内容。
	this.$placeHolder = this.$wrap.find('.placeholder');
	this.$progress = this.$statusBar.find('.progress').hide();
	// 添加的文件数量
	this.fileCount = 0;
	// 添加的文件总大小
	this.fileSize = 0;
	// 优化retina, 在retina下这个值是2
	this.ratio = window.devicePixelRatio || 1,
	// 缩略图大小
	this.thumbnailWidth = 90 * this.ratio;
	this.thumbnailHeight = 90 * this.ratio;
	// 可能有pedding, ready, uploading, confirm, done.
	this.state = 'pedding';
	// 所有文件的进度信息，key为file id
	this.percentages = {};
	// 判断浏览器是否支持图片的base64
	this.isSupportBase64 = (function() {
		var data = new Image();
		var support = true;
		data.onload = data.onerror = function() {
			if (this.width != 1 || this.height != 1) {
				support = false;
			}
		}
		data.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
		return support;
	} )();
	// 检测是否已经安装flash，检测flash的版本
	this.flashVersion = (function() {
		var version;

		try {
			version = navigator.plugins['Shockwave Flash'];
			version = version.description;
		} catch ( ex ) {
			try {
				version = new ActiveXObject('ShockwaveFlash.ShockwaveFlash').GetVariable('$version');
			} catch ( ex2 ) {
				version = '0.0';
			}
		}
		version = version.match(/\d+/g);
		return parseFloat(version[0] + '.' + version[1], 10);
	} )();
	this.supportTransition = (function() {
		var s = document.createElement('p').style, r = 'transition' in s || 'WebkitTransition' in s || 'MozTransition' in s || 'msTransition' in s || 'OTransition' in s;
		s = null;
		return r;
	})();

	if (!WebUploader.Uploader.support('flash') /*&& WebUploader.browser.ie*/ ) {

		// flash 安装了但是版本过低。
		if (this.flashVersion) {
			(function(container) {
				window['expressinstallcallback'] = function(state) {
					switch(state) {
						case 'Download.Cancelled':
							alert('您取消了更新！')
							break;

						case 'Download.Failed':
							alert('安装失败')
							break;

						default:
							alert('安装已成功，请刷新！');
							break;
					}
					delete window['expressinstallcallback'];
				};

				var swf = './expressInstall.swf';
				// insert flash object
				var html = '<object type="application/' + 'x-shockwave-flash" data="' + swf + '" ';

				if (WebUploader.browser.ie) {
					html += 'classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" ';
				}

				html += 'width="100%" height="100%" style="outline:0">' + '<param name="movie" value="' + swf + '" />' + '<param name="wmode" value="transparent" />' + '<param name="allowscriptaccess" value="always" />' + '</object>';

				container.html(html);

			})(this.$wrap);

			// 压根就没有安转。
		} else {
			this.$wrap.html('<a href="http://www.adobe.com/go/getflashplayer" target="_blank" border="0"><img alt="get flash player" src="http://www.adobe.com/macromedia/style_guide/images/160x41_Get_Flash_Player.jpg" /></a>');
		}

		return;
	} else if (!WebUploader.Uploader.support()) {
		alert('Web Uploader 不支持您的浏览器！');
		return;
	}

	// 实例化
	this.option.fileSizeLimit = (this.option["fileSizeLimit"] || 0) * 1024 * 1024,
	this.option.fileSingleSizeLimit = (this.option["fileSingleSizeLimit"] || 0) * 1024 * 1024
	this.uploader = WebUploader.create($.extend({
		chunked : false,
		chunkSize : 512 * 1024,
		// 禁掉全局的拖拽功能。这样不会出现图片拖进页面的时候，把图片打开。
		disableGlobalDnd : true,
		// 使用flash
		// runtimeOrder: 'flash'
	}, this.option));

	// 添加“添加文件”的按钮
	if (this.option.fileNumLimit > 1 && $(this.uploadWrap + ' .filePicker2')) {
		this.uploader.addButton({
			id : this.uploadWrap + ' .filePicker2',
			label : '继续添加'
		});
	}
}

LCMFUpload.prototype.init = function() {
	var self = this;
	
	// 拖拽时不接受 js, txt 文件。
	self.uploader.on('dndAccept', function(items) {
		var denied = false, len = items.length, i = 0,
		// 修改js类型
		unAllowed = 'text/plain;application/javascript ';

		for (; i < len; i++) {
			// 如果在列表里面
			if (~unAllowed.indexOf(items[i].type)) {
				denied = true;
				break;
			}
		}

		return !denied;
	});

	// uploader.on('filesQueued', function() {
	//     uploader.sort(function( a, b ) {
	//         if ( a.name < b.name )
	//           return -1;
	//         if ( a.name > b.name )
	//           return 1;
	//         return 0;
	//     });
	// });

	self.uploader.on('ready', function() {
		window.uploader = self.uploader;
	});

	// 当有文件添加进来时执行，负责view的创建
	function addFile(file) {
		var $li = $('<li id="' + file.id + '" title="' + file.name + '&#10;' + WebUploader.formatSize(file.size) + '">' + '<p class="title">' + file.name + '</p>' + '<p class="imgWrap"></p>' + '<p class="progress"><span></span></p>' + '</li>'), 
		$btns = $('<div class="file-panel">' + '<span class="cancel">删除</span>' + '<span class="rotateRight">向右旋转</span>' + '<span class="rotateLeft">向左旋转</span></div>').appendTo($li), 
		$prgress = $li.find('p.progress span'), 
		$wrap = $li.find('p.imgWrap'), 
		$info = $('<p class="error"></p>'),
		showError = function(code) {
			switch( code ) {
				case 'exceed_size':
					text = '文件大小超出';
					break;

				case 'interrupt':
					text = '上传暂停';
					break;

				default:
					text = '上传失败';
					break;
			}

			$info.text(text).appendTo($li);
		};

		if (file.getStatus() === 'invalid') {
			showError(file.statusText);
		} else {
			// @todo lazyload
			$wrap.text('预览中');
			self.uploader.makeThumb(file, function(error, src) {
				var img;

				if (error) {
					$wrap.text('不能预览');
					return;
				}

				if (self.isSupportBase64) {
					img = $('<img src="' + src + '">');
					$wrap.empty().append(img);
				} else {
					$.ajax(LCMF.UPLOAD + '/preview', {
						method : 'POST',
						data : src,
						dataType : 'json'
					}).done(function(response) {
						if (response.result) {
							img = $('<img src="' + response.result + '">');
							$wrap.empty().append(img);
						} else {
							$wrap.text("预览出错");
						}
					});
				}
			}, self.thumbnailWidth, self.thumbnailHeight);

			self.percentages[file.id] = [file.size, 0];
			file.rotation = 0;
		}

		file.on('statuschange', function(cur, prev) {
			if (prev === 'progress') {
				$prgress.hide().width(0);
			}

			// 成功
			if (cur === 'error' || cur === 'invalid') {
				showError(file.statusText);
				self.percentages[ file.id ][1] = 1;
			} else if (cur === 'interrupt') {
				showError('interrupt');
			} else if (cur === 'queued') {
				self.percentages[ file.id ][1] = 0;
			} else if (cur === 'progress') {
				$info.remove();
				$prgress.css('display', 'block');
			}
			
			$li.removeClass('state-' + prev).addClass('state-' + cur);
		});

		$li.on('mouseenter', function() {
			$btns.stop().animate({
				height : 30
			});
		});

		$li.on('mouseleave', function() {
			$btns.stop().animate({
				height : 0
			});
		});

		$btns.on('click', 'span', function() {
			var index = $(this).index(), deg;

			switch ( index ) {
				case 0:
					self.uploader.removeFile(file);
					return;

				case 1:
					file.rotation += 90;
					break;

				case 2:
					file.rotation -= 90;
					break;
			}

			if (self.supportTransition) {
				deg = 'rotate(' + file.rotation + 'deg)';
				$wrap.css({
					'-webkit-transform' : deg,
					'-mos-transform' : deg,
					'-o-transform' : deg,
					'transform' : deg
				});
			} else {
				$wrap.css('filter', 'progid:DXImageTransform.Microsoft.BasicImage(rotation=' + (~~((file.rotation / 90) % 4 + 4) % 4) + ')');
				// use jquery animate to rotation
				// $({
				//     rotation: rotation
				// }).animate({
				//     rotation: file.rotation
				// }, {
				//     easing: 'linear',
				//     step: function( now ) {
				//         now = now * Math.PI / 180;

				//         var cos = Math.cos( now ),
				//             sin = Math.sin( now );

				//         $wrap.css( 'filter', "progid:DXImageTransform.Microsoft.Matrix(M11=" + cos + ",M12=" + (-sin) + ",M21=" + sin + ",M22=" + cos + ",SizingMethod='auto expand')");
				//     }
				// });
			}

		});

		$li.appendTo(self.$queue);
	}

	// 负责view的销毁
	function removeFile(file) {
		var $li = $('#' + file.id);
		delete self.percentages[file.id];
		updateTotalProgress();
		$li.off().find('.file-panel').off().end().remove();
	}

	function updateTotalProgress() {
		var loaded = 0, total = 0, spans = self.$progress.children(), percent;

		$.each(self.percentages, function(k, v) {
			total += v[0];
			loaded += v[0] * v[1];
		});

		percent = total ? loaded / total : 0;

		spans.eq(0).text(Math.round(percent * 100) + '%');
		spans.eq(1).css('width', Math.round(percent * 100) + '%');
		self.updateStatus();
	}

	self.uploader.onUploadProgress = function(file, percentage) {
		var $li = $('#' + file.id), $percent = $li.find('.progress span');

		$percent.css('width', percentage * 100 + '%');
		self.percentages[ file.id ][1] = percentage;
		updateTotalProgress();
	};

	self.uploader.onFileQueued = function(file) {
		self.fileCount++;
		self.fileSize += file.size;

		if (self.fileCount > 0) {
			self.$placeHolder.addClass('element-invisible');
			self.$statusBar.show();
		}

		addFile(file);
		self.setState('ready');
		updateTotalProgress();
	};

	self.uploader.onFileDequeued = function(file) {
		self.fileCount--;
		self.fileSize -= file.size;

		if (!self.fileCount) {
			self.setState('pedding');
		}

		removeFile(file);
		updateTotalProgress();
	};

	self.uploader.onUploadSuccess = function(file, response) {
		var $li = $('#' + file.id);

		$li.find("span.success,p.error").remove();

		if (response["status"] == "1") {
			$success = $('<span class="success"></span>');
			$success.html('<input type="hidden" name="' + self.fieldName + '" value=\'' + JSON.stringify(response.info) + '\'>').appendTo($li);
		} else {
			$error = $('<p class="error"></p>');
			$error.attr("title", response["info"]).html(response["info"] || "未知错误").appendTo($li);
		}
	}

	self.uploader.on('all', function(type) {
		var stats;
		switch( type ) {
			case 'uploadFinished':
				self.setState('confirm');
				break;

			case 'startUpload':
				self.setState('uploading');
				break;

			case 'stopUpload':
				self.setState('paused');
				break;

		}
	});

	self.uploader.onError = function(code) {
		alert('Eroor: ' + code);
	};

	self.$upload.on('click', function() {
		if ($(this).hasClass('disabled')) {
			return false;
		}
		if (!self.uploader.getStats()["queueNum"]) {
			return false;
		}
		
		if (self.state === 'ready') {
			self.uploader.upload();
		} else if (self.state === 'paused') {
			self.uploader.upload();
		} else if (self.state === 'uploading') {
			self.uploader.stop();
		}
	});

	self.$info.on('click', '.retry', function() {
		self.uploader.retry();
	});

	self.$info.on('click', '.ignore', function() {
		alert('todo');
	});

	self.$upload.addClass('state-' + self.state);
	updateTotalProgress();

}

LCMFUpload.prototype.load = function(files) {
	var self = this;
	
	// 当有文件添加进来时执行，负责view的创建
	function addFile(file) {
		var $li = $('<li id="' + file.hash + '" title="' + file.name + '&#10;' + WebUploader.formatSize(file.size) + '">' + '<p class="title">' + file.name + '</p>' + '<p class="imgWrap"></p>' + '</li>'), 
		$btns = $('<div class="file-panel">' + '<span class="cancel">删除</span>' + '<span class="rotateRight">向右旋转</span>' + '<span class="rotateLeft">向左旋转</span></div>').appendTo($li), 
		$prgress = $li.find('p.progress span'), 
		$wrap = $li.find('p.imgWrap');
				
		// 已上传标识
		$success = $('<span class="success"></span>');
		$success.html('<input type="hidden" name="' + self.fieldName + '" value=\'' + JSON.stringify(file) + '\'>').appendTo($li);
		
		// 图片预览
		if ($.inArray(file.extension, ["gif", "jpg", "jpeg", "png", "bmp"]) != -1) {
			$wrap.empty().append('<img src="' + file.savepath + '">');
			file.rotation = 0;
		}else{
			$wrap.text('不能预览');
		}

		$li.on('mouseenter', function() {
			$btns.stop().animate({
				height : 30
			});
		});

		$li.on('mouseleave', function() {
			$btns.stop().animate({
				height : 0
			});
		});

		$btns.on('click', 'span', function() {
			var index = $(this).index(), deg;

			switch ( index ) {
				case 0:
					self.fileCount--;
					self.fileSize -= file.size;
					if (!self.fileCount) {
						self.setState('pedding');
					}else{
						self.updateStatus();
					}
					$li.off().find('.file-panel').off().end().remove();
					return;

				case 1:
					file.rotation += 90;
					break;

				case 2:
					file.rotation -= 90;
					break;
			}

			if (supportTransition) {
				deg = 'rotate(' + file.rotation + 'deg)';
				$wrap.css({
					'-webkit-transform' : deg,
					'-mos-transform' : deg,
					'-o-transform' : deg,
					'transform' : deg
				});
			} else {
				$wrap.css('filter', 'progid:DXImageTransform.Microsoft.BasicImage(rotation=' + (~~((file.rotation / 90) % 4 + 4) % 4) + ')');
			}

		});

		$li.appendTo(self.$queue);
	}
	
	// 循环添加文件
	if (!$.isArray(files)) {
		files = [files];
	}
	
	$.each(files, function(i, file) {
		if ($.isPlainObject(file)) {
			self.fileCount++;
			self.fileSize += file.size;
			addFile(file);
		}
	});
	
	if (self.fileCount > 0) {
		self.$placeHolder.addClass('element-invisible');
		self.$statusBar.show();
		self.updateStatus();
	}
}

LCMFUpload.prototype.updateStatus = function() {
	var self = this, text = '', stats;

	if (self.state === 'ready') {
		text = '选中' + self.fileCount + '个文件，共' + WebUploader.formatSize(self.fileSize);
	} else if (self.state === 'confirm') {
		stats = self.uploader.getStats();
		if (stats.uploadFailNum) {
			text = '已成功上传' + stats.successNum + '个文件，其中' + stats.uploadFailNum + '个上传失败，<a class="retry" href="#">重新上传</a>失败文件或<a class="ignore" href="#">忽略</a>'
		}

	} else {
		stats = self.uploader.getStats();
		text = '共' + self.fileCount + '个（' + WebUploader.formatSize(self.fileSize) + '），本次上传' + stats.successNum + '个';

		if (stats.uploadFailNum) {
			text += '，失败' + stats.uploadFailNum + '个';
		}
	}

	self.$info.html(text);
}

LCMFUpload.prototype.setState = function(val) {
	var self = this, file, stats;
	/*if (val === self.state) {
		return;
	}*/

	self.$upload.removeClass('state-' + self.state);
	self.$upload.addClass('state-' + val);
	self.state = val;

	switch ( self.state ) {
		case 'pedding':
			self.$placeHolder.removeClass('element-invisible');
			self.$queue.hide();
			self.$statusBar.addClass('element-invisible');
			self.uploader.refresh();
			break;

		case 'ready':
			self.$placeHolder.addClass('element-invisible');
			self.$pick2.removeClass('element-invisible');
			self.$queue.show();
			self.$statusBar.removeClass('element-invisible');
			self.uploader.refresh();
			break;

		case 'uploading':
			self.$pick2.addClass('element-invisible');
			self.$progress.show();
			self.$upload.text('暂停上传');
			break;

		case 'paused':
			self.$progress.show();
			self.$upload.text('继续上传');
			break;

		case 'confirm':
			self.$progress.hide();
			self.$upload.text('开始上传').addClass('disabled');

			stats = self.uploader.getStats();
			if (stats.successNum && !stats.uploadFailNum) {
				this.setState('finish');
				return;
			}
			break;
		case 'finish':
			stats = self.uploader.getStats();
			if (stats.successNum) {
				//
			} else {
				// 没有成功的图片，重设
				self.state = 'done';
				location.reload();
			}
			self.$pick2.removeClass('element-invisible');
			self.$upload.removeClass('disabled');
			break;
	}

	this.updateStatus();
}