var click_id="";//记录点击
var zsc_count = 0;//记录编辑

//查看详细信息
function zsc_show(id){
	if(!flag){
		$('#add_zsc div').remove();
		$('#add_zsc').hide();
		flag = true;
	}
	var zsc_class = $('#zsc_btninfo'+id).attr('class');
	if(zsc_class!='add'){
		$('#zsc_btninfo'+id).attr('class','add');
		click_id="";
		$('#zsc_content_'+id+' p').show();
		$('#zsc_content_'+id+' div').remove();
	}else{
		$('#zsc_btninfo'+id).attr('class','add dec');
		if(click_id!=""){
			$('#zsc_content_'+click_id+' p').show();
			$('#zsc_content_'+click_id+' div').remove();
			$('#zsc_btninfo'+click_id).attr('class','add');
		}
		click_id = id;
		var zsc_id = id;
		
		var url = "index.php?act=seller_wechat&op=addkeyword_ajax&kid="+zsc_id;
		//var url = "/biz.php?ctl=wechat&act=addkeyword_ajax&kid="+zsc_id;
		$.post(url,null,function(data){
			//$.each(data,function(i,v){
				var zsc_html = "";
				zsc_html = "<div class=\"edit-info\">"+
						   "<input type=\"hidden\" value=\""+id+"\" id=\"zsc_iid\" />"+
						   "<ul><li>"+
						   "<span>规则命名</span>"+
						   "<input type=\"text\" class=\"text1\" value=\"\" id=\"zsc_kname\" />"+
						   "</li><li><span>关键词</span>"+
						   "<input type=\"text\" class=\"text1\" value=\"\" id=\"zsc_kword\" />"+
						   "</li></ul>"+
						   "</div>"+
						   "<div class=\"replycon-set\">"+
						   "<span>回复内容设置</span>"+
						   "<br>"+
						   "<a><input type=\"radio\" onclick=\"check_tuwen(1)\" id=\"ch_wen\" name=\"type\">文本</a>"+
						   "<a><input type=\"radio\" onclick=\"check_tuwen(2)\" id=\"ch_tu\" name=\"type\">图文</a>"+
						   "</div>"+
						   "<div class=\"clear\"  id=\"wen\">"+
						   "<div class=\"replycon\">"+
						   "<textarea id=\"zsc_kecontent\" style=\"width:915px;height:130px; border:#ccc solid 1px;\"></textarea>"+
						   "</div>"+
						   "<div style=\" padding-top:45px; clear:both;\">"+
						   "<a href=\"javascript:btnfalse(1);\" class=\"save cancel\">取消</a>"+
						   "<div onclick=\"btnsave(1,1)\" class=\"updatebtn\" style=\"cursor:pointer;\">修改</div>"+
						   "</div>"+
						   "</div>"+
						   //图文信息
						   "<div class=\"clear\" id=\"tu\">"+
						   "<div class=\"tuwen\">"+
						   "<div class=\"tuwen_left\">"+
						   "<a href=\"javascript:;\" class=\"imgcon\">"+
						   "<img id=\"zsc_imgs0\" src=\"/shop/resource/keyword/images/bigimg.jpg\" width=\"295\" height=\"126\"/>"+
						   "<span id=\"zsc_titles0\">标题</span>"+
						   "<b class=\"modify\"><img id=\"zsc_simg0\""+
						   " src=\"/shop/resource/keyword/images/op-modify.png\" onclick=\"zsc_editimg(0)\"/></b>"+
						   
						   "<input type=\"hidden\" value=\"\" class=\"addtitle\" id=\"zsc_t0\"/>"+
						   "<input type=\"hidden\" value=\"\" class=\"addimg\" id=\"zsc_i0\"/>"+
						   "<input type=\"hidden\" value=\"\" class=\"addurls\" id=\"zsc_urls0\" />"+
						   
						   "</a>"+
						   "<div style=\"width:295px;\" id=\"zsc_add1bg\">"+
						   "<img src=\"/shop/resource/keyword/images/keywords_28.jpg\" class=\"add1\" "+
						   "style=\"cursor:pointer;\" onclick=\"zsc_addimg(1)\" />"+
						   "</div>"+
						   "</div>"+
						   "<div class=\"tuwen_right\">"+
						   "<img src=\"/shop/resource/keyword/images/keywords_15.jpg\">"+
						   "<p>"+
						   "<br />"+
						   "标题：<input id=\"zsc_titles\" type=\"text\" style=\"width:277px;\" class=\"text1\""+
						   "onkeyup=\"zsc_keytitle()\" onchange=\"zsc_keytitle()\" />"+
						   "<br><br>"+
						   "封面：<input type=\"text\" class=\"text1\" style=\"width:210px;\" id=\"zsc_imgurls\" onchange=\"zsc_urlimg()\" onfocus =\"zsc_urlimg()\"/>&nbsp;<label class=\"fileupload\" onclick=\"upd_file(this,'fm_file');\" style=\"top:3px;\" > <input type=\"file\" class=\"filebox\" name=\"fm_file\" id=\"fm_file\" /></label><label class=\"fileuploading hide\" ></label>" +		
						   
						   //"封面：<input type=\"text\" class=\"text1\" style=\"width:277px\" id=\"zsc_imgurls\" onchange=\"zsc_urlimg()\" />"+
					
						   "<br><br>"+
						   "链接：<input id=\"zsc_urls\" type=\"text\" style=\"width:277px;\" class=\"text1\""+
						   "onkeyup=\"zsc_urlinfo()\" onchange=\"zsc_urlinfo()\" />"+
						   "</p>"+
						   "<img src=\"/shop/resource/keyword/images/keywords_19.jpg\">"+ 
						   "</div>"+
						   "<div style=\" padding-top:45px; clear:both;\">"+
						   "<a class=\"save cancel\" href=\"javascript:btnfalse(1);\">取消</a>"+
						   "<div onclick=\"btnsave(2,1)\" class=\"updatebtn\" style=\"cursor:pointer;\"></div>"+
						   "</div>"+
						   "</div>"+
						   "</div>";
				$('#zsc_content_'+zsc_id+' p').hide();
				$('#zsc_content_'+zsc_id+' div').remove();
				$('#zsc_content_'+zsc_id).append(zsc_html);
				$('#zsc_kname').val(data.kename);
				$('#zsc_kword').val(data.kyword);
				if(data.type==1){
					check_tuwen(1);
					$('#zsc_kecontent').val(data.kecontent);
				}else{
					check_tuwen(2);
					$.each(data.titles2,function(i,info){
						if(i>0){
							zsc_addimg(i);
							$('#zsc_imgs'+i+' span').html(info);
							$('#zsc_simg'+i).attr('src',data.imageinfo2[i]);
						}else{
							$('#zsc_imgs0').attr('src',data.imageinfo2[i]);
							$('#zsc_titles0').html(info);
							$('#zsc_titles').val(info);
							$('#zsc_imgurls').val(data.imageinfo2[i]);
							$('#zsc_urls').val(data.linkinfo2[i]);
						}
						$('#zsc_t'+i).val(info);
						$('#zsc_i'+i).val(data.imageinfo2[i]);
						$('#zsc_urls'+i).val(data.linkinfo2[i]);
					});
				}
			//});
		},'json');
	}
}

