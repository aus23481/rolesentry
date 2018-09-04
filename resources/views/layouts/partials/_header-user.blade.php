
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		
		<title>Recruiter Intel</title>
		<link rel="icon" href="favicon.ico" type="image/gif" sizes="16x16">
		<!-- Bootstrap & Icons CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/4.0.0-14/css/ionicons.min.css">
		
		<!--link rel="stylesheet" href="http://recruiterintel.com/Features-Boxed.css"-->
		<!--link rel="stylesheet" href="http://recruiterintel.com/Footer-Dark.css"-->
		<!--link rel="stylesheet" href="http://recruiterintel.com/Navigation-Menu.css"-->
		<!--link rel="stylesheet" href="http://recruiterintel.com/Newsletter-Subscription-Form.css"-->
		<!--link rel="stylesheet" href="http://recruiterintel.com/Pretty-Header.css"-->
		<!--link rel="stylesheet" href="http://recruiterintel.com/styles.css"-->
		
		<!-- General Header CSS -->
		<!--<link rel="stylesheet" href="css/style-header.css">-->
		<link rel="stylesheet" href="css/style-footer.css">
		
		<!-- Homepage CSS -->
		<link rel="stylesheet" href="css/homestyle.css">
		<link rel="stylesheet" href="https://recruiterintel.com/colorbox.css">
		<link rel="stylesheet" href="css/popout.css">
		<link rel="stylesheet" href="css/style-popout.css">
		<link rel="stylesheet" href="css/style-why-section.css">
		
		<!-- Other Pages CSS -->
		<link rel="stylesheet" href="css/style-platform-redesign.css">
		<link rel="stylesheet" href="css/style-preferences.css">
		<link rel="stylesheet" href="css/style-account.css">
		<link rel="stylesheet" href="css/style-modals.css">
		<link rel="stylesheet" href="css/style-platform-invoice.css">

		<!-- New Header CSS -->
		<link rel="stylesheet" href="css/style-new-header.css"> <!-- new header style -->		
		<!-- Pages CSS -->
		<link rel="stylesheet" href="css/style-about-page.css"> <!-- about page style -->
		<link rel="stylesheet" href="css/style-faq-page.css"> <!-- faq page style -->		
		<!-- Contact Section CSS -->
		<link rel="stylesheet" href="css/style-contact-section.css"> <!-- contact section style -->
		

		<!-- Added CSS -->
		<link rel="stylesheet" href="css/revisions.css">
		<link rel="stylesheet" href="css/style-platform-updated.css">
		<link rel="stylesheet" href="css/style-platform-modals.css">
		
		<link rel="stylesheet" href="css/style-email-page.css">
		<link rel="stylesheet" href="css/style-modal-history.css">
		<link rel="stylesheet" href="css/style-prospecting.css">
		<link rel="stylesheet" href="css/style-three-modals.css">
		
		
		
		<!-- Fonts -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Abel">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700">
		<link rel="stylesheet" href="css/introjs.css">
		<!-- JS -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
		<script src="https://recruiterintel.com/js/jquery.colorbox.js"></script>
		<!-- select2 -->
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
		
		<script>
			var baseurl = "{{url ('/')}}";
			//alert(baseurl);
			var _token = '<?php echo csrf_token(); ?>';
			var _platform = '<?php echo isset($platform) ? $platform : 1; ?>';
			</script>
<script>
/*	
window['_fs_debug'] = false;
window['_fs_host'] = 'fullstory.com';
window['_fs_org'] = 'BW3RJ';
window['_fs_namespace'] = 'FS';
(function(m,n,e,t,l,o,g,y){
    if (e in m) {if(m.console && m.console.log) { m.console.log('FullStory namespace conflict. Please set window["_fs_namespace"].');} return;}
    g=m[e]=function(a,b){g.q?g.q.push([a,b]):g._api(a,b);};g.q=[];
    o=n.createElement(t);o.async=1;o.src='https://'+_fs_host+'/s/fs.js';
    y=n.getElementsByTagName(t)[0];y.parentNode.insertBefore(o,y);
    g.identify=function(i,v){g(l,{uid:i});if(v)g(l,v)};g.setUserVars=function(v){g(l,v)};
    y="rec";g.shutdown=function(i,v){g(y,!1)};g.restart=function(i,v){g(y,!0)};
    y="consent";g[y]=function(a){g(y,!arguments.length||a)};
    g.identifyAccount=function(i,v){o='account';v=v||{};v.acctId=i;g(o,v)};
    g.clearUserCookie=function(){};
})(window,document,window['_fs_namespace'],'script','user');
*/
</script>

<script>
  /*  (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:897934,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
*/
</script>
    

	</head>

<body id="platform-page" cz-shortcut-listen="true">

	<!--
	<div id="loading" style="width:200px;position:absolute;left:50%;top:79%;z-index:999;display:none">
		<img  style="width:100px;" src="images/loading.gif"> 
		<span style="color:#FB8B14;font-weight:bold;text-align:center;clear:both"><br />Loading Results</span>
	</div>	
-->
