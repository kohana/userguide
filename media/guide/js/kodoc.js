$(document).ready(function()
{

// Syntax highlighter

	$('pre:not(.debug) code').each(function()
	{
		$(this).addClass('brush: php, class-name: highlighted');
	});

	SyntaxHighlighter.config.tagName = 'code';
	// Don't show the toolbar or line-numbers.
	SyntaxHighlighter.defaults.gutter = false;
	SyntaxHighlighter.all();
	
	// Any link that has the current page as its href should be class="current"
	$('a[href="'+ window.location.pathname +'"]').addClass('current');
	
// Breadcrumbs magic

	last = $('#kodoc-nav .breadcrumb-last');
	li = $('<li></li>');
	
	$('#kodoc-menu li').has('a.current').each(function()
	{
		// Only add if we aren't already on that page
		if ($(this).find(':first-child').first().attr('href') != window.location.pathname)
		{
			// Clone the empty li, set it's html as the link or span, then prepend it to the last breadcrumb item
			last.before(li.clone().html($(this).find(':first-child').first().clone()));
		}
	});
	
	// Now kill the duplicate link for the current page
	//last.prev().remove();

// Collapsing menus

	$('#topics li:has(li)').each(function()
	{
		var $this = $(this);
		var toggle = $('<span class="toggle"></span>');
		var menu = $this.find('>ul,>ol');

		toggle.click(function()
		{
			if (menu.is(':visible'))
			{
				menu.stop(true, true).slideUp('fast');
				toggle.html('+');
			}
			else
			{
				menu.stop(true, true).slideDown('fast');
				toggle.html('&ndash;');
			}
		});

		$this.find('>span').click(function()
		{
			// Menu without a link
			toggle.click();
		});

		if ( ! $this.is(':has(a.current)'))
		{
			menu.hide();
		}

		toggle.html(menu.is(':visible') ? '&ndash;' : '+').prependTo($this);
	});

	$('#topics').each(function()
	{
		var $this = $(this);
		var $pane = $(window);
		var $body = $('#body');
		var _base = $this.offset().top;

		$pane.scroll(function()
		{
			var _max = $body.height() - $this.height();
			var _scroll = Math.max(0, $pane.scrollTop() - _base);

			if (_scroll < _max)
			{
				$this.stop(true, false).animate({
					marginTop: _scroll
				}, 200);
			}
		});
	});

// Show source links

	$('#kodoc-main .method-source').each(function()
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

// "Link to this" link that appears when you hover over a header

	$('#kodoc-main')
		.find('h1[id],h2[id],h3[id],h4[id],h5[id],h6[id]')
		.append(function(index, html){
			return '<a href="#' + $(this).attr('id') + '" class="permalink">link to this</a>';
		});

// Table of contents for userguide pages
	
	// When the show/hide link for the page toc is clicked, toggle visibility
	$('#kodoc-toc-toggle').click(function()
	{
		if ($('#kodoc-page-toc-content').is(':visible'))
		{
			// Hide the contents
			$(this).html('show');
			$('#kodoc-page-toc').addClass('closed').removeClass('open')
			$('#kodoc-page-toc-content').hide();
			$.cookie('kodoc-toc-show',"false");
		}
		else
		{
			// Show the contents
			$(this).html('hide');
			$('#kodoc-page-toc').addClass('open').removeClass('closed')
			$('#kodoc-page-toc-content').show();
			$.cookie('kodoc-toc-show',"true");
		}
	});
	
	// If the cookie says to hide the toc, hide it by clicking the toggle
	if ($.cookie('kodoc-toc-show') == "false")
	{
		$('#kodoc-toc-toggle').click();
	}

});