//图文判断
function check_tuwen(num){
	if(num==1){
		$('#wen').show();
		$('#tu').hide();
		$('#ch_wen').attr('checked','checked');
	}else{
		$('#wen').hide();
		$('#tu').show();
		$('#ch_tu').attr('checked','checked');
	}
}

//图文信息新增一条
function zsc_addimg(num){
	if($('.simgcon').length<9){
		$('#zsc_add1bg').remove();
		var zsc_html = "<a href=\"javascript:;\" class=\"simgcon\" id=\"zsc_imgs"+num+"\">"+
					   "<span>标题</span>"+
					   "<img src=\"/shop/resource/keyword/images/smallimg.jpg\" id=\"zsc_simg"+num+"\" />"+
					   "<b class=\"smodify\"><img src=\"/shop/resource/keyword/images/op-modify.png\" onclick=\"zsc_editimg("+num+")\" />"+
					   "<img src=\"/shop/resource/keyword/images/op-del.png\" onclick=\"zsc_delimg("+num+")\" /></b>"+
					   "<input type=\"hidden\" value=\""+num+"\" class=\"simgcons\" />"+
					   "<input type=\"hidden\" value=\"\" id=\"zsc_urls"+num+"\" class=\"addurls\"/>"+
					   "<input type=\"hidden\" value=\"\" class=\"addimg\" id=\"zsc_i"+num+"\"/>"+
					   "<input type=\"hidden\" value=\"\" class=\"addtitle\" id=\"zsc_t"+num+"\"/>"+
					   "</a>";
		$('.tuwen_left').append(zsc_html);
		num = parseInt(num)+1;
		zsc_html = "<div style=\"width:295px;\" id=\"zsc_add1bg\">"+
				   "<img src=\"/shop/resource/keyword/images/keywords_28.jpg\" class=\"add1\" "+
				   "style=\"cursor:pointer;\" onclick=\"zsc_addimg("+num+")\" />"+
				   "</div>";
		$('.tuwen_left').append(zsc_html);
	}else{
		alert('图文信息上限！');
	}
}

