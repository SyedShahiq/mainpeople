{crmScript ext=com.listfixer.regpoll file=regpolladmin.js}
{crmStyle ext=com.listfixer.regpoll file=regpolladmin.css}
<div class="extra-wide" id="poll-block">
<div class="crm-form-block crm-block">
<table><tr><td rowspan=4 width=250>Filter Location List:<br>
<input onchange="regpoll_polllist()" type=checkbox id="unstaffed"> Include Unstaffed Locations<br>
<input onchange="regpoll_polllist()" type=checkbox id="secondary"> Include Secondary Locations<br>
<br>
<select onchange="regpoll_polllist()" id="org-id">
{foreach from=$orgs key=o item=org}
<option value={$o}{if $o eq $default_org} SELECTED{/if}>{$org}</option>
{/foreach}
</select>
<td rowspan=4 width=250>Search for existing contacts to schedule:<br><input type=text autocomplete=off onkeyup="regpoll_change()" id="search-string">
<br>You have to search for an existing<br>contact before adding a new one.</td>
<td width=40>Volunteer:</td><td class="center" width=30 id="selected-contact-id"></td><td colspan=4 id="selected-contact-name">&nbsp;</td></tr>
<tr><td width=40>Location:</td><td class="center" width=30 id="selected-poll-id"></td><td colspan=4 id="selected-poll-name">&nbsp;</td></tr>
<tr><td width=40>Shifts:</td><td></td><td class="center">1&nbsp;<input id="shift-1" type=checkbox></td><td class="center">2&nbsp;<input id="shift-2" type=checkbox></td><td class="center">3&nbsp;<input id="shift-3" type=checkbox></td><td class="center">4&nbsp;<input id="shift-4" type=checkbox></td></tr>
<tr><td width=40>Captain:</td><td class="center"><input id="captain" type=checkbox></td><td colspan=4 class="center"><input onclick="regpoll_save()" type=button value=Save></td>
</tr></table>
<div id="poll-list"></div>
<div class="search-box" id="search-results"></div>
<div class="edit-schedule-box" id="edit-schedule"></div>
<div class="add-contact-box" id="add-contact">
<table>
<tr><td><div class="wizard-label">First Name:</div> <input id="first-name">
	<div class="wizard-label">Last Name:</div> <input id="last-name"></td></tr>

<tr><td><div class="wizard-label">Phone:</div> <input id="phone"></td></tr>

<tr><td><div class="wizard-label">Email:</div> <input size=50 id="email"></td></tr>

<tr><td><div class="wizard-label">Address:</div> <input size=50 id="address"></td></tr>

<tr><td><div class="wizard-label">City:</div> <input name=city id="city">
	<div class="wizard-label">Zip Code:</div> <input size=10 id="zip"></td></tr>

<tr><td class="right"><input onclick="regpoll_add_contact_save()" type=button value=Save>
	<input onclick="regpoll_add_contact_cancel()" type=button value=Cancel></td></tr>
</table>
</div>
</div>
