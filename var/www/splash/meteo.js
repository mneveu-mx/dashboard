conte = document.getElementById('cont_NTkzNTB8NHwzfDF8M3xGOEEwQzJ8MnxGRkZGRkZ8Y3wx');
enlace = document.getElementById('spa_NTkzNTB8NHwzfDF8M3xGOEEwQzJ8MnxGRkZGRkZ8Y3wx');
var ua = navigator.userAgent.toLowerCase();

if (conte && enlace){ 
enlace.style.cssText = 'font:normal 10px/12px Tahoma, Arial, Helvetica, serif; color:#333; padding:0 0 3px; text-decoration: none;';
conte.style.cssText = 'width:480px;'; 
elem = document.createElement('iframe');
elem.id = 'NTkzNTB8NHwzfDF8M3xGOEEwQzJ8MnxGRkZGRkZ8Y3wx'; 
elem.src = 'http://widget.meteocity.com/NTkzNTB8NHwzfDF8M3xGOEEwQzJ8MnxGRkZGRkZ8Y3wx/'; 
elem.allowTransparency = true; 
elem.frameBorder = 0; 
elem.scrolling = 'no';
elem.name = 'frame'; 
elem.height = '200'; 
elem.width = '480'; 
conte.insertBefore(elem,enlace); 
}