//删除某一条图文信息
function zsc_delimg(num){
	if(!confirm('是否删除该图文信息')){
		return false;	
	}
	$('#zsc_imgs'+num).remove();
	$('.tuwen_right').css('top','40px');
	$('#zsc_titles').val($('#zsc_titles0').html());
	$('#zsc_urls').val($('#zsc_urls0').val());
}

//编辑某一条图文信息
function zsc_editimg(num){
	var count = 0;
	zsc_count = 0;
	for(var i=0;i<$('.simgcons').length;i++){	
		var zsc_co = $('.simgcons')[i];
		if(num==zsc_co.value){
			count = i+1;
			zsc_count = num;
		}
	}
	var num = parseInt(count)*80+40;
	$('.tuwen_right').css('top',num+'px');
	if(0==zsc_count){
		$('#zsc_titles').val($('#zsc_titles0').html());
		$('#zsc_urls').val($('#zsc_urls0').val());
		/*
		if('themes/mall/default/styles/default/keyword/images/bigimg.jpg'==$('#zsc_imgs0').attr('src')){
			$('#zsc_imgurls').val();
		}else{
			$('#zsc_imgurls').val($('#zsc_imgs0').attr('src'));
		}
		*/
		$('#zsc_imgurls').val($('#zsc_i0').val());
		
	}else{
		$('#zsc_titles').val($('#zsc_imgs'+zsc_count+" span").html());
		$('#zsc_urls').val($('#zsc_urls'+zsc_count).val());
		var ssurl = $('#zsc_simg'+zsc_count).attr('src');
		/*
		if('static/weixin/images/dingcan/smallimg.jpg'!=ssurl){
			$('#zsc_imgurls').val($('#zsc_simg'+zsc_count).attr('src'));
		}else{
			$('#zsc_imgurls').val('');
		}
		*/
		$('#zsc_imgurls').val($('#zsc_i'+zsc_count).val());
	}
}

//标题改变
function zsc_keytitle(){
	var zsc_title = $('#zsc_titles').val();
	if(0==zsc_count){
		if(""==zsc_title){
			$('#zsc_titles0').html('标题');
			$('#zsc_t0').val('');
		}else{
			$('#zsc_titles0').html(zsc_title);
			$('#zsc_t0').val(zsc_title);
		}
	}else{
		if(""==zsc_title){
			$('#zsc_imgs'+zsc_count+" span").html('标题');
			$('#zsc_t'+zsc_count).val('');
		}else{
			$('#zsc_imgs'+zsc_count+" span").html(zsc_title);
			$('#zsc_t'+zsc_count).val(zsc_title);
		}
	}
}

//链接改变
function zsc_urlinfo(){
	var zsc_url = $('#zsc_urls').val();
	if(0==zsc_count){
		$('#zsc_urls0').val(zsc_url);
	}else{
		$('#zsc_urls'+zsc_count).val(zsc_url);
	}
}

