	<script src="/assets/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("[rel=tooltip-left]").tooltip({ placement: 'left'});
			$("[rel=tooltip-bottom]").tooltip({ placement: 'bottom'});
			$('input[rel="tooltip-manual-bottom"]').tooltip({ placement: 'bottom', trigger: 'manual'}).tooltip('show');
			$('input[rel="tooltip-manual-bottom"]').focus(function(){
				$(this).tooltip('destroy');
			});
		});
	</script>
</body>
</html>