$(function(){
   if (document.frmRegister){
       //onsubmit
       $(document.frmRegister).submit(function(){
          if (!$('input[name=plan]:checked', this).val()){
             alert('Please choose a plan');
             return false;
          }
       });        
   }
});