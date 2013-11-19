var AScgiloc="http://www.imathas.com/imathas/filter/graph/svgimg.php";var ASnoSVG=false;var checkIfSVGavailable=true;var notifyIfNoSVG=false;var alertIfNoSVG=false;var xunitlength=20;var yunitlength=20;var origin=[0,0];var defaultwidth=300;defaultheight=200;defaultborder=[0,0,0,0];var border=defaultborder;var strokewidth,strokedasharray,stroke,fill;var fontstyle,fontfamily,fontsize,fontweight,fontstroke,fontfill;var markerstrokewidth="1";var markerstroke="black";var markerfill="yellow";var marker="none";var arrowfill=stroke;var dotradius=4;var ticklength=4;var axesstroke="black";var gridstroke="grey";var pointerpos=null;var coordinates=null;var above="above";var below="below";var left="left";var right="right";var aboveleft="aboveleft";var aboveright="aboveright";var belowleft="belowleft";var belowright="belowright";var cpi="\u03C0",ctheta="\u03B8";var pi=Math.PI,ln=Math.log,e=Math.E;var arcsin=Math.asin,arccos=Math.acos,arctan=Math.atan;var sec=function(A){return 1/Math.cos(A)};var csc=function(A){return 1/Math.sin(A)};var cot=function(A){return 1/Math.tan(A)};var xmin,xmax,ymin,ymax,xscl,yscl,xgrid,ygrid,xtick,ytick,initialized;var isIE=document.createElementNS==null;var picture,svgpicture,doc,width,height,a,b,c,d,i,n,p,t,x,y;var arcsec=function(A){return arccos(1/A)};var arccsc=function(A){return arcsin(1/A)};var arccot=function(A){return arctan(1/A)};var sinh=function(A){return(Math.exp(A)-Math.exp(-A))/2};var cosh=function(A){return(Math.exp(A)+Math.exp(-A))/2};var tanh=function(A){return(Math.exp(A)-Math.exp(-A))/(Math.exp(A)+Math.exp(-A))};var sech=function(A){return 1/cosh(A)};var csch=function(A){return 1/sinh(A)};var coth=function(A){return 1/tanh(A)};var arcsinh=function(A){return ln(A+Math.sqrt(A*A+1))};var arccosh=function(A){return ln(A+Math.sqrt(A*A-1))};var arctanh=function(A){return ln((1+A)/(1-A))/2};var sech=function(A){return 1/cosh(A)};var csch=function(A){return 1/sinh(A)};var coth=function(A){return 1/tanh(A)};var arcsech=function(A){return arccosh(1/A)};var arccsch=function(A){return arcsinh(1/A)};var arccoth=function(A){return arctanh(1/A)};var sign=function(A){return(A==0?0:(A<0?-1:1))};var logten=function(A){return(Math.LOG10E*Math.log(A))};function factorial(A,D){if(D==null){D=1}for(var B=A-D;B>0;B-=D){A*=B}return(A<0?NaN:(A==0?1:A))}function C(A,B){var E=1;for(var D=0;D<B;D++){E*=(A-D)/(B-D)}return E}function chop(A,B){if(B==null){B=0}return Math.floor(A*Math.pow(10,B))/Math.pow(10,B)}function ran(B,A,D){if(D==null){D=0}return chop((A+Math.pow(10,-D)-B)*Math.random()+B,D)}function myCreateElementXHTML(A){if(isIE){return document.createElement(A)}else{return document.createElementNS("http://www.w3.org/1999/xhtml",A)}}function isSVGavailable(){if((ver=navigator.userAgent.toLowerCase().match(/safari\/(\d+)/))!=null){if(ver[1]>524){return null}}if((ver=navigator.userAgent.toLowerCase().match(/opera\/([\d\.]+)/))!=null){if(ver[1]>9.1){return null}}if(navigator.product&&navigator.product=="Gecko"){var rv=navigator.userAgent.toLowerCase().match(/rv:\s*([\d\.]+)/);if(rv!=null){rv=rv[1].split(".");if(rv.length<3){rv[2]=0}if(rv.length<2){rv[1]=0}}if(rv!=null&&10000*rv[0]+100*rv[1]+1*rv[2]>=10800){return null}else{return 1}}if(navigator.appName.slice(0,9)=="Microsoft"){try{var oSVG=eval("new ActiveXObject('Adobe.SVGCtl.3');");return null}catch(e){return 1}}else{return 1}}function less(A,B){return A<B}function setText(A,D){var B=document.getElementById(D);if(B!=null){if(B.childNodes.length!=0){B.childNodes[0].nodeValue=A}else{B.appendChild(document.createTextNode(A))}}}function myCreateElementSVG(A){if(isIE){return doc.createElement(A)}else{return doc.createElementNS("http://www.w3.org/2000/svg",A)}}function getX(){return(doc.getElementById("pointerpos").getAttribute("cx")-origin[0])/xunitlength}function getY(){return(height-origin[1]-doc.getElementById("pointerpos").getAttribute("cy"))/yunitlength}function mousemove_listener(A){if(svgpicture.getAttribute("xbase")!=null){pointerpos.cx.baseVal.value=A.clientX-svgpicture.getAttribute("xbase")}if(svgpicture.getAttribute("ybase")!=null){pointerpos.cy.baseVal.value=A.clientY-svgpicture.getAttribute("ybase")}}function top_listener(A){svgpicture.setAttribute("ybase",A.clientY)}function bottom_listener(A){svgpicture.setAttribute("ybase",A.clientY-height+1)}function left_listener(A){svgpicture.setAttribute("xbase",A.clientX)}function right_listener(A){svgpicture.setAttribute("xbase",A.clientX-width+1)}function switchTo(A){picture=document.getElementById(A);width=picture.getAttribute("width")-0;height=picture.getAttribute("height")-0;strokewidth="1";stroke="black";fill="none";marker="none";if((picture.nodeName=="EMBED"||picture.nodeName=="embed")&&isIE){svgpicture=picture.getSVGDocument().getElementById("root");doc=picture.getSVGDocument()}else{picture.setAttribute("onmousemove","updateCoords"+(A.slice(A.length-1)-1)+"()");svgpicture=picture;doc=document}xunitlength=svgpicture.getAttribute("xunitlength")-0;yunitlength=svgpicture.getAttribute("yunitlength")-0;xmin=svgpicture.getAttribute("xmin")-0;xmax=svgpicture.getAttribute("xmax")-0;ymin=svgpicture.getAttribute("ymin")-0;ymax=svgpicture.getAttribute("ymax")-0;origin=[svgpicture.getAttribute("ox")-0,svgpicture.getAttribute("oy")-0]}function updatePicture(obj){var src=document.getElementById((typeof obj=="string"?obj:"picture"+(obj+1)+"input")).value;xmin=null;xmax=null;ymin=null;ymax=null;xscl=null;xgrid=null;yscl=null;ygrid=null;initialized=false;switchTo((typeof obj=="string"?obj.slice(0,8):"picture"+(obj+1)));src=src.replace(/plot\(\x20*([^\"f\[][^\n\r]+?)\,/g,'plot("$1",');src=src.replace(/plot\(\x20*([^\"f\[][^\n\r]+)\)/g,'plot("$1")');src=src.replace(/([0-9])([a-zA-Z])/g,"$1*$2");src=src.replace(/\)([\(0-9a-zA-Z])/g,")*$1");try{with(Math){eval(src)}}catch(err){alert(err+"\n"+src)}}function showHideCode(B){var A=B.nextSibling;while(A!=null&&A.nodeName!="BUTTON"&&A.nodeName!="button"){A=A.nextSibling}if(A.style.display=="none"){A.style.display=""}else{A.style.display="none"}while(A!=null&&A.nodeName!="TEXTAREA"&&A.nodeName!="textarea"){A=A.previousSibling}if(A.style.display=="none"){A.style.display=""}else{A.style.display="none"}}function hideCode(){}function showcode(){}function nobutton(){}function setBorder(B,A,E,D){if(D==null){border=new Array(B,B,B,B)}else{border=new Array(B,A,E,D)}}function initPicture(x_min,x_max,y_min,y_max){if(!initialized){strokewidth="1";strokedasharray=null;stroke="black";fill="none";fontstyle="italic";fontfamily="times";fontsize="16";fontweight="normal";fontstroke="black";fontfill="black";marker="none";initialized=true;if(x_min!=null){xmin=x_min}if(x_max!=null){xmax=x_max}if(y_min!=null){ymin=y_min}if(y_max!=null){ymax=y_max}if(xmin==null){xmin=-5}if(xmax==null){xmax=5}if(typeof xmin!="number"||typeof xmax!="number"||xmin>=xmax){alert("Picture requires at least two numbers: xmin < xmax")}else{if(y_max!=null&&(typeof y_min!="number"||typeof y_max!="number"||y_min>=y_max)){alert("initPicture(xmin,xmax,ymin,ymax) requires numbers ymin < ymax")}else{width=picture.getAttribute("width");if(width==null||width==""){width=defaultwidth}height=picture.getAttribute("height");if(height==null||height==""){height=defaultheight}xunitlength=(width-border[0]-border[2])/(xmax-xmin);yunitlength=xunitlength;if(ymin==null){origin=[-xmin*xunitlength+border[0],height/2];ymin=-(height-border[1]-border[3])/(2*yunitlength);ymax=-ymin}else{if(ymax!=null){yunitlength=(height-border[1]-border[3])/(ymax-ymin)}else{ymax=(height-border[1]-border[3])/yunitlength+ymin}origin=[-xmin*xunitlength+border[0],-ymin*yunitlength+border[1]]}winxmin=Math.max(border[0]-5,0);winxmax=Math.min(width-border[2]+5,width);winymin=Math.max(border[3]-5,0);winymax=Math.min(height-border[1]+5,height);if(isIE){svgpicture=picture.getSVGDocument().getElementById("root");while(svgpicture.childNodes.length()>5){svgpicture.removeChild(svgpicture.lastChild)}svgpicture.setAttribute("width",width);svgpicture.setAttribute("height",height);doc=picture.getSVGDocument()}else{var qnode=document.createElementNS("http://www.w3.org/2000/svg","svg");qnode.setAttribute("id",picture.getAttribute("id"));qnode.setAttribute("style","display:inline; "+picture.getAttribute("style"));qnode.setAttribute("width",picture.getAttribute("width"));qnode.setAttribute("height",picture.getAttribute("height"));if(picture.parentNode!=null){picture.parentNode.replaceChild(qnode,picture)}else{svgpicture.parentNode.replaceChild(qnode,svgpicture)}svgpicture=qnode;doc=document;pointerpos=doc.getElementById("pointerpos");if(pointerpos==null){pointerpos=myCreateElementSVG("circle");pointerpos.setAttribute("id","pointerpos");pointerpos.setAttribute("cx",0);pointerpos.setAttribute("cy",0);pointerpos.setAttribute("r",0.5);pointerpos.setAttribute("fill","red");svgpicture.appendChild(pointerpos)}}svgpicture.setAttribute("xunitlength",xunitlength);svgpicture.setAttribute("yunitlength",yunitlength);svgpicture.setAttribute("xmin",xmin);svgpicture.setAttribute("xmax",xmax);svgpicture.setAttribute("ymin",ymin);svgpicture.setAttribute("ymax",ymax);svgpicture.setAttribute("ox",origin[0]);svgpicture.setAttribute("oy",origin[1]);var node=myCreateElementSVG("rect");node.setAttribute("x","0");node.setAttribute("y","0");node.setAttribute("width",width);node.setAttribute("height",height);node.setAttribute("style","stroke-width:1;fill:white");svgpicture.appendChild(node);if(!isIE&&picture.getAttribute("onmousemove")!=null){svgpicture.addEventListener("mousemove",mousemove_listener,true);var st=picture.getAttribute("onmousemove");svgpicture.addEventListener("mousemove",eval(st.slice(0,st.indexOf("("))),true);node=myCreateElementSVG("polyline");node.setAttribute("points","0,0 "+width+",0");node.setAttribute("style","stroke:white; stroke-width:3");node.addEventListener("mousemove",top_listener,true);svgpicture.appendChild(node);node=myCreateElementSVG("polyline");node.setAttribute("points","0,"+height+" "+width+","+height);node.setAttribute("style","stroke:white; stroke-width:3");node.addEventListener("mousemove",bottom_listener,true);svgpicture.appendChild(node);node=myCreateElementSVG("polyline");node.setAttribute("points","0,0 0,"+height);node.setAttribute("style","stroke:white; stroke-width:3");node.addEventListener("mousemove",left_listener,true);svgpicture.appendChild(node);node=myCreateElementSVG("polyline");node.setAttribute("points",(width-1)+",0 "+(width-1)+","+height);node.setAttribute("style","stroke:white; stroke-width:3");node.addEventListener("mousemove",right_listener,true);svgpicture.appendChild(node)}border=defaultborder}}}}function line(D,B,E){var A;if(E!=null){A=doc.getElementById(E)}if(A==null){A=myCreateElementSVG("path");A.setAttribute("id",E);svgpicture.appendChild(A)}A.setAttribute("d","M"+(D[0]*xunitlength+origin[0])+","+(height-D[1]*yunitlength-origin[1])+" "+(B[0]*xunitlength+origin[0])+","+(height-B[1]*yunitlength-origin[1]));A.setAttribute("stroke-width",strokewidth);if(strokedasharray!=null){A.setAttribute("stroke-dasharray",strokedasharray)}A.setAttribute("stroke",stroke);A.setAttribute("fill",fill);if(marker=="dot"||marker=="arrowdot"){ASdot(D,4,markerstroke,markerfill);if(marker=="arrowdot"){arrowhead(D,B)}ASdot(B,4,markerstroke,markerfill)}else{if(marker=="arrow"){arrowhead(D,B)}}}function path(E,G,F){if(F==null){F=""}var D,A,B;if(G!=null){D=doc.getElementById(G)}if(D==null){D=myCreateElementSVG("path");D.setAttribute("id",G);svgpicture.appendChild(D)}if(typeof E=="string"){A=E}else{A="M";A+=(E[0][0]*xunitlength+origin[0])+","+(height-E[0][1]*yunitlength-origin[1])+" "+F;for(B=1;B<E.length;B++){A+=(E[B][0]*xunitlength+origin[0])+","+(height-E[B][1]*yunitlength-origin[1])+" "}}D.setAttribute("d",A);D.setAttribute("stroke-width",strokewidth);if(strokedasharray!=null){D.setAttribute("stroke-dasharray",strokedasharray)}D.setAttribute("stroke",stroke);D.setAttribute("fill",fill);if(marker=="dot"||marker=="arrowdot"){for(B=0;B<E.length;B++){if(F!="C"&&F!="T"||B!=1&&B!=2){ASdot(E[B],4,markerstroke,markerfill)}}}}function curve(A,B){path(A,B,"T")}function circle(B,A,E){var D;if(E!=null){D=doc.getElementById(E)}if(D==null){D=myCreateElementSVG("circle");D.setAttribute("id",E);svgpicture.appendChild(D)}D.setAttribute("cx",B[0]*xunitlength+origin[0]);D.setAttribute("cy",height-B[1]*yunitlength-origin[1]);D.setAttribute("r",A*xunitlength);D.setAttribute("stroke-width",strokewidth);D.setAttribute("stroke",stroke);D.setAttribute("fill",fill)}function loop(A,B,D){if(B==null){B=[1,0]}path([A,[A[0]+B[0],A[1]+B[1]],[A[0]-B[1],A[1]+B[0]],A],D,"C");if(marker=="arrow"||marker=="arrowdot"){arrowhead([A[0]+Math.cos(1.4)*B[0]-Math.sin(1.4)*B[1],A[1]+Math.sin(1.4)*B[0]+Math.cos(1.4)*B[1]],A)}}function arc(G,B,A,F){var E,D;if(F!=null){E=doc.getElementById(F)}if(A==null){D=[B[0]-G[0],B[1]-G[1]];A=Math.sqrt(D[0]*D[0]+D[1]*D[1])}if(E==null){E=myCreateElementSVG("path");E.setAttribute("id",F);svgpicture.appendChild(E)}E.setAttribute("d","M"+(G[0]*xunitlength+origin[0])+","+(height-G[1]*yunitlength-origin[1])+" A"+A*xunitlength+","+A*yunitlength+" 0 0,0 "+(B[0]*xunitlength+origin[0])+","+(height-B[1]*yunitlength-origin[1]));E.setAttribute("stroke-width",strokewidth);E.setAttribute("stroke",stroke);E.setAttribute("fill",fill);if(marker=="arrow"||marker=="arrowdot"){u=[(B[1]-G[1])/4,(G[0]-B[0])/4];D=[(B[0]-G[0])/2,(B[1]-G[1])/2];D=[G[0]+D[0]+u[0],G[1]+D[1]+u[1]]}else{D=[G[0],G[1]]}if(marker=="dot"||marker=="arrowdot"){ASdot(G,4,markerstroke,markerfill);if(marker=="arrowdot"){arrowhead(D,B)}ASdot(B,4,markerstroke,markerfill)}else{if(marker=="arrow"){arrowhead(D,B)}}}function ellipse(A,E,D,F){var B;if(F!=null){B=doc.getElementById(F)}if(B==null){B=myCreateElementSVG("ellipse");B.setAttribute("id",F);svgpicture.appendChild(B)}B.setAttribute("cx",A[0]*xunitlength+origin[0]);B.setAttribute("cy",height-A[1]*yunitlength-origin[1]);B.setAttribute("rx",E*xunitlength);B.setAttribute("ry",D*yunitlength);B.setAttribute("stroke-width",strokewidth);B.setAttribute("stroke",stroke);B.setAttribute("fill",fill)}function rect(F,D,G,E,B){var A;if(G!=null){A=doc.getElementById(G)}if(A==null){A=myCreateElementSVG("rect");A.setAttribute("id",G);svgpicture.appendChild(A)}A.setAttribute("x",F[0]*xunitlength+origin[0]);A.setAttribute("y",height-D[1]*yunitlength-origin[1]);A.setAttribute("width",(D[0]-F[0])*xunitlength);A.setAttribute("height",(D[1]-F[1])*yunitlength);if(E!=null){A.setAttribute("rx",E*xunitlength)}if(B!=null){A.setAttribute("ry",B*yunitlength)}A.setAttribute("stroke-width",strokewidth);A.setAttribute("stroke",stroke);A.setAttribute("fill",fill)}function text(B,A,E,D){B[0]=B[0]*xunitlength+origin[0];B[1]=B[1]*yunitlength+origin[1];textabs(B,A,E,D)}function textabs(B,J,G,F,A,H){if(F==null){F=0}else{F=(360-F)%360}var E="middle";var K=0;var I=0;if(F==270){var I=0;var K=fontsize/3;if(G!=null){if(G.match(/left/)){K=-fontsize/2}if(G.match(/right/)){K=fontsize-0}if(G.match(/above/)){E="start";I=-fontsize/2}if(G.match(/below/)){E="end";I=fontsize/2}}}if(F==90){var I=0;var K=-fontsize/3;if(G!=null){if(G.match(/left/)){K=-fontsize-0}if(G.match(/right/)){K=fontsize/2}if(G.match(/above/)){E="end";I=-fontsize/2}if(G.match(/below/)){E="start";I=fontsize/2}}}if(F==0){var K=0;var I=fontsize/3;if(G!=null){if(G.match(/above/)){I=-fontsize/2}if(G.match(/below/)){I=fontsize-0}if(G.match(/right/)){E="start";K=fontsize/2}if(G.match(/left/)){E="end";K=-fontsize/2}}}var D;if(A!=null){D=doc.getElementById(A)}if(D==null){D=myCreateElementSVG("text");D.setAttribute("id",A);svgpicture.appendChild(D);D.appendChild(doc.createTextNode(J))}D.lastChild.nodeValue=J;D.setAttribute("x",B[0]+K);D.setAttribute("y",height-B[1]+I);if(F!=0){D.setAttribute("transform","rotate("+F+" "+(B[0]+K)+" "+(height-B[1]+I)+")")}D.setAttribute("font-style",(H!=null?H:fontstyle));D.setAttribute("font-family",fontfamily);D.setAttribute("font-size",fontsize);D.setAttribute("font-weight",fontweight);D.setAttribute("text-anchor",E);if(fontstroke!="none"){D.setAttribute("stroke",fontstroke)}if(fontfill!="none"){D.setAttribute("fill",fontfill)}D.setAttribute("stroke-width","0px");return B}function ASdot(B,A,D,F){if(D==null){D=stroke}if(F==null){F=fill}var E=myCreateElementSVG("circle");E.setAttribute("cx",B[0]*xunitlength+origin[0]);E.setAttribute("cy",height-B[1]*yunitlength-origin[1]);E.setAttribute("r",A);E.setAttribute("stroke-width",strokewidth);E.setAttribute("stroke",D);E.setAttribute("fill",F);svgpicture.appendChild(E)}function dot(B,F,D,I,H){var E;var A=B[0]*xunitlength+origin[0];var G=height-B[1]*yunitlength-origin[1];if(H!=null){E=doc.getElementById(H)}if(F=="+"||F=="-"||F=="|"){if(E==null){E=myCreateElementSVG("path");E.setAttribute("id",H);svgpicture.appendChild(E)}if(F=="+"){E.setAttribute("d"," M "+(A-ticklength)+" "+G+" L "+(A+ticklength)+" "+G+" M "+A+" "+(G-ticklength)+" L "+A+" "+(G+ticklength));E.setAttribute("stroke-width",0.5);E.setAttribute("stroke",axesstroke)}else{if(F=="-"){E.setAttribute("d"," M "+(A-ticklength)+" "+G+" L "+(A+ticklength)+" "+G)}else{E.setAttribute("d"," M "+A+" "+(G-ticklength)+" L "+A+" "+(G+ticklength))}E.setAttribute("stroke-width",strokewidth);E.setAttribute("stroke",stroke)}}else{if(E==null){E=myCreateElementSVG("circle");E.setAttribute("id",H);svgpicture.appendChild(E)}E.setAttribute("cx",A);E.setAttribute("cy",G);E.setAttribute("r",dotradius);E.setAttribute("stroke-width",strokewidth);E.setAttribute("stroke",stroke);E.setAttribute("fill",(F=="open"?"white":stroke))}if(D!=null){text(B,D,(I==null?"below":I),(H==null?H:H+"label"))}}function arrowhead(H,G){var A;var D=[H[0]*xunitlength+origin[0],height-H[1]*yunitlength-origin[1]];var B=[G[0]*xunitlength+origin[0],height-G[1]*yunitlength-origin[1]];var E=[B[0]-D[0],B[1]-D[1]];var I=Math.sqrt(E[0]*E[0]+E[1]*E[1]);if(I>1e-8){E=[E[0]/I,E[1]/I];A=[-E[1],E[0]];var F=myCreateElementSVG("path");F.setAttribute("d","M "+(B[0]-15*E[0]-4*A[0])+" "+(B[1]-15*E[1]-4*A[1])+" L "+(B[0]-3*E[0])+" "+(B[1]-3*E[1])+" L "+(B[0]-15*E[0]+4*A[0])+" "+(B[1]-15*E[1]+4*A[1])+" z");F.setAttribute("stroke-width",markerstrokewidth);F.setAttribute("stroke",stroke);F.setAttribute("fill",stroke);svgpicture.appendChild(F)}}function chopZ(B){var A=B.indexOf(".");if(A==-1){return B}for(var D=B.length-1;D>A&&B.charAt(D)=="0";D--){}if(D==A){D--}return B.slice(0,D+1)}function grid(B,A){axes(B,A,null,B,A)}function noaxes(){if(!initialized){initPicture()}}function axes(dx,dy,labels,gdx,gdy,dox,doy){var x,y,ldx,ldy,lx,ly,lxp,lyp,pnode,st;if(!initialized){initPicture()}if(typeof dx=="string"){labels=dx;dx=null}if(typeof dy=="string"){gdx=dy;dy=null}if(xscl!=null){dx=xscl;gdx=xscl;labels=dx}if(yscl!=null){dy=yscl;gdy=yscl}if(xtick!=null){dx=xtick}if(ytick!=null){dy=ytick}if(dox==null){dox=true}if(doy==null){doy=true}if(dox=="off"||dox==0){dox=false}else{dox=true}if(doy=="off"||doy==0){doy=false}else{doy=true}dx=(dx==null?xunitlength:dx*xunitlength);dy=(dy==null?dx:dy*yunitlength);fontsize=Math.floor(Math.min(dx/1.5,dy/1.5,16));ticklength=fontsize/4;if(xgrid!=null){gdx=xgrid}if(ygrid!=null){gdy=ygrid}if(gdx!=null){gdx=(typeof gdx=="string"?dx:gdx*xunitlength);gdy=(gdy==null?dy:gdy*yunitlength);pnode=myCreateElementSVG("path");st="";if(dox&&gdx>0){for(x=origin[0];x<=winxmax;x=x+gdx){if(x>=winxmin){st+=" M"+x+","+winymin+" "+x+","+winymax}}for(x=origin[0]-gdx;x>=winxmin;x=x-gdx){if(x<=winxmax){st+=" M"+x+","+winymin+" "+x+","+winymax}}}if(doy&&gdy>0){for(y=height-origin[1];y<=winymax;y=y+gdy){if(y>=winymin){st+=" M"+winxmin+","+y+" "+winxmax+","+y}}for(y=height-origin[1]-gdy;y>=winymin;y=y-gdy){if(y<=winymax){st+=" M"+winxmin+","+y+" "+winxmax+","+y}}}pnode.setAttribute("d",st);pnode.setAttribute("stroke-width",0.5);pnode.setAttribute("stroke",gridstroke);pnode.setAttribute("fill",fill);svgpicture.appendChild(pnode)}pnode=myCreateElementSVG("path");if(dox){st="M"+winxmin+","+(height-origin[1])+" "+winxmax+","+(height-origin[1])}if(doy){st+=" M"+origin[0]+","+winymin+" "+origin[0]+","+winymax}if(dox){for(x=origin[0];x<winxmax;x=x+dx){if(x>=winymin){st+=" M"+x+","+(height-origin[1]+ticklength)+" "+x+","+(height-origin[1]-ticklength)}}for(x=origin[0]-dx;x>winxmin;x=x-dx){if(x<=winxmax){st+=" M"+x+","+(height-origin[1]+ticklength)+" "+x+","+(height-origin[1]-ticklength)}}}if(doy){for(y=height-origin[1];y<winymax;y=y+dy){if(y>=winymin){st+=" M"+(origin[0]+ticklength)+","+y+" "+(origin[0]-ticklength)+","+y}}for(y=height-origin[1]-dy;y>winymin;y=y-dy){if(y<=winymax){st+=" M"+(origin[0]+ticklength)+","+y+" "+(origin[0]-ticklength)+","+y}}}if(labels!=null){with(Math){ldx=dx/xunitlength;ldy=dy/yunitlength;lx=(xmin>0||xmax<0?xmin:0);ly=(ymin>0||ymax<0?ymin:0);lxp=(ly==0?"below":"above");lyp=(lx==0?"left":"right");var ddx=floor(1.1-log(ldx)/log(10))+1;var ddy=floor(1.1-log(ldy)/log(10))+1;if(ddy<0){ddy=0}if(ddx<0){ddx=0}if(dox){for(x=(doy?ldx:0);x<=xmax;x=x+ldx){if(x>=xmin){text([x,ly],chopZ(x.toFixed(ddx)),lxp)}}for(x=-ldx;xmin<=x;x=x-ldx){if(x<=xmax){text([x,ly],chopZ(x.toFixed(ddx)),lxp)}}}if(doy){for(y=(dox?ldy:0);y<=ymax;y=y+ldy){if(y>=ymin){text([lx,y],chopZ(y.toFixed(ddy)),lyp)}}for(y=-ldy;ymin<=y;y=y-ldy){if(y<=ymax){text([lx,y],chopZ(y.toFixed(ddy)),lyp)}}}}}pnode.setAttribute("d",st);pnode.setAttribute("stroke-width",0.5);pnode.setAttribute("stroke",axesstroke);pnode.setAttribute("fill",fill);svgpicture.appendChild(pnode)}function safepow(D,B){if(D<0&&Math.floor(B)!=B){for(var A=3;A<50;A+=2){if(Math.abs(Math.round(A*B)-(A*B))<0.000001){if(Math.round(A*B)%2==0){return Math.pow(Math.abs(D),B)}else{return -1*Math.pow(Math.abs(D),B)}}}return sqrt(-1)}else{return Math.pow(D,B)}}function nthroot(B,A){return safepow(A,1/B)}function nthlogten(B,A){return((Math.log(A))/(Math.log(B)))}function matchtolower(A){return A.toLowerCase()}function mathjs(D,H){D=D.replace("[","(");D=D.replace("]",")");D=D.replace(/arc(sin|cos|tan)/g,"arc $1");if(H!=null){var G=new RegExp("(sqrt|ln|log|sin|cos|tan|sec|csc|cot|abs)[(]","g");D=D.replace(G,"$1#(");var G=new RegExp("("+H+")("+H+")$","g");D=D.replace(G,"($1)($2)");var G=new RegExp("("+H+")(sqrt|ln|log|sin|cos|tan|sec|csc|cot|abs)","g");D=D.replace(G,"($1)$2");var G=new RegExp("("+H+")("+H+")([^a-df-zA-Z#])","g");D=D.replace(G,"($1)($2)$3");var G=new RegExp("("+H+")("+H+")(w*[^(#])","g");D=D.replace(G,"($1)($2)$3");var G=new RegExp("([^a-df-zA-Z])("+H+")([^a-df-zA-Z])","g");D=D.replace(G,"$1($2)$3");var G=new RegExp("^("+H+")([^a-df-zA-Z])","g");D=D.replace(G,"($1)$2");var G=new RegExp("([^a-df-zA-Z])("+H+")$","g");D=D.replace(G,"$1($2)")}D=D.replace(/#/g,"");D=D.replace(/arc\s+(sin|cos|tan)/g,"arc$1");D=D.replace(/\s/g,"");D=D.replace(/(Sin|Cos|Tan|Sec|Csc|Cot|Arc|Abs|Log|Ln)/g,matchtolower);D=D.replace(/log_(\d+)\(/,"nthlog($1,");D=D.replace(/log/g,"logten");if(D.indexOf("^-1")!=-1){D=D.replace(/sin\^-1/g,"arcsin");D=D.replace(/cos\^-1/g,"arccos");D=D.replace(/tan\^-1/g,"arctan");D=D.replace(/sec\^-1/g,"arcsec");D=D.replace(/csc\^-1/g,"arccsc");D=D.replace(/cot\^-1/g,"arccot");D=D.replace(/sinh\^-1/g,"arcsinh");D=D.replace(/cosh\^-1/g,"arccosh");D=D.replace(/tanh\^-1/g,"arctanh");D=D.replace(/sech\^-1/g,"arcsech");D=D.replace(/csch\^-1/g,"arccsch");D=D.replace(/coth\^-1/g,"arccoth")}D=D.replace(/root\((\d+)\)\(/,"nthroot($1,");D=D.replace(/([0-9])E([\-0-9])/g,"$1(EE)$2");D=D.replace(/^e$/g,"(E)");D=D.replace(/pi/g,"(pi)");D=D.replace(/^e([^a-zA-Z])/g,"(E)$1");D=D.replace(/([^a-zA-Z])e$/g,"$1(E)");D=D.replace(/([^a-zA-Z])e([^a-zA-Z])/g,"$1(E)$2");D=D.replace(/([0-9])([\(a-zA-Z])/g,"$1*$2");D=D.replace(/(!)([0-9\(])/g,"$1*$2");D=D.replace(/([0-9])\*\(EE\)([\-0-9])/,"$1e$2");D=D.replace(/\)([\(0-9a-zA-Z])/g,")*$1");var E,B,A,F,I;while((E=D.indexOf("^"))!=-1){if(E==0){return"Error: missing argument"}B=E-1;F=D.charAt(B);if(F>="0"&&F<="9"){B--;while(B>=0&&(F=D.charAt(B))>="0"&&F<="9"){B--}if(F=="."){B--;while(B>=0&&(F=D.charAt(B))>="0"&&F<="9"){B--}}}else{if(F==")"){I=1;B--;while(B>=0&&I>0){F=D.charAt(B);if(F=="("){I--}else{if(F==")"){I++}}B--}while(B>=0&&(F=D.charAt(B))>="a"&&F<="z"||F>="A"&&F<="Z"){B--}}else{if(F>="a"&&F<="z"||F>="A"&&F<="Z"){B--;while(B>=0&&(F=D.charAt(B))>="a"&&F<="z"||F>="A"&&F<="Z"){B--}}else{return"Error: incorrect syntax in "+D+" at position "+B}}}if(E==D.length-1){return"Error: missing argument"}A=E+1;F=D.charAt(A);nch=D.charAt(A+1);if(F>="0"&&F<="9"||(F=="-"&&nch!="(")||F=="."){A++;while(A<D.length&&(F=D.charAt(A))>="0"&&F<="9"){A++}if(F=="."){A++;while(A<D.length&&(F=D.charAt(A))>="0"&&F<="9"){A++}}}else{if(F=="("||(F=="-"&&nch=="(")){if(F=="-"){A++}I=1;A++;while(A<D.length&&I>0){F=D.charAt(A);if(F=="("){I++}else{if(F==")"){I--}}A++}}else{if(F>="a"&&F<="z"||F>="A"&&F<="Z"){A++;while(A<D.length&&(F=D.charAt(A))>="a"&&F<="z"||F>="A"&&F<="Z"){A++}}else{return"Error: incorrect syntax in "+D+" at position "+A}}}D=D.slice(0,B+1)+"safepow("+D.slice(B+1,E)+","+D.slice(E+1,A)+")"+D.slice(A)}while((E=D.indexOf("!"))!=-1){if(E==0){return"Error: missing argument"}B=E-1;F=D.charAt(B);if(F>="0"&&F<="9"){B--;while(B>=0&&(F=D.charAt(B))>="0"&&F<="9"){B--}if(F=="."){B--;while(B>=0&&(F=D.charAt(B))>="0"&&F<="9"){B--}}}else{if(F==")"){I=1;B--;while(B>=0&&I>0){F=D.charAt(B);if(F=="("){I--}else{if(F==")"){I++}}B--}while(B>=0&&(F=D.charAt(B))>="a"&&F<="z"||F>="A"&&F<="Z"){B--}}else{if(F>="a"&&F<="z"||F>="A"&&F<="Z"){B--;while(B>=0&&(F=D.charAt(B))>="a"&&F<="z"||F>="A"&&F<="Z"){B--}}else{return"Error: incorrect syntax in "+D+" at position "+B}}}D=D.slice(0,B+1)+"factorial("+D.slice(B+1,E)+")"+D.slice(E+1)}return D}function slopefield(fun,dx,dy){var g=fun;if(typeof fun=="string"){eval("g = function(x,y){ with(Math) return "+mathjs(fun)+" }")}var gxy,x,y,u,v,dz;if(dx==null){dx=1}if(dy==null){dy=1}dz=Math.sqrt(dx*dx+dy*dy)/6;var x_min=Math.ceil(xmin/dx);var y_min=Math.ceil(ymin/dy);for(x=x_min;x<=xmax;x+=dx){for(y=y_min;y<=ymax;y+=dy){gxy=g(x,y);if(!isNaN(gxy)){if(Math.abs(gxy)=="Infinity"){u=0;v=dz}else{u=dz/Math.sqrt(1+gxy*gxy);v=gxy*u}line([x-u,y-v],[x+u,y+v])}}}}function drawPictures(){drawPics()}function parseShortScript(sscript,gw,gh){if(sscript==null){initialized=false;sscript=picture.sscr}var sa=sscript.split(",");if(gw&&gh){sa[9]=gw;sa[10]=gh;sscript=sa.join(",");picture.setAttribute("sscr",sscript)}picture.setAttribute("width",sa[9]);picture.setAttribute("height",sa[10]);picture.style.width=sa[9]+"px";picture.style.height=sa[10]+"px";if(sa.length>10){commands="setBorder(5);";commands+="width="+sa[9]+"; height="+sa[10]+";";commands+="initPicture("+sa[0]+","+sa[1]+","+sa[2]+","+sa[3]+");";commands+="axes("+sa[4]+","+sa[5]+","+sa[6]+","+sa[7]+","+sa[8]+");";var inx=11;var eqnlist="Graphs: ";while(sa.length>inx+9){commands+='stroke="'+sa[inx+7]+'";';commands+='strokewidth="'+sa[inx+8]+'";';if(sa[inx+9]!=""){commands+='strokedasharray="'+sa[inx+9].replace(/\s+/g,",")+'";'}if(sa[inx]=="slope"){eqnlist+="dy/dx="+sa[inx+1]+"; ";commands+='slopefield("'+sa[inx+1]+'",'+sa[inx+2]+","+sa[inx+2]+");"}else{if(sa[inx]=="func"){eqnlist+="y="+sa[inx+1]+"; ";eqn='"'+sa[inx+1]+'"'}else{if(sa[inx]=="polar"){eqnlist+="r="+sa[inx+1]+"; ";eqn='["cos(t)*('+sa[inx+1]+')","sin(t)*('+sa[inx+1]+')"]'}else{if(sa[inx]=="param"){eqnlist+="[x,y]=["+sa[inx+1]+","+sa[inx+2]+"]; ";eqn='["'+sa[inx+1]+'","'+sa[inx+2]+'"]'}}}if(typeof eval(sa[inx+5])=="number"){commands+="plot("+eqn+","+sa[inx+5]+","+sa[inx+6]+",null,null,"+sa[inx+3]+","+sa[inx+4]+");"}else{commands+="plot("+eqn+",null,null,null,null,"+sa[inx+3]+","+sa[inx+4]+");"}}inx+=10}try{eval(commands)}catch(e){alert("Graph not ready")}picture.setAttribute("alt",eqnlist);return commands}}function drawPics(){var index,nd;pictures=document.getElementsByTagName("embed");var len=pictures.length;for(index=0;index<len;index++){picture=((!ASnoSVG&&isIE)?pictures[index]:pictures[0]);if(!ASnoSVG){initialized=false;var sscr=picture.getAttribute("sscr");if((sscr!=null)&&(sscr!="")){try{parseShortScript(sscr)}catch(e){}}else{src=picture.getAttribute("script");if((src!=null)&&(src!="")){try{with(Math){eval(src)}}catch(err){alert(err+"\n"+src)}}}}else{if(picture.getAttribute("sscr")!=""){n=document.createElement("img");n.setAttribute("style",picture.getAttribute("style"));n.setAttribute("src",AScgiloc+"?sscr="+encodeURIComponent(picture.getAttribute("sscr")));pn=picture.parentNode;pn.replaceChild(n,picture)}}}}function plot(fun,x_min,x_max,points,id,min_type,max_type){var pth=[];var f=function(x){return x},g=fun;var name=null;if(typeof fun=="string"){eval("g = function(x){ with(Math) return "+mathjs(fun)+" }")}else{if(typeof fun=="object"){eval("f = function(t){ with(Math) return "+mathjs(fun[0])+" }");eval("g = function(t){ with(Math) return "+mathjs(fun[1])+" }")}}if(typeof x_min=="string"){name=x_min;x_min=xmin}else{name=id}var min=(x_min==null?xmin:x_min);var max=(x_max==null?xmax:x_max);if(max<=min){return null}var inc=max-min-0.000001*(max-min);inc=(points==null?inc/200:inc/points);var gt;for(var t=min;t<=max;t+=inc){gt=g(t);if(!(isNaN(gt)||Math.abs(gt)=="Infinity")){pth[pth.length]=[f(t),gt]}}path(pth,name);if(min_type==1){arrowhead(pth[1],pth[0])}else{if(min_type==2){dot(pth[0],"open")}else{if(min_type==3){dot(pth[0],"closed")}}}if(max_type==1){arrowhead(pth[pth.length-2],pth[pth.length-1])}else{if(max_type==2){dot(pth[pth.length-1],"open")}else{if(max_type==3){dot(pth[pth.length-1],"closed")}}}return p}function updateCoords(B){switchTo("picture"+(B+1));var D=getX(),A=getY();if((xmax-D)*xunitlength>6*fontsize||(A-ymin)*yunitlength>2*fontsize){text([xmax,ymin],"("+D.toFixed(2)+", "+A.toFixed(2)+")","aboveleft","AScoord"+B,"")}else{text([xmax,ymin]," ","aboveleft","AScoord"+B,"")}}function updateCoords0(){updateCoords(0)}function updateCoords1(){updateCoords(1)}function updateCoords2(){updateCoords(2)}function updateCoords3(){updateCoords(3)}function updateCoords4(){updateCoords(4)}function updateCoords5(){updateCoords(5)}function updateCoords6(){updateCoords(6)}function updateCoords7(){updateCoords(7)}function updateCoords8(){updateCoords(8)}function updateCoords9(){updateCoords(9)}ASfn=[function(){updatePicture(0)},function(){updatePicture(1)},function(){updatePicture(2)},function(){updatePicture(3)},function(){updatePicture(4)},function(){updatePicture(5)},function(){updatePicture(6)},function(){updatePicture(7)},function(){updatePicture(8)},function(){updatePicture(9)}];ASupdateCoords=[function(){updateCoords(0)},function(){updateCoords(1)},function(){updateCoords(2)},function(){updateCoords(3)},function(){updateCoords(4)},function(){updateCoords(5)},function(){updateCoords(6)},function(){updateCoords(7)},function(){updateCoords(8)},function(){updateCoords(9)}];function generic(){drawPictures()}if(typeof window.addEventListener!="undefined"){window.addEventListener("load",generic,false)}else{if(typeof document.addEventListener!="undefined"){document.addEventListener("load",generic,false)}else{if(typeof window.attachEvent!="undefined"){window.attachEvent("onload",generic)}else{if(typeof window.onload=="function"){var existing=onload;window.onload=function(){existing();generic()}}else{window.onload=generic}}}}if(checkIfSVGavailable){checkifSVGavailable=false;nd=isSVGavailable();ASnoSVG=nd!=null};