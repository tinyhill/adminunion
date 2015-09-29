/**
 * @author tinyhill@163.com
 * @file global.js
 */
/**
 * 定义全局命名空间
 * @var {Object} AU
 */
var AU = window.AU || {};

	/**
	 * 定义通用模块
	 * @namespace AU.Common
	 */
	AU.Common = {

		/**
		 * 客户端 IP 地址信息
		 * @method _getIpAddress
		 * @param {String} target 目标节点
		 */
		_getIpAddress : function (target) {

			$.ajax({
				dataType: 'json',
				type: 'get',
				url: '/api.php',
				data: 'c=ip&m=address&d=' + $(target).attr('data-ip'),
				success: function (data) {
					$(target).html('您的 IP&nbsp;&nbsp;<b style="color:#e00;">' + data[0] + '</b>&nbsp;&nbsp;来自&nbsp;&nbsp;' + data[1]);
				}
			});

		},

		/**
		 * 设本站为首页
		 * @method _setHomepage
		 * @param {String} url 页面地址
		 * @param {String} target 事件源元素
		 */
		_setHomepage : function (url, target) {

			try {
				target.style.behavior = 'url(#default#homepage)';
				target.setHomePage(url);
			} catch (e) {

				if (window.netscape) {
					try {
						netscape.security.PrivilegeManager.enablePrivilege('UniversalXPConnect');
					} catch (e) {
						alert('抱歉，此操作被浏览器拒绝！\n\n请在浏览器地址栏输入 "about:config" 并回车然后将 [signed.applets.codebase_principal_support] 设置为 "true"');
					}
				} else {
					alert('抱歉，您所使用的浏览器无法完成此操作。\n\n您需要手动将 "' + url + '" 设置为首页。');
				}
			}

		},

		/**
		 * 添加到收藏夹
		 * @method _addFavorite
		 * @param {String} url 页面地址
		 * @param {String} title 页面标题
		 */
		_addFavorite : function (url, title) {

			try {
				window.external.addFavorite(url, title);
			} catch (e){
				try {
					window.sidebar.addPanel(title, url, '');
				} catch (e) {
					alert('请按 Ctrl+D 组合键添加到收藏夹');
				}
			}

		},

		/**
		 * 输入提示信息
		 * @method _placeholder
		 * @param {Node} label 提示所用的<label>节点
		 * @param {Node} target 作用到的目标字段节点
		 */
		_placeholder : function (label, target) {

			if ($.trim(target.val()) == '') {
				label.removeClass('hidden');
			}

			target.focus(function () {

				label.addClass('hidden');

			});

			target.blur(function () {

				if ($.trim(target.val()) == '') {
					label.removeClass('hidden');
				}

			});

		},

		/**
		 * 初始化通用模块
		 * @method init
		 * @param {JSON} o 页面配置参数
		 */
		init : function (o) {

			var self = this;

			//初始化客户端 IP 地址信息
			this._getIpAddress('#ip-address');

			//绑定设为首页事件
			$('#set-homepage').click(function () {
				self._setHomepage('http://www.adminunion.com/', $(this));
			});

			//绑定添加收藏事件
			$('#add-favorite').click(function () {
				self._addFavorite('http://www.adminunion.com/', '站长军团，我的站长我的站！');
			});

			//初始化输入提示信息
			this._placeholder($('.s label'), $('.s input'));

		}

	};