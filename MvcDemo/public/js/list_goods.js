/**
 * 
 */

window.onload = function(){
				var good = document.getElementsByClassName('good');
				var a = document.getElementsByClassName('a');
				
				for(i = 0; i < good.length; i++){
				  	good[i].addEventListener('mouseover',onDrop);
					function onDrop(){
					this.children.item(1).style.display = 'block';
					}
					good[i].addEventListener('mouseout',outDrop);
					function outDrop(){
					this.children.item(1).style.display = 'none';
					}
				}
			}