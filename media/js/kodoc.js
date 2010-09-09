$(document).ready(function()
{
	
	// Translation selector
	$('#topbar form select').change(function()
	{
		$(this).parents('form').submit();
	});

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

	// Striped tables
	$('#content tbody tr:even').addClass('alt');
	
	$('#menu li').has('li').each(function()
	{
		var link = $(this);
		var menu = link.find('ul:first, ol:first');
		var togg = $('<a class="menu-toggle"></a>');
		link.prepend(togg);
		
		// When you click the arrow, hide or show the mune
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
		})
		
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
	
	$('a[href="'+ window.location.pathname +'"]').addClass('current');


	// Collapsable class contents
	$('#content #toc').each(function()
	{
		var header  = $(this);
		var content = $('#content div.toc').hide();

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
	
	// Show source links
	$('#content .method-source').each(function()
	{
		var self = $(this);
		var togg = $('<span class="toggle">+</span>').appendTo($('h5', self));
		var code = self.find('pre').hide();

		self.toggle(function()
		{
			togg.html('&ndash;');
			code.stop(true, true).slideDown();
		},
		function()
		{
			togg.html('+');
			code.stop(true, true).slideUp();
		});
	});
});
