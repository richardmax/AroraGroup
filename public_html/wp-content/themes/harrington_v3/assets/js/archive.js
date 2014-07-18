jQuery(document).ready(function ($) {
					$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
						var cookie;
						switch ($(e.target).html()) {
							case 'Description' : cookie = '0'; break;
							case 'Specifications' : cookie = '1'; break;
							case 'Map' : cookie = '2'; break;
						}
						console.log(cookie);
						document.cookie = "tabnum="+cookie+';path=/';
					  console.log(document.cookie);
					})
					var reg = document.cookie.match(/tabnum/);
					console.log(document.cookie);
					if (reg != null ){
					if (reg[0] == 'tabnum') {
						reg = document.cookie.match(/tabnum=(.+?);?/);
						console.log('reg:'+reg);
						if (reg != null ){
						if ( reg[1] == '0' || reg[1] == '1' || reg[1] == '2' ) {
						console.log(reg[1]);
						var num;
						num = reg[1]; 
						var size;
						size = $('#myTab li').size();
						console.log('size:'+size);
						if (size==2 && reg[1] == '2') {var param = '#myTab li:eq(0) a';} else{
						var param = '#myTab li:eq('+num+') a';	}		
							$(param).tab('show');
						} else {
							console.log('b');
							$('#myTab li:eq(0) a').tab('show');
						}
						} else {
							$('#myTab li:eq(0) a').tab('show');
						}
					} else {
						console.log('c');
						$('#myTab li:eq(0) a').tab('show');
					}
					}else {
						console.log('d');
						$('#myTab li:eq(0) a').tab('show');
					}
					//$('a[data-toggle="tab"]:first').tab('show');
					$('.avail').parent().parent().addClass('available');
					$('.imgs a').addClass('thumbnail noselect ngg-fancybox fancybox');
				  });	