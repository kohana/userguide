$(document).ready(function()
{
	// Translation selector
	$('#topbar form select').change(function()
	{
		$(this).parents('form').submit();
	});

	// Striped tables
	$('#content tbody tr:even').addClass('alt');

	// Toggle menus
	$('#menu ol > li').each(function()
	{
		var link = $(this).find('strong');
		var menu = $(this).find('ul');
		// var togg = $('<span class="toggle">[ + ]</span>');

		var open  = function()
		{
			// togg.html('[ &ndash; ]');
			menu.stop().slideDown();
		};

		var close = function()
		{
			// togg.html('[ + ]');
			menu.stop().slideUp();
		};

		if (menu.find('a[href="'+ window.location.pathname +'"]').length)
		{
			// Currently active menu
			link.toggle(close, open);
		}
		else
		{
			menu.slideUp(0);
			link.toggle(open, close);
		}
	});

	// Collapsable class contents
	$('#content #toc').each(function()
	{
		var header  = $(this);
		var content = $('#content div.toc').hide();

		$('<span class="toggle">[ + ]</span>').toggle(function()
		{
			$(this).html('[ &ndash; ]');
			content.stop().slideDown();
		},
		function()
		{
			$(this).html('[ + ]');
			content.stop().slideUp();
		})
		.appendTo(header);
	});

	// Sticky menu position
	$('#menu').each(function()
	{
		var menu = $(this);
		var win  = $(window);
		var otop = menu.offset().top;

		win.scroll(function()
		{
			wtop = win.scrollTop();
			menu.css('margin-top', wtop > otop ? wtop - otop : 0)
		});
	});
});
