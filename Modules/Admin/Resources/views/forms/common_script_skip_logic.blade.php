@push('script')
   <script type="text/javascript">
   	function disabled_opposite(stepid,class_opposite_checkbox,index,current_class){

   		if($('.'+current_class+stepid+'_'+index).is(':checked')){
   			$('.'+class_opposite_checkbox+stepid+'_'+index).attr('disabled',true);
   		}else{
   			$('.'+class_opposite_checkbox+stepid+'_'+index).attr('disabled',false);
   		}
   	}
   </script>
@endpush   	