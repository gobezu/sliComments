(function(d){window.addEvent("domready",function(){$$(".comments_form.no-js").removeClass("no-js");var f=d("comments_counter"),i=d("comments_list"),e=function(a){return function(c){c.stop();(new Request({url:this.get("href"),format:"raw",method:"get",onSuccess:a.bind(this),onFailure:function(a){alert(a.responseText)}})).send()}},g=e(function(a){var c=this.getParent(".content-container").getElement(".metadata"),c=c.getElement(".rating")||(new Element("span.rating")).inject(c),a=(c.get("text").toInt()||
0)+a.toInt();c.set("text",a>0?"+"+a:a).removeClass("(?:positive|negative)").addClass(a>0?"positive":"negative")});i.addEvents({"click:relay(a.comment-delete)":e(function(){this.getParent("li.comment").nix(true);f.set("text",f.get("text").toInt()-1)}),"click:relay(a.comment-like)":g,"click:relay(a.comment-dislike)":g,"click:relay(.slicomments-spoiler button)":function(){var a=this.getParent();a.hasClass("spoiler-hide")?(a.removeClass("spoiler-hide"),this.set("text",this.get("data-hide"))):(a.addClass("spoiler-hide"),
this.set("text",this.get("data-show")))}});var b=d("comments_form");if(b){var e=d("comments_form_textarea"),h=d("comments-remaining-count"),g=b.get("data-logged")==1?true:false,k=new Form.Validator.Inline(b,{scrollToErrorsOnSubmit:false,evaluateFieldsOnChange:false,evaluateFieldsOnBlur:false}),j=function(){remaining_chars=this.get("data-maxlength")-this.value.length;h.set("text",remaining_chars);remaining_chars<=5?(h.setStyle("color","#900"),remaining_chars<0&&b.getElement("button").set("disabled",
true)):(b.getElement("button").set("disabled",false),h.setStyle("color",null))};e.addEvents({keypress:j,focus:function(){this.addClass("init")},blur:function(){this.value.length==0&&this.removeClass("init");j.call(this)}});"placeholder"in document.createElement("input")?e.get("disabled")||$$(".comments_form_inputs li label").setStyle("display","none"):(OverText.implement({attach:function(){var a=this.element,c=this.options,b=c.textOverride||a.get("placeholder")||a.get("alt")||a.get("title");if(!b)return this;
b=this.text=(a.getPrevious(c.element)||(new Element(c.element)).inject(a,"after")).addClass(c.labelClass).setStyles({lineHeight:"normal",position:"absolute",cursor:"text"}).set("html",b).addEvent("click",this.hide.pass(c.element=="label",this));c.element=="label"&&(a.get("id")||a.set("id","input_"+String.uniqueID()),b.set("for",a.get("id")));if(c.wrap)this.textHolder=(new Element("div.overTxtWrapper",{styles:{lineHeight:"normal",position:"relative"}})).grab(b).inject(a,"before");return this.enable()}}),
new OverText(e,{positionOptions:{offset:{x:6,y:6}}}),g||(new OverText(d("comments_form_name")),new OverText(d("comments_form_email"))));d("comments_form_send").addEvent("click",function(a){a.stop();k.validate()&&(new Request.HTML({url:b.get("action"),format:"raw",method:"post",data:b,onSuccess:function(a){a[0].inject(i,b.get("data-position"));f.set("text",f.get("text").toInt()+1);b.reset();b.text.fireEvent("keypress");b.text.removeClass("init");OverText.update()},onFailure:function(a){alert(a.responseText)}})).send()})}})})(document.id);