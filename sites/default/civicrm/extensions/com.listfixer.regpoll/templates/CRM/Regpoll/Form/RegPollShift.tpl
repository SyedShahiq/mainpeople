{crmStyle ext=com.listfixer.regpoll file=regpoll.css}
<div class="crm-form-block crm-block">
<h2>Step 4:  Select Shifts</h2>
<p>Which shifts do you want to volunteer?</p>
<table class=wizard>
	<tr><td>{$form.s1.html} {$s1_label}</td></tr>
	<tr><td>{$form.s2.html} {$s2_label}</td></tr>
	<tr><td>{$form.s3.html} {$s3_label}</td></tr>
	<tr><td>{$form.s4.html} {$s4_label}</td></tr>
</table>
<p><strong>Poll Captains</strong> help coordinate shifts and volunteers for a polling location.
<strong>Poll Captains</strong> are also responsible for gathering notarized petitions from shift volunteers.
This is one of the most important roles in the campaign.
<table class=wizard>
	<tr><td>{$form.captain.html} I want to be a Poll Captain</td></tr>
</table>
<p>Are you a notary?</p>
<table class=wizard>
	<tr><td>{$form.notary.html} I am a notary</td></tr>
	<tr><td><div class="buttons-right">{include file="CRM/common/formButtons.tpl" location="bottom"}</div></td></tr>
</table>
</div>
<p><font size=3>Can't volunteer at any of these times, but still want to volunteer?
<a href=mailto:taryn@mainepeoplesalliance.org?subject=Volunteer>Click here and let us know!</a></font></p>