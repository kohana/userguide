$(document).ready(function()
{
	$('#topbar form :input').change(function()
	{
		$(this).parents('form').submit();
	});
});
