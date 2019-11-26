{crmStyle ext=com.listfixer.regpoll file=regpoll.css}
{$description}
<div class="crm-form-block crm-block">
<h2>Email Address</h2>
<p>Please enter your email address.</p>
<table>
	<tr><td><div class="wizard-label">{$form.email.label}</div> {$form.email.html}</td></tr>
	<tr><td><div class="buttons-right">{include file="CRM/common/formButtons.tpl" location="bottom"}</div></td></tr>
</table>
</div>
