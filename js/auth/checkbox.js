function doCheckbox(elem) {

  if (elem.className=='boxCheckbox' && elem.parentNode.tagName.toLowerCase()=='div') {
    elem.parentNode.className='box'+(elem.checked?'Checked':'Unchecked');
  }
}


var css=document.styleSheets[0];
try {
  css.addRule('.boxCheckbox', 'filter: progid:DXImageTransform.Microsoft.Alpha(opacity=0);');
}
catch(e) {
  css.insertRule('.boxCheckbox { -moz-opacity: 0; -khtml-opacity: 0; }', css.cssRules.length);
}

var el=document.getElementsByTagName('input');
for (var i=0; i<el.length; i++) {
  if (el[i].type.toLowerCase()=='checkbox') {
    doCheckbox(el[i]);
  }
}