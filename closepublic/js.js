function SortProducts(t){href=window.location.toString().split("?"),new_href="?sort="+t,href.length>1&&(tmp=href[1].split("&"),tmp.length>1&&(new_href="?"+tmp[0]+"&"+tmp[1]+"&sort="+t)),window.location=new_href}function checkfilters(){var t=[];$(".optid").each(function(o,e){var i=$(this).data("opid");t.push(i)});var o=$("#catid").html(),e=t.join(";");e&&catid&&$.ajax({url:"/ajax/getfilterprods/"+o+"/"+e,dataType:"json",type:"GET",error:function(t,o,e){console.log("check filter error")},success:function(t,o,e){if(t)for(var i in t){console.log(t[i]),$(".optid").data("opid",t[i]).parent("li").hide()}}})}$(document).ready(function(){$(".smallmenu_icon").click(function(t){$(this).parent().find($(".menu__dropsmall")).toggle(),t.preventDefault()}),"trademag.com.ua/"==location.href.substr(7)||"trademag.com.ua/"==location.href||"trademag.com.ua"==location.href?($(".side").css("display","block"),$(".side").css("position","static"),$(".side").css("opacity","0.99")):($(".catmainbut").on("mouseenter click",function(t){$(".side").toggle(),t.preventDefault()}),$(".catmainbutmob").on("click",function(t){$(".side").toggle(),t.preventDefault()}),$(".side").on("mouseleave",function(t){$(".side").hide()}),$(document).on("mousemove click",function(t){var o=$(t.target);"block"===$(".side").css("display")&&(o.closest(".parent__menublock").length||$(".side").hide())})),$(document).on("mousemove click",function(t){var o=$(t.target);"block"===$(".cart_sub").css("display")&&(o.closest(".parent__cartblock").length||$(".cart_sub").hide())}),$("#searchprod").keyup(function(){var t=$(this).val(),o=document.location.href.split("/")[0]+"/product/";$("#listProds").empty(),0!=t.length&&1!=t.length||$(".toggled_block").hide(),t.length>1&&$.ajax({url:"/ajax/getindexprods/"+t,dataType:"json",type:"GET",error:function(t,o,e){alert("get prod error")},success:function(t,e,i){if(t){$(".toggled_block").show();for(var n in t)$("#listProds").append('<li class="li_link"><a href="'+o+t[n].path+'">'+t[n].title+"</a></li>")}else $(".toggled_block").hide()}})}),$(document).click(function(t){$(t.target).closest(".parent_block").length||$(".toggled_block").hide(),t.stopPropagation()})}),jQuery(function(){Sort.sort()}),Sort=function(){return{sort:function(){jQuery(".sort-list").each(function(){jQuery(this).hover(function(){jQuery(this).addClass("sort-list-hov"),jQuery(this).find(".sort-drop").show()},function(){jQuery(this).removeClass("sort-list-hov"),jQuery(this).find(".sort-drop").hide()})})}}}();