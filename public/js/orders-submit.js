// <script language="javascript" type="text/javascript" src="/js/jquery-1.8.2.min.js"></script>
//   <script language="javascript" type="text/javascript" >
             function sb(ev,id){
               var urlf="orders";
            if (ev=="towork") {
                    urlf="orders/towork/";           
            }
            if (ev=="delete") {
                    urlf="orders/delete/";   
            }
            if (ev=="orderdone") {
                    urlf="workorders/orderdone/";           
            }
            if (ev=="deletedone") {
                    urlf="workorders/delete/";   
            }
                        
                           $("#adminForm" + id).attr("action",urlf);  //заменяем в форме атрибут action
                           $("#adminForm" + id).submit();  //отправляем форму
                          
                     }
                     
   