var flag = true;
//显示添加新回复
$(function(){
	$('.addreply').click(function(){
		var zsc_class = $('#zsc_btninfo'+click_id).attr('class');
		if(zsc_class!='add'){
			$('#zsc_btninfo'+click_id).attr('class','add');	
			$('#zsc_content_'+click_id+' p').show();
			$('#zsc_content_'+click_id+' div').remove();
			click_id="";
		}
		if(flag){
			var zsc_html = ""
			zsc_html = "<div><h3>添加新的回复</h3></div>"+
					   "<div class=\"edit-info\">"+
					   "<ul><li>"+
					   "<span>规则命名</span>"+
					   "<input type=\"text\" class=\"text1\" value=\"\" id=\"zsc_kname\" />"+
					   "</li><li><span>关键词</span>"+
					   "<input type=\"text\" class=\"text1\" value=\"\" id=\"zsc_kword\" />"+
					   "</li></ul>"+
					   "</div>"+
					   "<div class=\"replycon-set\">"+
					   "<span>回复内容设置</span>"+
					   "<br>"+
					   "<a><input type=\"radio\" onclick=\"check_tuwen(1)\" id=\"ch_wen\" checked=\"checked\" name=\"type\">文本</a>"+
					   "<a><input type=\"radio\" onclick=\"check_tuwen(2)\" id=\"ch_tu\" name=\"type\">图文</a>"+
					   "</div>"+
					   "<div class=\"clear\"  id=\"wen\">"+
					   "<div class=\"replycon\">"+
					   "<textarea id=\"zsc_kecontent\" style=\"width:915px;height:130px; border:#ccc solid 1px;\"></textarea>"+
					   "</div>"+
					   "<div style=\" padding-top:45px; clear:both;\">"+
					   "<a href=\"javascript:btnfalse(0);\" class=\"save cancel\">取消</a>"+
					   "<div onclick=\"btnsave(1,0)\" class=\"save\" style=\"cursor:pointer;\">保存</div>"+
					   "</div>"+
					   "</div>"+
					   //图文信息
					   "<div class=\"clear\" id=\"tu\">"+
					   "<div class=\"tuwen\">"+
					   "<div class=\"tuwen_left\">"+
					   "<a href=\"javascript:;\" class=\"imgcon\">"+
					   "<img id=\"zsc_imgs0\" src=\"/shop/resource/keyword/images/bigimg.jpg\" width=\"295\" height=\"126\"/>"+
					   "<span id=\"zsc_titles0\">标题</span>"+
					   "<b class=\"modify\"><img id=\"zsc_simg0\""+
					   " src=\"/shop/resource/keyword/images/op-modify.png\" onclick=\"zsc_editimg(0)\"/></b>"+
					   "<input type=\"hidden\" value=\"\" class=\"addtitle\" id=\"zsc_t0\"/>"+
					   "<input type=\"hidden\" value=\"\" class=\"addimg\" id=\"zsc_i0\"/>"+
					   "<input type=\"hidden\" value=\"\" class=\"addurls\" id=\"zsc_urls0\" />"+
					   "</a>"+
					   "<div style=\"width:295px;\" id=\"zsc_add1bg\">"+
					   "<img src=\"/shop/resource/keyword/images/keywords_28.jpg\" class=\"add1\" "+
					   "style=\"cursor:pointer;\" onclick=\"zsc_addimg(1)\" />"+
					   "</div>"+
					   "</div>"+
					   "<div class=\"tuwen_right\">"+
					   "<img src=\"/shop/resource/keyword/images/keywords_15.jpg\">"+
					   "<p>"+
					   "<br />"+
					   "标题：<input id=\"zsc_titles\" type=\"text\" style=\"width:277px;margin-top:16px;margin-left:30px;\" class=\"text1\""+
					   "onkeyup=\"zsc_keytitle()\" onchange=\"zsc_keytitle()\" />"+
					   "<br><br>"+
					    "封面：<input type=\"text\" class=\"text1\" style=\"width:210px;margin-top:16px;margin-left:30px;\" id=\"zsc_imgurls\" onchange=\"zsc_urlimg()\" onfocus =\"zsc_urlimg()\"/>&nbsp;<label class=\"fileupload\" onclick=\"upd_file(this,'fm_file');\" style=\"margin-top:18px;margin-left:6px;\" > <input type=\"file\" class=\"filebox\" name=\"fm_file\" id=\"fm_file\" /></label><label class=\"fileuploading hide\" ></label>" +	
					   //"封面：<input type=\"text\" class=\"text1\" style=\"width:277px\" id=\"zsc_imgurls\" onchange=\"zsc_urlimg()\" />"+
					   "<br><br>"+
					   "链接：<input id=\"zsc_urls\" type=\"text\" style=\"width:277px;margin-top:16px;margin-left:30px;\" class=\"text1\""+
					   "onkeyup=\"zsc_urlinfo()\" onchange=\"zsc_urlinfo()\" />"+
					   "</p>"+
					   "<img src=\"/shop/resource/keyword/images/keywords_19.jpg\">"+ 
					   "</div>"+
					   "<div style=\" padding-top:45px; clear:both;\">"+
					   "<a class=\"save cancel\" href=\"javascript:btnfalse(0);\">取消</a>"+
					   "<div onclick=\"btnsave(2,0)\" class=\"save\" style=\"cursor:pointer;\">保存</div>"+
					   "</div>"+
					   "</div>"+
					   "</div>";
			$('#add_zsc').append(zsc_html);
			$('#add_zsc').show();
			check_tuwen(1);
			flag = false;
		}else{
			$('#add_zsc div').remove();
			$('#add_zsc').hide();
			flag = true;
		}
	});
});

