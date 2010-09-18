$(document).ready(function()
{

	// Syntax highlighter
	$('pre:not(.debug) code').each(function()
	{
		$(this).addClass('brush: php');
	});

	SyntaxHighlighter.config.tagName = 'code';
	// Don't show the toolbar or line-numbers.
	SyntaxHighlighter.defaults.toolbar = false;
	SyntaxHighlighter.defaults.gutter = false;
	SyntaxHighlighter.all();
	
	// IE is stupid
	if ( ! $.browser.msie) {
		
		
		// Api browser, clickable Titles
		var categories = $("#menu li").find('span');
		// When you click the arrow, hide or show the menu
		categories.click(function()
		{
			var menu = $(this).next('ol');
			var link = $(this).parent();
			if (menu.is(':visible'))
			{
				// hide menu
				menu.stop(true,true).slideUp('fast');
				link.addClass('toggle-close').removeClass('toggle-open');
			}
			else
			{
				// show menu
				menu.stop(true,true).slideDown('fast');
				link.addClass('toggle-open').removeClass('toggle-close');
			}
			return
		});
		
		
		// Collapsable menus
		$('#menu li').has('li').each(function()
		{
			var link = $(this);
			var menu = link.find('ul:first, ol:first');
			var togg = $('<a class="menu-toggle"></a>');
			link.prepend(togg);
			
			// When you click the arrow, hide or show the menu
			togg.click(function()
			{
				if (menu.is(':visible'))
				{
					// hide menu
					menu.stop(true,true).slideUp('fast');
					link.addClass('toggle-close').removeClass('toggle-open');
				}
				else
				{
					// show menu
					menu.stop(true,true).slideDown('fast');
					link.addClass('toggle-open').removeClass('toggle-close');
				}
				return
			});
			
			// Hide all menus that do not contain the active link
			menu.not(':has(a[href="'+ window.location.pathname +'"])').hide();
			
			// If the current page is a parent, then show the children
			link.has('a[href="'+ window.location.pathname +'"]').find('ul:first, ol:first').show();
			
			// Add the classes to make the arrows show
			if (menu.is(':visible'))
			{
				link.addClass('toggle-open');
			}
			else
			{
				link.addClass('toggle-close');
			}
		});
	}
	
	// Any link that has the current page as its href should be class="current"
	$('a[href="'+ window.location.pathname +'"]').addClass('current');

	/*
	// Collapsable class contents
	$('#main #toc').each(function()
	{
		var header  = $(this);
		var content = $('#main div.toc').hide();

		$('<span class="toggle">[ + ]</span>').toggle(function()
		{
			$(this).html('[ &ndash; ]');
			content.stop(true, true).slideDown();
		},
		function()
		{
			$(this).html('[ + ]');
			content.stop(true, true).slideUp();
		})
		.appendTo(header);
	});
	*/
	

	// Show source links
	$('#main .method-source').each(function()
	{
		var self = $(this);
		var togg = $(' <a class="sourcecode-toggle">[show]</a>').appendTo($('h4', self));
		var code = self.find('pre').hide();

		togg.toggle(function()
		{
			togg.html('[hide]');
			code.stop(true, true).slideDown();
		},
		function()
		{
			togg.html('[show]');
			code.stop(true, true).slideUp();
		});
	});

	// "Link to" headers
	$('#main')
		.find('h1[id],h2[id],h3[id],h4[id],h5[id],h6[id]')
		.append(function(index, html){
			return '<a href="#' + $(this).attr('id') + '" class="permalink">link to this</a>';
		});

});
