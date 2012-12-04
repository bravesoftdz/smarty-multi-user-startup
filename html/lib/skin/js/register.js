$(function(){
   if (document.frmRegister){
        var orglogin = $(document.frmRegister.org_login);
        var orgname = $(document.frmRegister.org_name);
        orglogin.change(function(){
           $(this).attr('noautomodify', true);
        });
        
        var makeOrgShortname = function(){
           if (orglogin.attr('noautomodify') == undefined){
              var lcval = orgname.val().toLowerCase();
              lcval = lcval.replace(/ |-/g,"_");
              orglogin.val(lcval);
           }
        };
        
        orgname.on('keyup click', makeOrgShortname);
        
        
       //onsubmit
       $(document.frmRegister).submit(function(){
          if (!$('input[name=plan]:checked', this).val()){
             alert('Please choose a plan');
             return false;
          }
       });        
   }
   

});