//取消按钮
function btnfalse(num){
	if(0==num){
		$('#add_zsc div').remove();
		$('#add_zsc').hide();
		flag = true;
	}else{
		var zsc_class = $('#zsc_btninfo'+click_id).attr('class');
		$('#zsc_btninfo'+click_id).attr('class','add');	
		$('#zsc_content_'+click_id+' p').show();
		$('#zsc_content_'+click_id+' div').remove();
		click_id="";
	}
}


//添加、修改 消息
function btnsave(num,count){
	var kname = $('#zsc_kname').val();//规则
	var kword = $('#zsc_kword').val();//关键字
	if(''==kname){
		alert('请输入规则');
		return false;
	}
	if(''==kword){
		alert('请输入关键字');
		return false;
	}
	
	var ktype = num;//1:文本、2:图文
	if(ktype==1){
		var kecontent = $('#zsc_kecontent').val();//内容
		if(''==kecontent){
			alert('请输入内容');
			return false;
		}
		if(count==0){
			//var url = "/biz.php?ctl=wechat&act=keyword_index&op=add";
			var url = "index.php?act=seller_wechat&op=keyword_index&opt=add";
			$.post(url,{kename:kname,keword:kword,kecontent:kecontent,ketype:ktype},function(data){
				if(data==1)
			{
			alert('添加成功！');
			window.location.reload();
			}else if(data==0)
			{
				alert('添加成功！');
				return false;
			}
			});
		}else{
			//var url = "/biz.php?ctl=wechat&act=keyword_index&op=update";
			var url = "index.php?act=seller_wechat&op=keyword_index&opt=update";
			var kid = $('#zsc_iid').val();
			$.post(url,{kename:kname,keword:kword,kecontent:kecontent,ketype:ktype,kid:kid},function(data){
				if(data==1)
			{
			alert('修改成功！');
			window.location.reload();
			}else if(data==0)
			{
				alert('修改成功！');
				return false;
			}
			});
		}
		
	}else{
		var titles = $('.addtitle'); 
		var arrTitles = '';	//标题数组
		var imgurls = $('.addimg'); 	//图片路径数组
		var arrImgurls ='';
		var curl = $('.addurls');		//链接路径数组
		var arrCurl ='';
		var flag = true;
		$.each(titles,function(i,v){
			if('标题'==v.value||''==v.value||null==v.value){
				flag = false;
			}
			arrTitles+=v.value+",";
		});
		$.each(imgurls,function(i,v){
			if(''==v.value||null==v.value){
				flag = false;
			}
			arrImgurls+=v.value+",";
		});
		$.each(curl,function(i,v){
			if(''==v.value||null==v.value){
				flag = false;
			}
			arrCurl+=v.value+",";
		});
		
		if(!flag){
			alert('请输入完整信息再提交');
			return false;
		}
		
		if(count==0){
		    var url = "index.php?act=seller_wechat&op=keyword_index&opt=add";
			//var url = "/biz.php?ctl=wechat&act=keyword_index&op=add";
			$.post(url,{kename:kname,keword:kword,ketype:ktype,titles:arrTitles,imageinfo:arrImgurls,linkinfo:arrCurl},function(data){
				if(data==1)
				{
				alert('添加成功！');
				window.location.reload();
				}else
				{
					alert('添加失败！');return false;
				}
			});
		}else{
		    var url = "index.php?act=seller_wechat&op=keyword_index&opt=update";
			//var url = "/biz.php?ctl=wechat&act=keyword_index&op=update";
			var kid = $('#zsc_iid').val();
			$.post(url,{kename:kname,keword:kword,ketype:ktype,titles:arrTitles,imageinfo:arrImgurls,linkinfo:arrCurl,kid:kid},
			function(data){
				alert('修改成功！');
				window.location.reload();
			});
		}
	}
}

