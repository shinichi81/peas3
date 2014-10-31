// Object Inspector Plugin for HTMLArea-4.0
// (c) 2007-2013 Inferior-Products.com
// (c) dynarch.com 2003-2007
//
// Previously sponsored by Fabio Rotondo, http://www.os3.it/
// Original implementation by Mihai Bazon, http://dynarch.com/mishoo/
// Currently maintained and developed by SF_chris. http://Inferior-Products.com
//
// This plugin is released under BSD-style license.
//
// $Id: object-inspector.js,v 1.8 2013-07-29 19:33:48 SF_chris Exp $
function ObjectInspector(editor,params){this.editor=editor;params=params[0];if(typeof params=="string")params=eval('{'+params+'}');if(typeof params.parent=="string")params.parent=document.getElementById(params.parent);this.parent=params.parent;this.parent.innerHTML="ObjectInspector initializing...";this.toUpdate=[];this.target=null;var iframe=document.createElement("iframe");this.comm=iframe;iframe.src="about:blank";iframe.style.display="none";document.body.appendChild(iframe);ObjectInspector.editor=editor;setTimeout(function(){var oilayout=params.layout;var pluginurl=_editor_url+"plugins/ObjectInspector/";switch(params.layout){case "H":oilayout=pluginurl+'oi-horizontal.html';break;case "V":oilayout=pluginurl+'oi-vertical.html';break;}iframe.contentWindow.document.location.replace(oilayout);},2000);}ObjectInspector._pluginInfo={name:"ObjectInspector",version:"1.1",developer:"SF_chris",developer_url:"http://Inferior-Products.com/",c_owner:"Inferior-Products",sponsor:"www.os3.it",sponsor_url:"http://www.os3.it",license:"BSD-style license"};ObjectInspector.prototype.onDataLoaded=function(){var self=this;this.parent.innerHTML=this.comm.contentWindow.document.body.innerHTML;this.comm.parentNode.removeChild(this.comm);var i18n=ObjectInspector.I18N,cln,div,i;var els=this.parent.getElementsByTagName("span");for(var i=els.length;--i>=0;){var sp=els[i];var txt=sp.firstChild;if(txt){var translation=i18n[txt.data];if(translation)txt.data=translation;}}els=this.parent.getElementsByTagName("div");for(i=els.length;--i>=0;){div=els[i];if(!div.className.match(/control-(.*)/))continue;cln=RegExp.$1;switch(cln){case "color-selector":this.createColorSelector(div);break;case "input":this.createInputField(div);break;case "select":this.createSelect(div);break;case "select-border-style":this.createSelectBorderStyle(div);break;default:this.registerUpdatingElement(div);break;}}els=this.parent.getElementsByTagName("button");for(i=els.length;--i>=0;){div=els[i];if(/control-button-(.*)/.test(div.className)){div.__msh_handler=RegExp.$1;div.onclick=function(){self[this.__msh_handler].call(self);return false;};}}};ObjectInspector.prototype.makeDiv=function(span){var editor=this.editor,doc=editor._doc;if(!HTMLArea.is_ie){var sel=editor._getSelection();var range=editor._createRange(sel);var div=doc.createElement(span||"div");div.style.border="1px solid #000";div.style.padding="5px";if(range.collapsed){if(span=="span")div.innerHTML="&nbsp;";else div.appendChild(doc.createElement("br"));range.insertNode(div);range.selectNodeContents(div);range.collapse(true);}else{sel.removeAllRanges();range.surroundContents(div);range.selectNode(div);}sel.addRange(range);editor.focusEditor();editor.updateToolbar();}};ObjectInspector.prototype.makeSpan=function(){this.makeDiv("span");};ObjectInspector.prototype.onUpdateToolbar=function(){try{var el=this.element=this.editor.getParentElement();for(var i=0;i<this.toUpdate.length;++i){var args=this.toUpdate[i];args.onUpdate();}}catch(ex){};};ObjectInspector.createControl=function(div){var section=div;while(section&&!/section/.test(section.className))section=section.parentNode;if(section){div.__msh_section=section;var title=section.firstChild;while(title&&!/title/.test(title.className))title=title.nextSibling;if(title){div.__msh_title=title;if(title.childNodes.length==1){var span=document.createElement("span");span.className="tooltip";title.insertBefore(span,title.firstChild);}div.__msh_displayTooltip=function(){if(this.__msh_tooltip){var tt=this.__msh_title.firstChild;tt.innerHTML=this.__msh_tooltip;tt.nextSibling.style.display="none";}};div.__msh_removeTooltip=function(){if(this.__msh_tooltip){var tt=this.__msh_title.firstChild;tt.innerHTML=" ";tt.nextSibling.style.display="inline";}};}}if(!div.firstChild)return;if(div.firstChild.nodeType==1&&div.firstChild.tagName.toLowerCase()=="span"){div.__msh_tooltip=div.firstChild.firstChild.data;div.firstChild.parentNode.removeChild(div.firstChild);}};ObjectInspector.prototype.createColorSelector=function(div){var self=this;this.registerUpdatingElement(div);ObjectInspector.createControl(div);var args=div.__msh_args;if(args.type.match(/style\[(.*?)\]/)){args.prop=RegExp.$1;args.onUpdate=function(){this.target.style.backgroundColor=this.oi.element.style[this.prop];};args.onSelect=function(color){this.oi.element.style[this.prop]=color;this.onUpdate();};args.getValue=function(){return this.target.style[this.prop];};}div.onmouseover=function(){this.className="control-color-selector hover";this.__msh_displayTooltip();};div.onmouseout=function(){this.className="control-color-selector";this.__msh_removeTooltip();};div.onclick=function(){var div=this;self.editor._popupDialog("select_color.html",function(color){if(color){div.__msh_args.onSelect("#"+color);}},HTMLArea._colorToRgb(this.__msh_args.getValue()));};var x=document.createElement("span");x.className="control-color-nuller";x.innerHTML="&#x00d7;";div.appendChild(x,div.nextSibling);x.onmouseover=function(){this.className="control-color-nuller hover";};x.onmouseout=function(){this.className="control-color-nuller";};x.onclick=function(ev){ev||(ev=window.event);this.parentNode.__msh_args.onSelect("");HTMLArea._stopEvent(ev);};};ObjectInspector.prototype.createInputField=function(div){var self=this;this.registerUpdatingElement(div);ObjectInspector.createControl(div);var args=div.__msh_args;var input=document.createElement("input");input.type="text";if(/length/.test(args.type))input.size="3";if(/numeric/.test(args.type))input.style.textAlign="center";if(args.type.match(/style\[(.*?)\]/)){args.prop=RegExp.$1;args.onUpdate=function(){this.setValue(this.oi.element.style[this.prop]);};args.onSelect=function(){this.oi.element.style[this.prop]=this.getValue();};}input.__msh_args=args;div.appendChild(input);args.inputField=input;args.getValue=function(){return this.inputField.value;};args.setValue=function(value){this.inputField.value=value;};input.onfocus=function(){this.parentNode.__msh_displayTooltip();this.className="active";this.select();};input.onblur=function(){this.parentNode.__msh_removeTooltip();this.className="";};input.onchange=function(){this.__msh_args.onSelect();self.redraw();};input.onkeypress=function(ev){ev||(ev=window.event);if(ev.keyCode==13){this.__msh_args.onSelect();self.redraw();self.onUpdateToolbar();this.focus();}};div.onmouseover=function(){this.__msh_displayTooltip();};div.onmouseout=function(){this.__msh_removeTooltip();};};ObjectInspector.prototype._createSelect=function(div){var self=this;var args=div.__msh_args;var select=document.createElement("select");select.__msh_args=args;div.appendChild(select);div.style.display="inline";div.onmouseover=function(){this.__msh_displayTooltip();};div.onmouseout=function(){this.__msh_removeTooltip();};for(var i in args.options){var txt=args.options[i];var translation=ObjectInspector.I18N[txt];if(translation)txt=translation;var option=document.createElement("option");option.value=i;option.innerHTML=txt;select.appendChild(option);}args.select=select;args.getValue=function(){return this.select.options[this.select.selectedIndex].value;};args.setValue=function(val){if(!val)val="none";var options=this.select.options;for(var i=options.length;--i>=0;){var op=options[i];op.selected=(op.value==val);}};select.onchange=function(){this.__msh_args.onSelect();self.redraw();};select.onfocus=function(){this.parentNode.__msh_displayTooltip();};select.onblur=function(){this.parentNode.__msh_removeTooltip();};};ObjectInspector.prototype.createSelect=function(div){this.registerUpdatingElement(div);ObjectInspector.createControl(div);this._createSelect(div);};ObjectInspector.prototype.createSelectBorderStyle=function(div){this.registerUpdatingElement(div);ObjectInspector.createControl(div);var args=div.__msh_args;args.options={"none":"None","solid":"Solid","dotted":"Dotted","dashed":"Dashed"};args.onUpdate=function(){this.setValue(this.oi.element.style[this.type]);};args.onSelect=function(){this.oi.element.style[this.type]=this.getValue();};this._createSelect(div);};ObjectInspector.prototype.registerUpdatingElement=function(el){var code="";if(el.firstChild.nodeType==1&&el.firstChild.tagName.toLowerCase()=="code")code=el.firstChild.innerHTML;else code=el.firstChild.data;el.firstChild.parentNode.removeChild(el.firstChild);var args={target:el,editor:this.editor,oi:this};if(eval(code)){this.toUpdate.push(args);el.__msh_args=args;}};ObjectInspector.prototype.redraw=function(){this.editor.forceRedraw();if(this.element.tagName.toLowerCase()=="table"){var s=this.element.style;var save_collapse=s.borderCollapse;s.borderCollapse="collapse";s.borderCollapse="separate";s.borderCollapse=save_collapse;}};
