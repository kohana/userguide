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
		var link = $(this).find('strong a');
		var menu = $(this).find('ul');
		var togg = $('<span class="toggle">[ + ]</span>');

		var open  = function()
		{
			$(this).html('[ &ndash; ]');
			menu.stop().slideDown();
		};

		var close = function()
		{
			$(this).html('[ + ]');
			menu.stop().slideUp();
		};

		if ($(this).find('a[href="'+ window.location.pathname +'"]').length)
		{
			togg.html('[ &ndash; ]')
			.toggle(close, open);
		}
		else
		{
			menu.hide();
			togg.toggle(open, close);
		}

		togg.appendTo(link);
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
});
