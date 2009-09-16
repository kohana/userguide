$(document).ready(function()
{
	// Translation selector
	$('#topbar form select').change(function()
	{
		$(this).parents('form').submit();
	});

	// Striped tables
	$('#content tbody tr:even').addClass('alt');
});