//删除某条信息
function zscdel(num){
	if(!confirm('是否删除?')){
		return false;	
	}
	var url = "index.php?act=seller_wechat&op=keyword_index&opt=del";
	//var url = "/biz.php?ctl=wechat&act=keyword_index&op=del";
	$.post(url,{kid:num},function(data){
		if(data==1)
		{
		alert('删除成功！');
		window.location.reload();
		}else if(data==0)
		{
		alert('删除失败！');
		return false;
		}
	});
}
function zsc_urlimg(){
	var zsc_imgurls = $('#zsc_imgurls').val();
	
	if(0==zsc_count){
		$('#zsc_imgs0').attr('src',zsc_imgurls);
		$('#zsc_i0').val(zsc_imgurls);
	}else{
		$('#zsc_simg'+zsc_count).attr('src',zsc_imgurls);
		$('#zsc_i'+zsc_count).val(zsc_imgurls);
	}
}

function upd_file(obj,file_id)
{	
	$("input[name='"+file_id+"']").bind("change",function(){			
		$(obj).hide();
		$(obj).parent().find(".fileuploading").removeClass("hide");
		$(obj).parent().find(".fileuploading").removeClass("show");
		$(obj).parent().find(".fileuploading").addClass("show");
		  $.ajaxFileUpload
		   (
			   {
				    //url:APP_ROOT+'/index.php?ctl=avatar&act=upload&uid='+uid,
					url:"index.php?act=seller_wechat&op=upload_fm",
					//url:APP_ROOT+'/biz.php?ctl=wechat&act=upload_fm',
				    secureuri:false,
				    fileElementId:file_id,
				    dataType: 'json',
				    success: function (data, status)
				    {
				   		$(obj).show();
				   		$(obj).parent().find(".fileuploading").removeClass("hide");
						$(obj).parent().find(".fileuploading").removeClass("show");
						$(obj).parent().find(".fileuploading").addClass("hide");
				   		if(data.status==1)
				   		{
						    var img_url = data.big_url;
							var mid_url = data.mid_url;
						    if(0==zsc_count){
							    document.getElementById("zsc_imgurls").value = img_url;
							    $('#zsc_i0').val(img_url);
								$('#zsc_imgs0').attr('src',img_url);
							}else{
							    document.getElementById("zsc_imgurls").value = mid_url;
							    $('#zsc_i'+zsc_count).val(mid_url);
								$('#zsc_simg'+zsc_count).attr('src',mid_url);
							}
				   		}
				   		else
				   		{
				   			$.showErr(data.msg);
				   		}
				   		
				    },
				    error: function (data, status, e)
				    {
						$.showErr(data.responseText);;
				    	$(obj).show();
				    	$(obj).parent().find(".fileuploading").removeClass("hide");
						$(obj).parent().find(".fileuploading").removeClass("show");
						$(obj).parent().find(".fileuploading").addClass("hide");
				    }
			   }
		   );
		  $("input[name='"+file_id+"']").unbind("change");
	});	
}