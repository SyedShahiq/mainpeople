var my_timer;

jQuery( document ).ready( function( ) {
	regpoll_polllist( );
	regpoll_add_contact_cancel( );
} );

function regpoll_polllist( )
{
	var xmlhttp = new XMLHttpRequest( );

	xmlhttp.onreadystatechange = function( ) {
		if ( xmlhttp.readyState == 4 && xmlhttp.status == 200 )
			document.getElementById( "poll-list" ).innerHTML = xmlhttp.responseText;
	}

	var org_id = document.getElementById( "org-id" ).value;
	var unstaffed = document.getElementById( "unstaffed" ).checked;
	var secondary = document.getElementById( "secondary" ).checked;

	xmlhttp.open( 'GET', '/civicrm/ajax/regpollpolllist?org='+org_id+'&unstaffed='+unstaffed+'&secondary='+secondary, true );
	xmlhttp.send( );
}

function regpoll_change( )
{
	clearTimeout( my_timer );

	hide_list( );

	var search_string = document.getElementById( "search-string" ).value;

	if ( typeof search_string != "undefined" && search_string.length > 2 )
		my_timer = setTimeout( function( ) { regpoll_search( ); }, 1000 );
}

function regpoll_search( )
{
	var search_string = document.getElementById( "search-string" ).value;

	if ( search_string.length < 3 ) {
		alert( "You must specify a search of at least three charaters" );
		return;
	}

	search_string = search_string.replace( / /g, "+" );
	search_string = search_string.replace( /[^0-9A-Za-z._+@']/g, "" );

	var xmlhttp = new XMLHttpRequest( );

	xmlhttp.onreadystatechange = function( ) {
		if ( xmlhttp.readyState == 4 && xmlhttp.status == 200 ) {
			var rsp = xmlhttp.responseText;
			if ( rsp.length )
				show_list( rsp );
			else
				alert( "No match found." );
		}
	}

	xmlhttp.open( "GET", "/civicrm/ajax/regpollsearch?x="+search_string, true );
	xmlhttp.send( );
}

function show_list( html )
{
	var info = document.getElementById( "search-results" );
	set_screen_location( '#search-string', info );
	info.innerHTML = html;
}

function item_hover( contact_id )
{
	rsp = document.getElementsByClassName( "regpoll-row" );
	num_rsp = rsp.length;
	for ( i = 0; i < num_rsp; i++ )
		rsp[i].style.backgroundColor = "";

	document.getElementById( "regpoll-"+contact_id ).style.backgroundColor="yellow";	
}

function item_select( contact_id, contact_name )
{
	hide_list( );
	document.getElementById( "selected-contact-id" ).innerHTML = contact_id;
	document.getElementById( "selected-contact-name" ).innerHTML = contact_name;
}

function hide_list( )
{
	document.getElementById( "search-results" ).style.display = "none";
}

function poll_select( poll_id, poll_name )
{
	document.getElementById( "selected-poll-id" ).innerHTML = poll_id;
	document.getElementById( "selected-poll-name" ).innerHTML = poll_name;
}

function regpoll_add_contact( )
{
	hide_list( );
	var info = document.getElementById( "add-contact" );
	set_screen_location( '#poll-block', info );
}

function regpoll_add_contact_save( )
{
	var first_name = document.getElementById( 'first-name' ).value;
	document.getElementById( 'first-name' ).value = first_name = first_name.replace( /[^A-Za-z -']/g, '' );

	var last_name = document.getElementById( 'last-name' ).value;
	document.getElementById( 'last-name' ).value = last_name = last_name.replace( /[^A-Za-z -']/g, '' );

	var phone = document.getElementById( 'phone' ).value;

	var email = document.getElementById( 'email' ).value;
	document.getElementById( 'email' ).value = email = email.replace( /[^A-Za-z0-9@._+-]/g, '' );

	var address = document.getElementById( 'address' ).value;
	document.getElementById( 'address' ).value = address = address.replace( /[^0-9A-Za-z0-9 -']/g, '' );

	var city = document.getElementById( 'city' ).value;
	document.getElementById( 'city' ).value = city = city.replace( /[^A-Za-z -']/g, '' );

	var zip = document.getElementById( 'zip' ).value;
	document.getElementById( 'zip' ).value = zip = zip.replace( /[^0-9-]/g, '' );

	if ( !first_name.length ) {
		alert( 'First name required.' );
		return;
	}

	if ( !last_name.length ) {
		alert( 'Last name required.' );
		return;
	}

	if ( !phone.length && !email.length ) {
		alert( 'Phone or email address required.' );
		return;
	}

	var just_digits = phone.replace( /[\D]/g, '' );

	if ( just_digits.length == 7 )
		just_digits = '207' + just_digits;

	if ( just_digits.length != 10 && phone.length ) {
		alert( 'Invalid telephone number.' );
		return;
	}

	if ( phone.length ) {
		if ( just_digits.length )
			phone = '(' + just_digits.substring(0,3) + ') ' + just_digits.substring(3,6) + '-' + just_digits.substring(6);
		else
			phone = '';
		document.getElementById( 'phone' ).value = phone;
	}

	if ( email.search( /^[A-Z0-9._%+-]+@[A-Z0-9.0]+\.[A-Z]{2,4}$/i ) == -1 && email.length ) {
		alert( 'Invalid email address.' );
		return;
	}

	if ( zip.length && zip.length != 5 && zip.length != 10 ) {
		alert( 'Invalid zip code.' );
		return;
	}

	var xmlhttp = new XMLHttpRequest( );

	xmlhttp.onreadystatechange = function( ) {
		if ( xmlhttp.readyState == 4 && xmlhttp.status == 200 ) {
			var contact_id = xmlhttp.responseText;
			if ( isNaN( contact_id ) || !contact_id )
				alert( 'Unable to add new contact.' );
			else {
				document.getElementById( 'selected-contact-id' ).innerHTML = contact_id;
				document.getElementById( 'selected-contact-name' ).innerHTML = first_name+' '+last_name;
				alert( 'Contact added.' );
			}
			regpoll_add_contact_cancel( )
		}
	}

	xmlhttp.open( 'POST', '/civicrm/ajax/regpollcontact', true );
	xmlhttp.setRequestHeader( 'Content-type', 'application/x-www-form-urlencoded' );
	xmlhttp.send(  'first_name='+first_name+
						'&last_name='+last_name+
						'&phone='+phone+
						'&email='+email+
						'&address='+address+
						'&city='+city+
						'&zip='+zip );
}

function regpoll_add_contact_cancel( )
{
	document.getElementById( "add-contact" ).style.display = "none";
}

function regpoll_save( )
{
	var contact_id = document.getElementById( "selected-contact-id" ).innerHTML;
	if ( typeof contact_id == "undefined" || !contact_id.length || isNaN( contact_id ) ) {
		alert( "No volunteer selected." );
		return;
	}

	var poll_id = document.getElementById( "selected-poll-id" ).innerHTML;
	if ( typeof poll_id == "undefined" || !poll_id.length || isNaN( poll_id )  ) {
		alert( "No polling location selected." );
		return;
	}

	var captain = document.getElementById( "captain" ).checked;
	var shift_1 = document.getElementById( "shift-1" ).checked;
	var shift_2 = document.getElementById( "shift-2" ).checked;
	var shift_3 = document.getElementById( "shift-3" ).checked;
	var shift_4 = document.getElementById( "shift-4" ).checked;

	if ( !captain && !shift_1 && !shift_2 && !shift_3 && !shift_4 ) {
		alert( "No shift selected." );
		return;
	}

	var search_string = document.getElementById( "search-string" ).value;

	if ( search_string.length < 3 ) {
		alert( "You must specify a search of at least three charaters" );
		return;
	}

	var xmlhttp = new XMLHttpRequest( );

	xmlhttp.onreadystatechange = function( ) {
		if ( xmlhttp.readyState == 4 && xmlhttp.status == 200 ) {
			regpoll_polllist( );
			if ( xmlhttp.responseText )
				alert( xmlhttp.responseText );
		}
	}

	xmlhttp.open( "GET", "/civicrm/ajax/regpollschedule?cid="+contact_id+"&pid="+poll_id+"&capt="+captain+"&s1="+shift_1+"&s2="+shift_2+"&s3="+shift_3+"&s4="+shift_4, true );
	xmlhttp.send( );
}

function regpoll_edit_schedule( contact_id, poll_id )
{
	var xmlhttp = new XMLHttpRequest( );

	xmlhttp.onreadystatechange = function( ) {
		if ( xmlhttp.readyState == 4 && xmlhttp.status == 200 ) {
			var rsp = xmlhttp.responseText;
			if ( rsp.length )
				show_edit( rsp, poll_id );
			else
				alert( "Not found." );
		}
	}

	xmlhttp.open( "GET", "/civicrm/ajax/regpollgetsched?cid="+contact_id+"&pid="+poll_id, true );
	xmlhttp.send( );
}

function show_edit( html, poll_id )
{
	var info = document.getElementById( "edit-schedule" );
	set_screen_location( '#poll-' + poll_id, info );
	info.innerHTML = html;
}

function regpoll_edit_schedule_save( contact_id, poll_id )
{
	var xmlhttp = new XMLHttpRequest( );

	xmlhttp.onreadystatechange = function( ) {
		if ( xmlhttp.readyState == 4 && xmlhttp.status == 200 ) {
			regpoll_edit_schedule_cancel( );
			regpoll_polllist( );
			if ( xmlhttp.responseText )
				alert( xmlhttp.responseText );
		}
	}

	var url = "/civicrm/ajax/regpollputsched?cid="+contact_id+"&pid="+poll_id;

	for ( var i = 1; i< 14; i++ )
		url += '&s' + i + '=' + ( document.getElementById( "e"+i ).checked ? '1' : '0' );

	url += '&sid=' + document.getElementById( 'sid' ).value;
	url += '&pc=' + ( document.getElementById( 'pc' ).checked ? '1' : '0' );

	xmlhttp.open( "GET", url, true );
	xmlhttp.send( );
}

function regpoll_edit_schedule_cancel( )
{
	document.getElementById( "edit-schedule" ).style.display = "none";
}

function set_screen_location( e, popup )
{ 
	var pos = jQuery( e ).offset( );
	jQuery( popup ).show( ).offset( { top: pos.top + 50 } );
}
