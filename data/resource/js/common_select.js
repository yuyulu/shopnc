/* 多级选择相关函数，如选择，分类选择
 * common_select
 */

/* 商品分类选择函数 */
function gcategoryInit(divId)
{
    $("#" + divId + " > select").get(0).onchange = gcategoryChange; // select的onchange事件
    window.onerror = function(){return true;}; //屏蔽jquery报错
    $("#" + divId + " .edit_gcategory").click(gcategoryEdit); // 编辑按钮的onclick事件
}

function gcategoryChange()
{
    // 删除后面的select
    $(this).nextAll("select").remove();

    // 计算当前选中到id和拼起来的name
    var selects = $(this).siblings("select").andSelf();
    var id = 0;
    var names = new Array();
    for (i = 0; i < selects.length; i++)
    {
        sel = selects[i];
        if (sel.value > 0)
        {
            id = sel.value;
            name = sel.options[sel.selectedIndex].text;
            names.push(name);
        }
    }
    $(this).parent().find(".mls_id").val(id);
    $(this).parent().find(".mls_name").val(name);
    $(this).parent().find(".mls_names").val(names.join("\t"));

    // ajax请求下级分类
    if (this.value > 0)
    {
        var _self = this;
        var url = SITEURL + '/index.php?act=index&op=josn_class&callback=?';
        $.getJSON(url, {'gc_id':this.value}, function(data){
            if (data)
            {
                if (data.length > 0)
                {
                    $("<select class='class-select'><option value=''>-请选择-</option></select>").change(gcategoryChange).insertAfter(_self);
                    var data  = data;
                    for (i = 0; i < data.length; i++)
                    {
                        $(_self).next("select").append("<option data-explain='" + data[i].commis_rate + "' value='" + data[i].gc_id + "'>" + data[i].gc_name + "</option>");
                    }
                }
                else
                {
                	$(_self).attr('end','1');
                }
            }
        });
    }
}

function gcategoryEdit()
{
    $(this).siblings("select").show();
    $(this).siblings("span").andSelf().remove();
}
//显示一级分类下拉框
function show_gc_1(depth,gc_json){
	var html = '<select name="search_gc[]" id="search_gc_0" nc_type="search_gc" class="querySelect">';;
	html += ('<option value="0">请选择...</option>');
	if(gc_json){
		for(var i in gc_json){
			if(gc_json[i].depth == 1){
				html += ('<option value="'+gc_json[i].gc_id+'">'+gc_json[i].gc_name+'</option>');
			}
		}
	}
	html += '</select>';
	$("#searchgc_td").html(html);
}
//显示子分类下拉框
function show_gc_2(chooseid,gc_json){
	if(gc_json && chooseid > 0){
		var childid = gc_json[chooseid].child;
		if(childid){
			var html = '<select name="search_gc[]" id="search_gc_'+gc_json[chooseid].depth+'" nc_type="search_gc" class="querySelect">';;
			html += ('<option value="0">请选择...</option>');
			var childid_arr = childid.split(",");
			if(childid_arr){
				for(var i in childid_arr){
					html += ('<option value="'+gc_json[childid_arr[i]].gc_id+'">'+gc_json[childid_arr[i]].gc_name+'</option>');
				}
			}
			html += '</select>';
			$("#searchgc_td").append(html);
		}
	}
}
//初始化商品分类select
//chooseid_arr为已选gc_id的json数组
function init_gcselect(chooseid_json,gc_json){
	show_gc_1(1,gc_json);
	if(chooseid_json){
		for(var i in chooseid_json){
			show_gc_2(chooseid_json[i],gc_json);
			$('#search_gc_'+i).val(chooseid_json[i]);
			$('#choose_gcid').val(chooseid_json[i]);
		}
	}
	//商品分类select绑定事件
	$("[nc_type='search_gc']").live('change',function(){
        $(this).nextAll("[nc_type='search_gc']").remove();
        var chooseid = $(this).val();
		if(chooseid > 0){
			$("#choose_gcid").val(chooseid);
			show_gc_2(chooseid,gc_json);
		} else {
			chooseid = $(this).prev().val();
			$("#choose_gcid").val(chooseid);
		}
    });
}