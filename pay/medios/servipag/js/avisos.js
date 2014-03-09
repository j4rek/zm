var TOTAL=0;
$().ready(function(){
	
		$("input[name|='destacado']").click(function(){
			TOTAL=parseInt($("input[name|='MONTO']").val());
			if($(this).is(":checked"))
			{
				if($("input[name|='MONTO']").val()!=1000)
				{
					TOTAL=TOTAL + 2000;
					$("input[name|='MONTO']").val(TOTAL);
				}else{
					TOTAL=3000;
					$("input[name|='MONTO']").val(TOTAL);
				}
			}
			else
			{
				TOTAL=1000;
				$("input[name|='MONTO']").val(TOTAL);	
			}
		});
});
/**********************************/

